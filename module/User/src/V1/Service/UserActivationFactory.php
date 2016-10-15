<?php
namespace User\V1\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class UserActivationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userActivationMapper = $container->get('User\Mapper\UserActivation');
        $userProfileMapper = $container->get('User\Mapper\UserProfile');
        return new UserActivation($userActivationMapper, $userProfileMapper);
    }
}
