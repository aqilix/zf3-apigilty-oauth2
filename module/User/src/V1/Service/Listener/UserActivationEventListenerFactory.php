<?php
namespace User\V1\Service\Listener;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class UserActivationEventListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userProfileMapper = $container->get('User\Mapper\UserProfile');
        $userActivationProfileMapper = $container->get('User\Mapper\UserActivation');
        return new UserActivationEventListener($userProfileMapper, $userActivationProfileMapper);
    }
}
