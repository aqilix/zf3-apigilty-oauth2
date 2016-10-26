<?php
namespace User\Service\Listener;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class AuthActiveUserListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userProfileMapper = $container->get('User\Mapper\UserProfile');
        $mvcAuthEventListener = new AuthActiveUserListener();
        $mvcAuthEventListener->setUserProfileMapper($userProfileMapper);
        return $mvcAuthEventListener;
    }
}
