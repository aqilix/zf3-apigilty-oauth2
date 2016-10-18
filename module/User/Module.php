<?php
namespace User;

use ZF\Apigility\Provider\ApigilityProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

use Zend\Mvc\MvcEvent;

class Module implements
    ApigilityProviderInterface,
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ConsoleUsageProviderInterface
{
    public function onBootstrap(MvcEvent $mvcEvent)
    {
        $serviceManager = $mvcEvent->getApplication()->getServiceManager();
        // signup
        $signupService  = $serviceManager->get('user.signup');
        $signupEventListener = $serviceManager->get('user.signup.listener');
        $signupEventListener->attach($signupService->getEventManager());
        // user activation
        $userActivationService = $serviceManager->get('user.activation');
        $userActivationEventListener = $serviceManager->get('user.activation.listener');
        $userActivationEventListener->attach($userActivationService->getEventManager());
        // profile
        $profileEventListener = $serviceManager->get('user.profile.listener');
        $profileService = $serviceManager->get('user.profile');
        $profileEventListener->attach($profileService->getEventManager());
        // notification email for signup
        $signupNotificationEmailListener = $serviceManager->get('user.notification.email.signup.listener');
        $signupNotificationEmailListener->attach($signupService->getEventManager());
        // notification email for activation
        $activationNotificationEmailListener = $serviceManager->get('user.notification.email.activation.listener');
        $activationNotificationEmailListener->attach($userActivationService->getEventManager());
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

    public function getConsoleUsage(Console $console)
    {
        return [
            // available command
            'v1 user send-welcome-email <emailAddress> <activationCode>' => 'Send Welcome Email to New User',

            // describe expected parameters
            [ 'emailAddress',   'Email Address of New User'],
            [ 'activationCode', 'Activation Code for New user'],
        ];
    }
}
