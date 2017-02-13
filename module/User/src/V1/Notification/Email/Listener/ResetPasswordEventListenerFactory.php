<?php
namespace User\V1\Notification\Email\Listener;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ResetPasswordEventListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $processBuilder = $container->get('Aqilix\Service\PhpProcessBuilder');
        $resetPasswordEventListener = new ResetPasswordEventListener($processBuilder);
        $resetPasswordEventListener->setLogger($container->get("logger_default"));
        return $resetPasswordEventListener;
    }
}
