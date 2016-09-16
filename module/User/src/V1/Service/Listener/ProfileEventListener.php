<?php
namespace User\V1\Service\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use User\Mapper\UserProfile as UserProfileMapper;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use User\V1\ProfileEvent;

class ProfileEventListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $config;

    protected $userProfileMapper;

    protected $userProfileHydrator;

    /**
     * Constructor
     *
     * @param UserProfileMapper   $userProfileMapper
     * @param UserProfileHydrator $userProfileHydrator
     * @param array $config
     */
    public function __construct(
        UserProfileMapper $userProfileMapper,
        DoctrineObject $userProfileHydrator,
        array $config = []
    ) {
        $this->setUserProfileMapper($userProfileMapper);
        $this->setUserProfileHydrator($userProfileHydrator);
        $this->config = $config;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            ProfileEvent::EVENT_UPDATE_PROFILE,
            [$this, 'updateProfile'],
            499
        );
    }

    /**
     * Update Profile
     *
     * @param  $event
     */
    public function updateProfile($event)
    {
        try {
            $updateData  = get_object_vars($event->getParams()->getUpdateData());
            $userProfileEntity = $event->getParams()->getUserProfileEntity();
            $userProfile = $this->getUserProfileHydrator()->hydrate($updateData, $userProfileEntity);
            $this->getUserProfileMapper()->save($userProfile);
            $event->getParams()->setUserProfileEntity($userProfile);
        } catch (\Exception $e) {
            $event->stopPropagation(true);
            return $e;
        }
    }

    /**
     * @return the $config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
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
     * @return the $userProfileHydrator
     */
    public function getUserProfileHydrator()
    {
        return $this->userProfileHydrator;
    }

    /**
     * @param DoctrineObject $userProfileHydrator
     */
    public function setUserProfileHydrator($userProfileHydrator)
    {
        $this->userProfileHydrator = $userProfileHydrator;
    }
}
