<?php
namespace User\Service\Listener;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class UnauthorizedUserListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $unauthorizedUserListener = new UnauthorizedUserListener();
        return $unauthorizedUserListener;
    }
}
