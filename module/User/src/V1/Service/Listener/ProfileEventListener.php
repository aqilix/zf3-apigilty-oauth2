<?php
namespace User\V1\Service\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Exception\InvalidArgumentException;
use Psr\Log\LoggerAwareTrait;
use User\Mapper\UserProfile as UserProfileMapper;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use User\V1\ProfileEvent;

class ProfileEventListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    use LoggerAwareTrait;

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
        $this->setConfig($config);
    }

    /**
     * (non-PHPdoc)
     * @see \Zend\EventManager\ListenerAggregateInterface::attach()
     */
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
     * @param  SignupEvent $event
     * @return void|\Exception
     */
    public function updateProfile(ProfileEvent $event)
    {
        try {
            $userProfileEntity = $event->getUserProfileEntity();
            $updateData  = $event->getUpdateData();
            // add file input filter here
            if (! $event->getInputFilter() instanceof InputFilterInterface) {
                throw new InvalidArgumentException('Input Filter not set');
            }

            // adding filter for photo
            $inputPhoto  = $event->getInputFilter()->get('photo');
            $inputPhoto->getFilterChain()
                    ->attach(new \Zend\Filter\File\RenameUpload([
                        'target' => $this->getConfig()['backup_dir'],
                        'randomize' => true,
                        'use_upload_extension' => true
                    ]));
            $userProfile = $this->getUserProfileHydrator()->hydrate($updateData, $userProfileEntity);
            $this->getUserProfileMapper()->save($userProfile);
            $event->setUserProfileEntity($userProfile);
            $this->logger->log(
                \Psr\Log\LogLevel::INFO,
                "{function} {username}",
                [
                    "function" => __FUNCTION__,
                    "username" => $userProfileEntity->getUser()->getUsername()
                ]
            );
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
