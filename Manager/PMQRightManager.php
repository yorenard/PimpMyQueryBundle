<?php

namespace YoRenard\PimpMyQueryBundle\Manager;

use Doctrine\ORM\EntityManager;

/**
 * PMQRightManager
 */
class PMQRightManager extends AbstractManager
{

    public function __construct(EntityManager $em, $entityClassName)
    {
        parent::__construct($em, $entityClassName);
    }

}