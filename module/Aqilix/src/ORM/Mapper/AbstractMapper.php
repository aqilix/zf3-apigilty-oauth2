<?php

namespace Aqilix\ORM\Mapper;

use Aqilix\ORM\Entity\EntityInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrinePaginatorAdapter;

/**
 * Abstract Mapper with Doctrine support
 *
 * @author Dolly Aswin <dolly.aswin@gmail.com>
 */
abstract class AbstractMapper implements MapperInterface
{
    use EntityManagerTrait;

    /**
     * Save Entity
     *
     * @param EntityInterface $entity
     */
    public function save(EntityInterface $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    /**
     * Fetch Review by Id
     *
     * @param int $id
     */
    public function fetchOne($id)
    {
        return $this->getEntityRepository()->findOneBy(['uuid' => $id]);
    }

    /**
     * Fetch single records with params
     *
     * @param array $params
     * @return object
     */
    public function fetchOneBy($params = [])
    {
        return $this->getEntityRepository()->findOneBy($params);
    }

    /**
     * Fetch Reviews with pagination
     *
     * @param  array $params
     * @return ZendPaginator
     */
    public function fetchAll(array $params)
    {
    }

    /**
     * Get Paginator Adapter for list
     *
     * @param  unknown $query
     * @param  boolean $fetchJoinCollection
     * @return DoctrineORMModule\Paginator\Adapter\DoctrinePaginator
     */
    public function buildListPaginatorAdapter(array $params)
    {
        $query   = $this->fetchAll($params);
        $doctrinePaginator = new DoctrinePaginator($query, true);
        $adapter = new DoctrinePaginatorAdapter($doctrinePaginator);

        return $adapter;
    }

    /**
     * Delete Entity
     *
     * @param EntityInterface $entity
     */
    public function delete(EntityInterface $entity)
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Get Entity Repository
     */
    public function getEntityRepository()
    {
    }
}
