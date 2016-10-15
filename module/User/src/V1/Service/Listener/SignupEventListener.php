<?php
namespace User\V1\Service\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Aqilix\OAuth2\Mapper\OauthUsers as UserMapper;
use User\Mapper\UserProfile as UserProfileMapper;
use User\Mapper\UserActivation as UserActivationMapper;
use Aqilix\OAuth2\Mapper\OauthAccessTokens as AccessTokenMapper;
use Aqilix\OAuth2\Mapper\OauthRefreshTokens as RefreshTokenMapper;
use Aqilix\OAuth2\ResponseType\AccessToken as OAuth2AccessToken;
use User\V1\SignupEvent;

class SignupEventListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $config;

    protected $userMapper;

    protected $userProfileMapper;

    protected $userActivationMapper;

    protected $oauth2AccessToken;

    protected $accessTokenMapper;

    protected $refreshTokenMapper;

    /**
     * Constructor
     *
     * @param OAuth2AccessToken $oauth2AccessToken
     * @param UserMapper $userMapper
     * @param UserProfileMapper $userProfileMapper
     * @param UserActivationMapper $userActivationMapper
     * @param AccessTokenMapper $accessTokenMapper
     * @param RefreshTokenMapper $refreshTokenMapper
     */
    public function __construct(
        OAuth2AccessToken $oauth2AccessToken,
        UserMapper $userMapper,
        UserProfileMapper $userProfileMapper,
        UserActivationMapper $userActivationMapper,
        AccessTokenMapper $accessTokenMapper,
        RefreshTokenMapper $refreshTokenMapper,
        array $config = []
    ) {
        $this->userMapper   = $userMapper;
        $this->oauth2AccessToken  = $oauth2AccessToken;
        $this->accessTokenMapper  = $accessTokenMapper;
        $this->userProfileMapper  = $userProfileMapper;
        $this->userActivationMapper = $userActivationMapper;
        $this->refreshTokenMapper   = $refreshTokenMapper;
        $this->config = $config;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            SignupEvent::EVENT_INSERT_USER,
            [$this, 'createUser'],
            499
        );
        $this->listeners[] = $events->attach(
            SignupEvent::EVENT_INSERT_USER,
            [$this, 'createAccessToken'],
            498
        );
        $this->listeners[] = $events->attach(
            SignupEvent::EVENT_INSERT_USER,
            [$this, 'createUserProfile'],
            497
        );
        $this->listeners[] = $events->attach(
            SignupEvent::EVENT_INSERT_USER,
            [$this, 'createActivation'],
            496
        );
    }

    /**
     * Create New User
     *
     * @param  $event
     */
    public function createUser($event)
    {
        try {
            $user = new \Aqilix\OAuth2\Entity\OauthUsers;
            $signupData = $event->getParams()->getSignupData();
            $password   = $this->getUserMapper()
                               ->getPasswordHash($signupData['password']);
            $user->setUsername($signupData['email']);
            $user->setPassword($password);
            $this->getUserMapper()->save($user);
            $event->getParams()->setUserEntity($user);
        } catch (\Exception $e) {
            $event->stopPropagation(true);
            return $e;
        }
    }

    /**
     * Create New User
     *
     * @param  $event
     */
    public function createUserProfile($event)
    {
        try {
            $user = $event->getParams()->getUserEntity();
            $userProfile = new \User\Entity\UserProfile;
            $userProfile->setUser($user);
            $this->getUserMapper()->save($userProfile);
        } catch (\Exception $e) {
            $event->stopPropagation(true);
            return $e;
        }
    }

    public function createAccessToken($event)
    {
        $clientId = $this->getConfig()['client_id'];
        $userId   = $event->getParams()->getUserEntity()->getUsername();
        $now = new \DateTime('now');
        $accessTokensExpires = new \DateTime();
        $accessTokensExpires->setTimestamp($now->getTimestamp() + $this->getConfig()['expires_in']);
        $refreshTokenExpires = new \DateTime('now');
        $refreshTokenExpires->add(new \DateInterval('P14D'));

        try {
            // insert access_token
            $accessTokens = new \Aqilix\OAuth2\Entity\OauthAccessTokens();
            $accessTokens->setClientId($clientId)
                ->setAccessToken($this->getOauth2AccessToken()->generateToken())
                ->setExpires($accessTokensExpires)
                ->setUserId($userId);
            $this->getAccessTokenMapper()->save($accessTokens);

            // insert refresh_token
            $refreshTokens = new \Aqilix\OAuth2\Entity\OauthRefreshTokens();
            $refreshTokens->setClientId($clientId)
                ->setRefreshToken($this->getOauth2AccessToken()->generateToken())
                ->setExpires($refreshTokenExpires)
                ->setUserId($userId);
            $this->getRefreshTokenMapper()->save($refreshTokens);
        } catch (\Exception $e) {
            $event->stopPropagation(true);
            return $e;
        }

        // set accessToken response
        $accessTokensResponse = [
            'access_token'  => $accessTokens->getAccessToken(),
            'expires_in' => $this->getConfig()['expires_in'],
            'token_type' => $this->getConfig()['token_type'],
            'scope' => $this->getConfig()['scope'],
            'refresh_token' => $refreshTokens->getRefreshToken()
        ];

        $event->getParams()->setAccessTokenResponse($accessTokensResponse);
    }

    public function createActivation($event)
    {
        try {
            $expiration = new \DateTime();
            // 14 day expiration
            $expiration->add(new \DateInterval('P14D'));
            $user = $event->getParams()->getUserEntity();
            $userActivation = new \User\Entity\UserActivation;
            $userActivation->setUser($user);
            $userActivation->setExpiration($expiration);
            $this->getUserActivationMapper()->save($userActivation);
            $event->getParams()->setUserActivationKey($userActivation->getUuid());
        } catch (\Exception $e) {
            $event->stopPropagation(true);
            return $e;
        }
    }

    /**
     * @return the $config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return the $userMapper
     */
    public function getUserMapper()
    {
        return $this->userMapper;
    }

    /**
     * Set UserMapper
     *
     * @param UserMapper $userMapper
     */
    public function setUserMapper(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
    }

    /**
     * @return the $userProfileMapper
     */
    public function getUserProfileMapper()
    {
        return $this->userProfileMapper;
    }

    /**
     * @param UserProfileMapper $userProfileMapper
     */
    public function setUserProfileMapper(UserProfileMapper $userProfileMapper)
    {
        $this->userProfileMapper = $userProfileMapper;
    }

    /**
     * @return the $accessTokenMapper
     */
    /**
     * @return the $userActivationMapper
     */
    public function getUserActivationMapper()
    {
        return $this->userActivationMapper;
    }

    /**
     * @param UserActivationMapper $userActivationMapper
     */
    public function setUserActivationMapper($userActivationMapper)
    {
        $this->userActivationMapper = $userActivationMapper;
    }

    /**
     * Get AccessTokenMapper
     *
     * @return AccessTokenMapper
     */
    public function getAccessTokenMapper()
    {
        return $this->accessTokenMapper;
    }

    /**
     * Set AccessTokenMapper
     *
     * @param AccessTokenMapper $accessTokenMapper
     */
    public function setAccessTokenMapper(AccessTokenMapper $accessTokenMapper)
    {
        $this->accessTokenMapper = $accessTokenMapper;
    }

    /**
     * @return the $refreshTokenMapper
     */
    public function getRefreshTokenMapper()
    {
        return $this->refreshTokenMapper;
    }

    /**
     * @param RefreshTokenMapper $refreshTokenMapper
     */
    public function setRefreshTokenMapper($refreshTokenMapper)
    {
        $this->refreshTokenMapper = $refreshTokenMapper;
    }

    /**
     * @return the $oauth2AccessToken
     */
    public function getOauth2AccessToken()
    {
        return $this->oauth2AccessToken;
    }

    /**
     * @param OAuth2AccessToken $oauth2AccessToken
     */
    public function setOauth2AccessToken($oauth2AccessToken)
    {
        $this->oauth2AccessToken = $oauth2AccessToken;
    }
}
