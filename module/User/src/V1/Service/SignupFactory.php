<?php
namespace User\V1\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class SignupFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userMapper = $container->get('Aqilix\OAuth2\Mapper\OauthUsers');
        $oauth2Server = $container->get('ZF\OAuth2\Service\OAuth2Server');
        return new Signup($userMapper);
    }
}
