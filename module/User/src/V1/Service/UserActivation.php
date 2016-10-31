<?php
namespace User\V1\Service;

use User\V1\UserActivationEvent;
use Zend\EventManager\EventManagerAwareTrait;
use User\Mapper\UserActivation as UserActivationMapper;
use User\Mapper\UserProfile as UserProfileMapper;

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

    /**
     * Construct
     *
     * @param \User\Mapper\UserActivation $userActivationMapper
     * @param \User\Mapper\UserProfile    $userProfileMapper
     */
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
    public function setUserActivationMapper(UserActivationMapper $userActivationMapper)
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
    public function setUserProfileMapper(UserProfileMapper $userProfileMapper)
    {
        $this->userProfileMapper = $userProfileMapper;
    }

    /**
     * Activate user
     *
     * @param  array $activationData
     * @throw  \RuntimeException
     * @return void
     */
    public function activate(array $activationData)
    {
        $userActivationEvent = $this->getUserActivationEvent();
        $userActivationEvent->setUserActivationData($activationData);
        // retrieve user activation
        $activation  = $this->getUserActivationMapper()->fetchOne($activationData['activationUuid']);
        // check if activation data exist
        if (is_null($activation)) {
            throw new \RuntimeException('Activation UUID not valid');
        }

        // retrieve user
        $user = $activation->getUser();
        // retrieve user profile
        $userProfile = $this->getUserProfileMapper()->fetchOneBy(['user' => $user->getUsername()]);
        $userActivationEvent->setUserProfileEntity($userProfile);
        $userActivationEvent->setUserActivationEntity($activation);
        $userActivationEvent->setName(UserActivationEvent::EVENT_ACTIVATE_USER);
        $activate = $this->getEventManager()->triggerEvent($userActivationEvent);
        if ($activate->stopped()) {
            $userActivationEvent->setException($activate->last());
            $userActivationEvent->setName(UserActivationEvent::EVENT_ACTIVATE_USER_ERROR);
            $this->getEventManager()->triggerEvent($userActivationEvent);
            throw $this->getUserActivationEvent()->getException();
        } else {
            $userActivationEvent->setName(UserActivationEvent::EVENT_ACTIVATE_USER_SUCCESS);
            $this->getEventManager()->triggerEvent($userActivationEvent);
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
