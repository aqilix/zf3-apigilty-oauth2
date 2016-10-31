<?php
namespace User\V1\Service;

use User\V1\ResetPasswordEvent;
use Zend\EventManager\EventManagerAwareTrait;
use User\Mapper\ResetPassword as ResetPasswordMapper;
use Aqilix\OAuth2\Mapper\OauthUsers as UserMapper;
use Gedmo\Exception\RuntimeException;

class ResetPassword
{
    use EventManagerAwareTrait;

    /**
     * @var \User\V1\ResetPasswordEvent
     */
    protected $resetPasswordEvent;

    /**
     * @var \User\Mapper\ResetPassword
     */
    protected $resetPasswordMapper;

    /**
     * @var \Aqilix\OAuth2\Mapper\OauthUsers
     */
    protected $userMapper;

    public function __construct(ResetPasswordMapper $resetPasswordMapper, UserMapper $userMapper)
    {
        $this->setResetPasswordMapper($resetPasswordMapper);
        $this->setUserMapper($userMapper);
    }

    /**
     * @return the $resetPasswordMapper
     */
    public function getResetPasswordMapper()
    {
        return $this->resetPasswordMapper;
    }

    /**
     * @param \User\Mapper\ResetPassword $resetPasswordMapper
     */
    public function setResetPasswordMapper($resetPasswordMapper)
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

    /**
     * Create Reset Password
     *
     * @param  \Aqilix\OAuth2\Entity\OauthUsers $user
     * @return \User\Entity\ResetPassword
     */
    public function create(array $confirmEmailData)
    {
        $emailAddress = $confirmEmailData['emailAddress'];
        $user = $this->getUserMapper()->fetchOneBy(['username' => $emailAddress]);
        if (is_null($user)) {
            throw new \RuntimeException('Email Address not found');
        }

        $event = $this->getResetPasswordEvent();
        $event->setName(ResetPasswordEvent::EVENT_RESET_PASSWORD_CONFIRM_EMAIL);
        $event->setUserEntity($user);
        $confirmEmail = $this->getEventManager()->triggerEvent($event);
        if ($confirmEmail->stopped()) {
            $event->setException($confirmEmail->last());
            $event->setName(ResetPasswordEvent::EVENT_RESET_PASSWORD_CONFIRM_EMAIL_ERROR);
            $confirmEmail = $this->getEventManager()->triggerEvent($event);
            throw $event->getException();
        } else {
            $event->setName(ResetPasswordEvent::EVENT_RESET_PASSWORD_CONFIRM_EMAIL_SUCCESS);
            $this->getEventManager()->triggerEvent($event);
        }
    }

    /**
     * Reset Password User
     *
     * @param array $resetData
     */
    public function reset(array $resetData)
    {
        $resetPasswordKey = $resetData['resetPasswordKey'];
        try {
            $resetPasswordEntity = $this->getResetPassword($resetPasswordKey);
        } catch (\RuntimeException $e) {
            throw $e;
        }

        $event = $this->getResetPasswordEvent();
        $event->setName(ResetPasswordEvent::EVENT_RESET_PASSWORD_RESET);
        $event->setUserEntity($resetPasswordEntity->getUser());
        $event->setResetPasswordEntity($resetPasswordEntity);
        $event->setResetPasswordData($resetData);
        $resetPassword = $this->getEventManager()->triggerEvent($event);
        if ($resetPassword->stopped()) {
            $event->setException($resetPassword->last());
            $event->setName(ResetPasswordEvent::EVENT_RESET_PASSWORD_RESET_ERROR);
            $resetPassword = $this->getEventManager()->triggerEvent($event);
            throw $event->getException();
        } else {
            $event->setName(ResetPasswordEvent::EVENT_RESET_PASSWORD_RESET_SUCCESS);
            $this->getEventManager()->triggerEvent($event);
        }
    }

    /**
     * Get ResetPasswordEvent
     *
     * @return the $resetPasswordEvent
     */
    public function getResetPasswordEvent()
    {
        if ($this->resetPasswordEvent == null) {
            $this->resetPasswordEvent = new ResetPasswordEvent();
        }

        return $this->resetPasswordEvent;
    }

    /**
     * Get Reset Password Object
     *
     * @param  string $resetPasswordPasswordKey
     * @return ResetPassword
     * @throws RuntimeException
     */
    public function getResetPassword($resetPasswordKey)
    {
        $resetPassword = $this->getResetPasswordMapper()->fetchOneBy(['uuid' => $resetPasswordKey]);
        if (is_null($resetPassword)) {
            throw new \RuntimeException('Invalid Reset Password Key');
        }

        return $resetPassword;
    }
}
