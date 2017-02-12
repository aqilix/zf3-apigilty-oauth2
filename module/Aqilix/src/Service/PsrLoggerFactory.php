<?php
namespace Aqilix\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Log as ZendLog;

/**
 * Psr Logger Factory
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
class PsrLoggerFactory implements FactoryInterface
{
    /**
     * Create service for PHP Process
     *
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\Factory\FactoryInterface::__invoke()
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $zendLogLogger = $container->get("logger_default");
        $zendLogLogger->addProcessor(new PsrPlaceholder());
        $psrLogger = new ZendLog\PsrLoggerAdapter($zendLogLogger);
        return $psrLogger;
    }
}
