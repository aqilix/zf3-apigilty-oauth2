<?php
namespace User\V1\Service\Listener;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ProfileEventListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userProfileMapper    = $container->get('User\Mapper\UserProfile');
        $userProfileHydrator  = $container->get('HydratorManager')->get('User\Hydrator\UserProfile');
        $profileEventConfig   = $container->get('Config')['user']['photo'];
        $profileEventListener = new ProfileEventListener($userProfileMapper, $userProfileHydrator, $profileEventConfig);
        $profileEventListener->setLogger($container->get("logger_default"));
        return $profileEventListener;
    }
}
