<?php

namespace YoRenard\PimpMyQueryBundle\Manager;

use YoRenard\CoreBundle\Manager\Doctrine\AbstractManager;
use Doctrine\ORM\EntityManager;

/**
 * PMQFavoriteQueryManager
 */
class PMQFavoriteQueryManager extends AbstractManager
{

    public function __construct(EntityManager $em, $entityClassName)
    {
        parent::__construct($em, $entityClassName);
    }

}