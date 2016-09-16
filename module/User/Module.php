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
        $signupEventListener->attach($signupService->getEventManager());
        $profileEventListener = $serviceManager->get('user.profile.listener');
        $profileService = $serviceManager->get('user.profile');
        $profileEventListener->attach($profileService->getEventManager());
    }

    public function getConfig()
    {
        $config = [];
        $configFiles = [
            __DIR__ . '/config/module.config.php',
            __DIR__ . '/config/doctrine.config.php',  // configuration for doctrine
        ];

        // merge all module config options
        foreach ($configFiles as $configFile) {
            $config = \Zend\Stdlib\ArrayUtils::merge($config, include $configFile, true);
        }

        return $config;
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
