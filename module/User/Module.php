<?php
namespace User;

use ZF\Apigility\Provider\ApigilityProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Mvc\MvcEvent;
use ZF\MvcAuth\MvcAuthEvent;

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
        // reset password
        $resetPasswordService = $serviceManager->get(\User\V1\Service\ResetPassword::class);
        $resetPasswordEventListener = $serviceManager->get(
            \User\V1\Service\Listener\ResetPasswordEventListener::class
        );
        $resetPasswordEventListener->attach($resetPasswordService->getEventManager());
        // AuthActiveUserListener
        $app    = $mvcEvent->getApplication();
        $events = $app->getEventManager();
        $mvcAuthEvent = new MvcAuthEvent(
            $mvcEvent,
            $serviceManager->get('authentication'),
            $serviceManager->get('authorization')
        );
        $pdoAdapter = $serviceManager->get(\User\OAuth2\Adapter\PdoAdapter::class);
        $pdoAdapter->setEventManager($events);
        $pdoAdapter->setMvcAuthEvent($mvcAuthEvent);
        $events->attach(
            MvcAuthEvent::EVENT_AUTHENTICATION_POST,
            $serviceManager->get(\User\Service\Listener\AuthActiveUserListener::class)
        );
        // notification email for signup
        $signupNotificationEmailListener = $serviceManager->get('user.notification.email.signup.listener');
        $signupNotificationEmailListener->attach($signupService->getEventManager());
        // notification email for activation
        $activationNotificationEmailListener = $serviceManager->get('user.notification.email.activation.listener');
        $activationNotificationEmailListener->attach($userActivationService->getEventManager());
        // notification email for reset password
        $resetPasswordNotificationEmailListener = $serviceManager->get(
            \User\V1\Notification\Email\Listener\ResetPasswordEventListener::class
        );
        $resetPasswordNotificationEmailListener->attach($resetPasswordService->getEventManager());
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
