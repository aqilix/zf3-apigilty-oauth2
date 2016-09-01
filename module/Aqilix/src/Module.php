<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014-2016 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace Application;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $mvcEvent)
    {
        $serviceManager = $mvcEvent->getApplication()->getServiceManager();
        $eventManager   = $mvcEvent->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
