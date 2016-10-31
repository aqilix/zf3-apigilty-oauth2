<?php
namespace User\V1\Service;

use User\V1\ProfileEvent;
use Zend\EventManager\EventManagerAwareTrait;
use User\Mapper\UserProfile as UserProfileMapper;

class Profile
{
    use EventManagerAwareTrait;

    /**
     * @var \User\V1\ProfileEvent
     */
    protected $profileEvent;

    /**
     * @var \User\Mapper\UserProfile
     */
    protected $userProfileMapper;

    public function __construct(UserProfileMapper $userProfileMapper)
    {
        $this->setUserProfileMapper($userProfileMapper);
    }

    /**
     * @return \User\V1\ProfileEvent
     */
    public function getProfileEvent()
    {
        if ($this->profileEvent == null) {
            $this->profileEvent = new ProfileEvent();
        }

        return $this->profileEvent;
    }

    /**
     * @param ProfileEvent $signupEvent
     */
    public function setProfileEvent(ProfileEvent $profileEvent)
    {
        $this->profileEvent = $profileEvent;
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
     * Update User Profile
     *
     * @param \User\Entity\UserProfile  $userProfile
     * @param array                     $updateData
     */
    public function update($userProfile, $inputFilter)
    {
        $profileEvent = $this->getProfileEvent();
        $profileEvent->setUserProfileEntity($userProfile);
        $profileEvent->setUpdateData($inputFilter->getValues());
        $profileEvent->setInputFilter($inputFilter);
        $profileEvent->setName(ProfileEvent::EVENT_UPDATE_PROFILE);
        $update = $this->getEventManager()->triggerEvent($profileEvent);
        if ($update->stopped()) {
            $profileEvent->setName(ProfileEvent::EVENT_UPDATE_PROFILE_ERROR);
            $profileEvent->setException($update->last());
            $this->getEventManager()->triggerEvent($profileEvent);
            throw $profileEvent->getException();
        } else {
            $profileEvent->setName(ProfileEvent::EVENT_UPDATE_PROFILE_SUCCESS);
            $this->getEventManager()->triggerEvent($profileEvent);
        }
    }
}
