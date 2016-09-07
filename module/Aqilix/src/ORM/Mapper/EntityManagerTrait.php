<?php
/**
 * Aqilix Doctrine ORM Module
 *
 * @copyright Copyright (c) 2014-2015
 */

namespace Aqilix\ORM\Mapper;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Trait for EntityManager
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
trait EntityManagerTrait
{
    /**
     * EntityManager Object
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * Set EntityManager
     *
     * @param  EntityManagerInterface $entityManager
     *
     * @return $this
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
        return $this;
    }

    /**
     * Get EntityManager
     *
     * @return EntityManagerInterface
     **/
    public function getEntityManager()
    {
        return $this->em;
    }
}
