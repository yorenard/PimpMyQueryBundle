<?php

namespace YoRenard\PimpMyQueryBundle\Manager;

use Doctrine\ORM\EntityManager;

/**
 * PMQParamManager
 */
class PMQParamManager extends AbstractManager
{

    public function __construct(EntityManager $em, $entityClassName)
    {
        parent::__construct($em, $entityClassName);
    }

    public function getQueryParamList($idQuery)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from('YoRenardPimpMyQueryBundle:PMQParam', 'p')
            ->where('p.query = :query')
            ->setParameter('query', $idQuery)
            ->getQuery()
            ->getResult();

        return $qb;
    }

}