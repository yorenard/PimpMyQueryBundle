<?php

namespace YoRenard\PimpMyQueryBundle\Manager;

use YoRenard\CoreBundle\Manager\Doctrine\AbstractManager;
use Doctrine\ORM\EntityManager;

/**
 * PMQRunQueueParamManager
 */
class PMQRunQueueParamManager extends AbstractManager
{

    public function __construct(EntityManager $em, $entityClassName)
    {
        parent::__construct($em, $entityClassName);
    }

    public function getParamToExecuteRunQueue($id_run_queue)
    {
        $qb = $this->em->createQueryBuilder()
            ->select('p.code, p.fieldType, qp.value')
            ->from('YoRenardPimpMyQueryBundle:PMQParam', 'p')
            ->innerJoin('p.runQueueParams', 'qp')
            ->where('qp.runQueue = :idRunQueue')
            ->setParameter('idRunQueue', $id_run_queue);

        return $qb->getQuery()
                  ->getResult();
    }

}