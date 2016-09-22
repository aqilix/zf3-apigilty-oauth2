<?php
namespace User\V1\Hydrator\Strategy;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * Hydrator Strategy for Photo
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
class PhotoStrategyFactory implements FactoryInterface
{
    /**
     * Create a service for DoctrineObject Hydrator
     *
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config')['user']['photo'];
        $strategy = new PhotoStrategy($config);
        return $strategy;
    }
}
