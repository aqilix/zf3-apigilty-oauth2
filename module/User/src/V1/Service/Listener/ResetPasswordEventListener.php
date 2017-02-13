<?php
namespace User\V1\Service\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Psr\Log\LoggerAwareTrait;
use User\V1\ResetPasswordEvent;
use User\Mapper\ResetPassword as ResetPasswordMapper;
use Aqilix\OAuth2\Mapper\OauthUsers as UserMapper;

class ResetPasswordEventListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    use LoggerAwareTrait;

    /**
     * @var \User\Mapper\ResetPassword
     */
    protected $resetPasswordMapper;

    /**
     * @var \Aqilix\OAuth2\Mapper\OauthUsers
     */
    protected $userMapper;

    /**
     * Constructor
     */
    public function __construct(
        ResetPasswordMapper $resetPasswordMapper,
        UserMapper $userMapper
    ) {

        $this->setResetPasswordMapper($resetPasswordMapper);
        $this->setUserMapper($userMapper);
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            ResetPasswordEvent::EVENT_RESET_PASSWORD_CONFIRM_EMAIL,
            [$this, 'create'],
            499
        );
        $this->listeners[] = $events->attach(
            ResetPasswordEvent::EVENT_RESET_PASSWORD_CONFIRM_EMAIL_SUCCESS,
            [$this, 'setResetPasswordKey'],
            500
        );
        $this->listeners[] = $events->attach(
            ResetPasswordEvent::EVENT_RESET_PASSWORD_RESET,
            [$this, 'resetPassword'],
            499
        );
    }

    /**
     * Create Reset Password
     *
     * @param ResetPasswordEvent $event
     */
    public function create(ResetPasswordEvent $event)
    {
        // @todo retrieve expired from config
        $expiration = new \DateTime();
        $expiration->add(new \DateInterval('P14D'));
        try {
            $resetPassword = new \User\Entity\ResetPassword;
            $resetPassword->setUser($event->getUserEntity());
            $resetPassword->setExpiration($expiration);
            $this->getResetPasswordMapper()->save($resetPassword);
            $event->setResetPasswordEntity($resetPassword);
            // set reset password key
            $event->setResetPasswordKey($resetPassword->getUuid());
            $this->logger->log(
                \Psr\Log\LogLevel::INFO,
                "{function} {username} {key}",
                [
                    "function" => ResetPasswordEvent::EVENT_RESET_PASSWORD_CONFIRM_EMAIL,
                    "username" => $event->getUserEntity()->getUsername(),
                    "key" => $resetPassword->getUuid()
                ]
            );
        } catch (\Exception $e) {
            $event->stopPropagation(true);
            return $e;
        }
    }

    /**
     * Set Reset Password Key
     *
     * @param ResetPasswordEvent $event
     */
    public function setResetPasswordKey(ResetPasswordEvent $event)
    {
        $resetPassword = $event->getResetPasswordEntity();
        if (! is_null($resetPassword)) {
            $event->setResetPasswordKey($resetPassword->getUuid());
        }
    }

    /**
     * Reset Password
     *
     * @param ResetPasswordEvent $event
     */
    public function resetPassword(ResetPasswordEvent $event)
    {
        $resetPasswordData = $event->getResetPasswordData();
        $resetPassword = $event->getResetPasswordEntity();
        $user = $event->getUserEntity();
        $password = $this->getUserMapper()
                         ->getPasswordHash($resetPasswordData['newPassword']);
        $user->setPassword($password);
        $resetPassword->setPassword($password);
        $resetPassword->setReseted(new \DateTime());
        $this->getUserMapper()->save($user);
        $this->getresetPasswordMapper()->save($resetPassword);
        $event->setUserEntity($user);
        $event->setResetPasswordEntity($resetPassword);
        $this->logger->log(
            \Psr\Log\LogLevel::INFO,
            "{function} {username}",
            [
                "function" => __FUNCTION__,
                "username" => $user->getUsername()
            ]
        );
    }

    /**
     * @return the $resetPasswordMapper
     */
    public function getresetPasswordMapper()
    {
        return $this->resetPasswordMapper;
    }

    /**
     * @param \User\Mapper\UserActivation $resetPasswordMapper
     */
    public function setresetPasswordMapper(ResetPasswordMapper $resetPasswordMapper)
    {
        $this->resetPasswordMapper = $resetPasswordMapper;
    }

    /**
     * @return the $userMapper
     */
    public function getUserMapper()
    {
        return $this->userMapper;
    }

    /**
     * @param \Aqilix\OAuth2\Mapper\OauthUsers $userMapper
     */
    public function setUserMapper(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
    }
}
