<?php
namespace User;

use ZF\Apigility\Provider\ApigilityProviderInterface;

use Zend\Mvc\MvcEvent;

class Module implements ApigilityProviderInterface
{
    public function onBootstrap(MvcEvent $mvcEvent)
    {
        $serviceManager = $mvcEvent->getApplication()->getServiceManager();
        $signupEventListener = $serviceManager->get('user.signup.listener');
        $signupService  = $serviceManager->get('user.signup');
//         var_dump($signupService->getEventManager() instanceof \Zend\EventManager\EventManagerInterface);
        $signupEventListener->attach($signupService->getEventManager());
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'ZF\Apigility\Autoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src',
                ],
            ],
        ];
    }
}
