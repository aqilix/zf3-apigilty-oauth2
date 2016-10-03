<?php
namespace User\V1\Console\Controller;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class EmailControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mailTransport = $container->get('Aqilix\Service\Mail');
        $viewRenderer  = $container->get('ViewRenderer');
        $config = $container->get('Config')['project']['sites'];
        $emailController = new EmailController($viewRenderer, $mailTransport, $config);
        $emailController->setWelcomeMailMessage($container->get('user.notification.email.service.welcome'));
        return $emailController;
    }
}
