<?php

namespace YoRenard\PimpMyQueryBundle\Manager;

use Doctrine\ORM\EntityManager;
use YoRenard\PimpMyQueryBundle\Manager\ManagerInterface;

/**
 * Abstract class wich describe a manager class
 */

abstract class AbstractManager implements ManagerInterface
{
    protected $em;
    protected $repository;
    protected $class;

    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $this->class = $class;
    }

    public function getEm()
    {
        return $this->em;
    }

    /**
     * {@inheritDoc}
     */
    public function createObject()
    {
        $class = $this->getClass();

        return new $class();
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritDoc}
     */
    public function getReference($id)
    {
        return $this->em->getReference($this->class, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function load($id)
    {
        return $this->repository->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function loadOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function loadBy(array $criteria, array $order = null)
    {
        return $this->repository->findBy($criteria, $order);
    }

    /**
     * {@inheritDoc}
     */
    public function loadAll()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function persist($entity)
    {
        $this->em->persist($entity);
    }

    public function detach($entity)
    {
        $this->em->detach($entity);
    }

    public function clear()
    {
        $this->em->clear();
    }
}
