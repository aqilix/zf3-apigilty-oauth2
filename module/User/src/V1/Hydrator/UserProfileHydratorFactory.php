<?php
namespace User\V1\Hydrator;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Hydrator\Strategy\DateTimeFormatterStrategy;
use Zend\Hydrator\Filter\FilterComposite;

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
        $hydrator->addStrategy('photo', $container->get('user.hydrator.photo.strategy'));
        $hydrator->addFilter('exclude', function ($property) {
            if (in_array($property, ['deletedAt', 'userActivation'])) {
                return false;
            }

            return true;
        }, FilterComposite::CONDITION_AND);
        return $hydrator;
    }
}
