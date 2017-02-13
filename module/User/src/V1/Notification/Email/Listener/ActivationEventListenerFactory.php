<?php
namespace User\V1\Notification\Email\Listener;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ActivationEventListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $processBuilder = $container->get('Aqilix\Service\PhpProcessBuilder');
        $activationEventListener = new ActivationEventListener($processBuilder);
        $activationEventListener->setLogger($container->get("logger_default"));
        return $activationEventListener;
    }
}
