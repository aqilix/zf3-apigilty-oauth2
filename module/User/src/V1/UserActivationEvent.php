<?php

namespace User\V1;

use Zend\EventManager\Event;
use User\Entity\UserProfile;
use User\Entity\UserActivation;

class UserActivationEvent extends Event
{
    /**#@+
     * Signup events triggered by eventmanager
     */
    const EVENT_ACTIVATE_USER = 'activate.user';
    const EVENT_ACTIVATE_USER_SUCCESS = 'activate.user.success';
    const EVENT_ACTIVATE_USER_ERROR   = 'activate.user.error';
    /**#@-*/

    /**
     * @var \User\Entity\UserProfile
     */
    protected $userProfileEntity;

    /**
     * @var \User\Entity\UserActivation
     */
    protected $userActivationEntity;

    /**
     * @var array
     */
    protected $userActivationData;

    /**
     * @var string
     */
    protected $userActivationUuid;

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @return the $userProfileEntity
     */
    public function getUserProfileEntity()
    {
        return $this->userProfileEntity;
    }

    /**
     * @param \User\Entity\UserProfile $userProfileEntity
     */
    public function setUserProfileEntity(UserProfile $userProfileEntity)
    {
        $this->userProfileEntity = $userProfileEntity;
    }

    /**
     * @return the $userActivationEntity
     */
    public function getUserActivationEntity()
    {
        return $this->userActivationEntity;
    }

    /**
     * @param \User\Entity\UserActivation $userActivationEntity
     */
    public function setUserActivationEntity(UserActivation $userActivationEntity)
    {
        $this->userActivationEntity = $userActivationEntity;
    }

    /**
     * @return the $userActivationUuid
     */
    public function getUserActivationUuid()
    {
        return $this->userActivationUuid;
    }

    /**
     * @param string $userActivationUuid
     */
    public function setUserActivationUuid($userActivationUuid)
    {
        $this->userActivationUuid = $userActivationUuid;
    }

    /**
     * @return the $userActivationData
     */
    public function getUserActivationData()
    {
        return $this->userActivationData;
    }

    /**
     * @param array $userActivationData
     */
    public function setUserActivationData(array $userActivationData)
    {
        $this->userActivationData = $userActivationData;
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
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
    }
}
