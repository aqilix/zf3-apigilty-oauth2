<?php

namespace Aqilix\OAuth2\Entity;

use Aqilix\ORM\Entity\EntityInterface;

/**
 * OauthAccessTokens
 */
class OauthAccessTokens implements EntityInterface
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var \DateTime
     */
    private $expires = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     */
    private $scope;

    /**
     * @var string
     */
    private $accessToken;


    /**
     * Set clientId
     *
     * @param string $clientId
     *
     * @return OauthAccessTokens
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set userId
     *
     * @param string $userId
     *
     * @return OauthAccessTokens
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set expires
     *
     * @param \DateTime $expires
     *
     * @return OauthAccessTokens
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Get expires
     *
     * @return \DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set scope
     *
     * @param string $scope
     *
     * @return OauthAccessTokens
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set accessToken
     *
     * @param string $accessToken
     *
     * @return OauthAccessTokens
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
