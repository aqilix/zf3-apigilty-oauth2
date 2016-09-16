<?php
namespace User\V1\Service\Listener;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ProfileEventListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userProfileMapper   = $container->get('User\Mapper\UserProfile');
        $userProfileHydrator = $container->get('HydratorManager')->get('User\Hydrator\UserProfile');
        $config = $container->get('Config');
        $profileEventConfig = [];
        return new ProfileEventListener(
            $userProfileMapper,
            $userProfileHydrator,
            $profileEventConfig
        );
    }
}
