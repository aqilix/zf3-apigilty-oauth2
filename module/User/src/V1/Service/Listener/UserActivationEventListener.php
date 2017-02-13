<?php
namespace User\V1\Service\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Psr\Log\LoggerAwareTrait;
use User\V1\UserActivationEvent;
use User\Mapper\UserProfile as UserProfileMapper;
use User\Mapper\UserActivation as UserActivationMapper;

class UserActivationEventListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    use LoggerAwareTrait;

    /**
     * @var \User\Mapper\UserProfile
     */
    protected $userProfileMapper;

    /**
     * @var \User\Mapper\UserActivation
     */
    protected $userActivationMapper;

    /**
     * Construct
     *
     * @param \User\Mapper\UserProfile $userProfileMapper
     * @param \User\Mapper\UserActivation $userActivationMapper
     */
    public function __construct(UserProfileMapper $userProfileMapper, UserActivationMapper $userActivationMapper)
    {
        $this->setUserProfileMapper($userProfileMapper);
        $this->setUserActivationMapper($userActivationMapper);
    }

    /**
     * (non-PHPdoc)
     * @see \Zend\EventManager\ListenerAggregateInterface::attach()
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            UserActivationEvent::EVENT_ACTIVATE_USER,
            [$this, 'isExpired'],
            499
        );
        $this->listeners[] = $events->attach(
            UserActivationEvent::EVENT_ACTIVATE_USER,
            [$this, 'isActivated'],
            495
        );
        $this->listeners[] = $events->attach(
            UserActivationEvent::EVENT_ACTIVATE_USER,
            [$this, 'activate'],
            490
        );
    }

    /**
     * Check activated
     *
     * @param  UserActivationEvent $event
     * @return void|\Exception
     */
    public function isActivated(UserActivationEvent $event)
    {
        $userActivation = $event->getUserActivationEntity();
        if ($userActivation->getActivated() !== null) {
            $event->stopPropagation(true);
            return new \RuntimeException('Activation UUID has been activated');
        }
    }

    /**
     * Check expiration
     *
     * @param  UserActivationEvent $event
     * @return void|\Exception
     */
    public function isExpired(UserActivationEvent $event)
    {
        $now = new \DateTime();
        $userActivation = $event->getUserActivationEntity();
        if ($userActivation->getExpiration() < $now) {
            $event->stopPropagation(true);
            return new \RuntimeException('User Activation UUID has expired');
        }
    }

    /**
     * Activate New User
     *
     * @param  UserActivationEvent $event
     * @return void|\Exception
     */
    public function activate(UserActivationEvent $event)
    {
        $userActivation = $event->getUserActivationEntity();
        $userProfile = $event->getUserProfileEntity();
        try {
            $userProfile->setIsActive(true);
            $userProfile->setUserActivation($userActivation);
            $userActivation->setActivated(new \DateTime('now'));
            $this->getUserProfileMapper()->save($userProfile);
            $this->getUserActivationMapper()->save($userActivation);
            $this->logger->log(
                \Psr\Log\LogLevel::INFO,
                "{function} {username} {activationUuid}",
                [
                    "function" => __FUNCTION__,
                    "username" => $userActivation->getUser()->getUsername(),
                    "activationUuid" => $userActivation->getUuid()
                ]
            );
        } catch (\Exception $e) {
            $event->stopPropagation(true);
            return $e;
        }
    }

    /**
     * @return the $userProfileMapper
     */
    public function getUserProfileMapper()
    {
        return $this->userProfileMapper;
    }

    /**
     * Set UserProfile Mapper
     *
     * @param UserProfileMapper $userProfileMapper
     */
    public function setUserProfileMapper(UserProfileMapper $userProfileMapper)
    {
        $this->userProfileMapper = $userProfileMapper;
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
}
