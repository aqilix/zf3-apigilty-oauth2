<?php
namespace User\V1\Notification\Email\Listener;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class SignupEventListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $mailTransport = $container->get('Aqilix\Service\Mail');
        $viewRenderer  = $container->get('ViewRenderer');
        $config = $container->get('Config')['project']['sites'];
        $signupEventListener = new SignupEventListener($viewRenderer, $mailTransport, $config);
        $signupEventListener->setWelcomeMailMessage($container->get('user.notification.email.service.welcome'));
        return $signupEventListener;
    }
}
