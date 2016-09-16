<?php
namespace User\V1\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ProfileFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userProfileMapper = $container->get('User\Mapper\UserProfile');
        return new Profile($userProfileMapper);
    }
}
