<?php
namespace User\V1\Notification\Email\Listener;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class SignupEventListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $processBuilder = $container->get('Aqilix\Service\PhpProcessBuilder');
        $signupEventListener = new SignupEventListener($processBuilder);
        $signupEventListener->setLogger($container->get("logger_default"));
        return $signupEventListener;
    }
}
