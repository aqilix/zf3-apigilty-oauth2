<?php
namespace Aqilix\Service;

use Interop\Container\ContainerInterface;
use Zend\Log\PsrLoggerAdapter;
use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Aqilix\Log\Processor\PsrPlaceholder;

/**
 * Psr Logger Delegator
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
class PsrLoggerDelegator implements DelegatorFactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param                    $requestedName
     * @param callable           $callback
     * @param array|null         $options
     * @return PsrLoggerAdapter
     */
    public function __invoke(ContainerInterface $container, $requestedName, callable $callback, array $options = null)
    {
        $zendLogLogger = $callback();
        $zendLogLogger->addProcessor(new PsrPlaceholder());
        $psrLogger = new PsrLoggerAdapter($zendLogLogger);
        return $psrLogger;
    }

    /**
     * SM2 Compatibility
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string                  $name
     * @param string                  $requestedName
     * @param callable                $callback
     * @return mixed
     */
    public function createDelegatorWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback)
    {
        $zendLogLogger = $callback();
        $zendLogLogger->addProcessor(new PsrPlaceholder());
        $psrLogger = new PsrLoggerAdapter($zendLogLogger);
        return $psrLogger;
    }
}
