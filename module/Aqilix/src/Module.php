<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014-2016 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace Aqilix;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $mvcEvent)
    {
        $serviceManager = $mvcEvent->getApplication()->getServiceManager();
        $eventManager   = $mvcEvent->getApplication()->getEventManager();
        $em = $serviceManager->get('doctrine.entitymanager.orm_default');
        // enable soft-deletable
        $em->getFilters()
           ->enable('soft-deleteable');
    }

    public function getConfig()
    {
        $config = [];
        $configFiles = [
            __DIR__ . '/../config/module.config.php',
            __DIR__ . '/../config/doctrine.oauth2.config.php',  // configuration for doctrine oauth2
        ];

        // merge all module config options
        foreach ($configFiles as $configFile) {
            $config = \Zend\Stdlib\ArrayUtils::merge($config, include $configFile, true);
        }

        return $config;
    }
}
