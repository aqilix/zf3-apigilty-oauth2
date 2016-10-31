<?php
namespace User\V1\Hydrator;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Hydrator\Strategy\DateTimeFormatterStrategy;

/**
 * Hydrator for Doctrine Entity
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
class UserProfileHydratorFactory implements FactoryInterface
{
    /**
     * Create a service for DoctrineObject Hydrator
     *
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $hydrator = new DoctrineObject($entityManager);
        $hydrator->addStrategy('user', new \User\V1\Hydrator\Strategy\UsernameStrategy);
        $hydrator->addStrategy('dateOfBirth', new DateTimeFormatterStrategy('Y-m-d'));
        $hydrator->addStrategy('createdAt', new DateTimeFormatterStrategy('c'));
        $hydrator->addStrategy('updatedAt', new DateTimeFormatterStrategy('c'));
        $hydrator->addStrategy('photo', $container->get(\User\V1\Hydrator\Strategy\PhotoStrategy::class));
        return $hydrator;
    }
}
