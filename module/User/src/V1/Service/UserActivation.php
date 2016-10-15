<?php
namespace User\V1\Service;

use User\V1\UserActivationEvent;
use Zend\EventManager\EventManagerAwareTrait;
use User\Mapper\UserActivation as UserActivationMapper;
use User\Mapper\UserProfile as UserProfileMapper;
use User\Entity\UserProfile;

class UserActivation
{
    use EventManagerAwareTrait;

    /**
     * @var \User\V1\UserActivationEvent
     */
    protected $userActivationEvent;

    /**
     * @var \User\Mapper\UserActivation
     */
    protected $userActivationMapper;

    /**
     * @var \User\Mapper\UserProfile
     */
    protected $userProfileMapper;

    public function __construct(UserActivationMapper $userActivationMapper, UserProfileMapper $userProfileMapper)
    {
        $this->setUserActivationMapper($userActivationMapper);
        $this->setUserProfileMapper($userProfileMapper);
    }

    /**
     * @return the $userActivationMapper
     */
    public function getUserActivationMapper()
    {
        return $this->userActivationMapper;
    }

    /**
     * @param \User\Mapper\UserActivation $userActivationMapper
     */
    public function setUserActivationMapper($userActivationMapper)
    {
        $this->userActivationMapper = $userActivationMapper;
    }

    /**
     * @return the $userProfileMapper
     */
    public function getUserProfileMapper()
    {
        return $this->userProfileMapper;
    }

    /**
     * @param \User\Mapper\UserProfile $userProfileMapper
     */
    public function setUserProfileMapper($userProfileMapper)
    {
        $this->userProfileMapper = $userProfileMapper;
    }

    /**
     * Activate user
     *
     * @param array $activationData
     */
    public function activate(array $activationData)
    {
        $this->getUserActivationEvent()->setUserActivationData($activationData);
        // retrieve user activation
        $activation  = $this->getUserActivationMapper()->fetchOne($activationData['activationUuid']);
        // retrieve user
        $user = $activation->getUser();
        // retrieve user profile
        $userProfile = $this->getUserProfileMapper()->fetchOneBy(['user' => $user->getUsername()]);
        $this->getUserActivationEvent()->setUserProfileEntity($userProfile);
        $this->getUserActivationEvent()->setUserActivationEntity($activation);

        $activate = $this->getEventManager()->trigger(
            UserActivationEvent::EVENT_ACTIVATE_USER,
            $this,
            $this->getUserActivationEvent()
        );
        if ($activate->stopped()) {
            $this->getUserActivationEvent()->setException($activate->last());
            $activate = $this->getEventManager()->trigger(
                UserActivationEvent::EVENT_ACTIVATE_USER_ERROR,
                $this,
                $this->getUserActivationEvent()
            );
            throw $this->getUserActivationEvent()->getException();
        } else {
            $this->getEventManager()->trigger(
                UserActivationEvent::EVENT_ACTIVATE_USER_SUCCESS,
                $this,
                $this->getUserActivationEvent()
            );
        }
    }

    /**
     * @return the $userActivationEvent
     */
    public function getUserActivationEvent()
    {
        if ($this->userActivationEvent == null) {
            $this->userActivationEvent = new UserActivationEvent();
        }

        return $this->userActivationEvent;
    }
}
