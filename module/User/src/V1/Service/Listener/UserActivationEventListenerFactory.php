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
        $userActivationEventListener = new UserActivationEventListener(
            $userProfileMapper,
            $userActivationProfileMapper
        );
        $userActivationEventListener->setLogger($container->get("logger_default"));
        return $userActivationEventListener;
    }
}
