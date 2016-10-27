<?php
namespace User\V1\Service;

use User\V1\ResetPasswordEvent;
use Zend\EventManager\EventManagerAwareTrait;
use User\Mapper\ResetPassword as ResetPasswordMapper;

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

    public function __construct(ResetPasswordMapper $resetPasswordMapper)
    {
        $this->setResetPasswordMapper($resetPasswordMapper);
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
     * Create Reset Password
     *
     * @param  \Aqilix\OAuth2\Entity\OauthUsers $user
     * @return \User\Entity\ResetPassword
     */
    public function create($user)
    {
        $mapper = $this->getResetPasswordMapper();
        // 14 days expiration
        $expiration = new \DateTime();
        $expiration->add(new \DateInterval('P14D'));
        $this->getResetPasswordEvent()->setName(ResetPasswordEvent::EVENT_RESET_PASSWORD_CONFIRM_EMAIL);
        $this->getEventManager()->triggerEvent($this->getResetPasswordEvent());
        try {
            $resetPassword = new \User\Entity\ResetPassword;
            $resetPassword->setUser($user);
            $resetPassword->setExpiration($expiration);
            $this->getResetPasswordMapper()->save($resetPassword);
            $this->getResetPasswordEvent()->setName(ResetPasswordEvent::EVENT_RESET_PASSWORD_CONFIRM_EMAIL_SUCCESS);
            $this->getResetPasswordEvent()->setResetPasswordEntity($resetPassword);
            $this->getEventManager()->triggerEvent($this->getResetPasswordEvent());
        } catch (\Exception $e) {
            $this->getResetPasswordEvent()->setName(ResetPasswordEvent::EVENT_RESET_PASSWORD_CONFIRM_EMAIL_ERROR);
            $this->getResetPasswordEvent()->setUserEntity($user);
            $this->getEventManager()->triggerEvent($this->getResetPasswordEvent());
            throw $e;
        }
    }

    /**
     * Activate user
     *
     * @param array $activationData
     */
    public function reset(array $activationData)
    {
        $this->getResetPasswordEvent()->setResetPasswordData($activationData);
        // retrieve user activation
        $activation  = $this->getResetPasswordMapper()->fetchOne($activationData['activationUuid']);
        // check if activation data exist
        if (is_null($activation)) {
            throw new \RuntimeException('Activation UUID not valid');
        }

        // retrieve user
        $user = $activation->getUser();
        // retrieve user profile
        $userProfile = $this->getUserProfileMapper()->fetchOneBy(['user' => $user->getUsername()]);
        $this->getResetPasswordEvent()->setUserProfileEntity($userProfile);
        $this->getResetPasswordEvent()->setResetPasswordEntity($activation);

        $activate = $this->getEventManager()->trigger(
            ResetPasswordEvent::EVENT_ACTIVATE_USER,
            $this,
            $this->getResetPasswordEvent()
        );
        if ($activate->stopped()) {
            $this->getResetPasswordEvent()->setException($activate->last());
            $activate = $this->getEventManager()->trigger(
                ResetPasswordEvent::EVENT_ACTIVATE_USER_ERROR,
                $this,
                $this->getResetPasswordEvent()
            );
            throw $this->getResetPasswordEvent()->getException();
        } else {
            $this->getEventManager()->trigger(
                ResetPasswordEvent::EVENT_ACTIVATE_USER_SUCCESS,
                $this,
                $this->getResetPasswordEvent()
            );
        }
    }

    /**
     * @return the $resetPasswordEvent
     */
    public function getResetPasswordEvent()
    {
        if ($this->resetPasswordEvent == null) {
            $this->resetPasswordEvent = new ResetPasswordEvent();
        }

        return $this->resetPasswordEvent;
    }
}
