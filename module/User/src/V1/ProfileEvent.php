<?php

namespace User\V1;

use Zend\EventManager\Event;
use Aqilix\ORM\Entity\EntityInterface;
use \Exception;

class ProfileEvent extends Event
{
    /**#@+
     * Profile events triggered by eventmanager
     */
    const EVENT_UPDATE_PROFILE  = 'update.profile';
    const EVENT_UPDATE_PROFILE_ERROR   = 'update.profile.error';
    const EVENT_UPDATE_PROFILE_SUCCESS = 'update.profile.success';
    /**#@-*/

    /**
     * @var User\Entity\UserProfile
     */
    protected $userProfileEntity;

    /**
     * @var array
     */
    protected $updateData;

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @return the $user
     */
    public function getUserProfileEntity()
    {
        return $this->userProfileEntity;
    }

    /**
     * @param Aqilix\ORM\Entity\EntityInterface $userProfile
     */
    public function setUserProfileEntity(EntityInterface $userProfile)
    {
        $this->userProfileEntity = $userProfile;
    }

    /**
     * @return the $updateData
     */
    public function getUpdateData()
    {
        return $this->updateData;
    }

    /**
     * @param object $updateData
     */
    public function setUpdateData($updateData)
    {
        $this->updateData = $updateData;
    }

    /**
     * @return the $exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param Exception $exception
     */
    public function setException(Exception $exception)
    {
        $this->exception = $exception;
    }
}
