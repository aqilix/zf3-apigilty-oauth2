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
    public function update($userProfile, $updateData)
    {
        $this->getProfileEvent()->setUserProfileEntity($userProfile);
        $this->getProfileEvent()->setUpdateData($updateData);
        $update = $this->getEventManager()->trigger(
            ProfileEvent::EVENT_UPDATE_PROFILE,
            $this,
            $this->getProfileEvent()
        );
        if ($update->stopped()) {
            $update->getProfileEvent()->setException($update->last());
            $insert = $this->getEventManager()->trigger(
                ProfileEvent::EVENT_UPDATE_PROFILE_ERROR,
                $this,
                $this->getProfileEvent()
            );
            throw $this->getProfileEvent()->getException();
        } else {
            $this->getEventManager()->trigger(
                ProfileEvent::EVENT_UPDATE_PROFILE_SUCCESS,
                $this,
                $this->getProfileEvent()
            );
        }
    }
}
