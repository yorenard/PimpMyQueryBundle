<?php

namespace YoRenard\PimpMyQueryBundle\Manager;

use YoRenard\CoreBundle\Manager\Doctrine\AbstractManager;
use Doctrine\ORM\EntityManager;
use YoRenard\PimpMyQueryBundle\Entity\PMQRunQueue;

/**
 * PMQRunQueueManager
 */
class PMQRunQueueManager extends AbstractManager
{

    protected $doctrineService;

    public function __construct(EntityManager $em, $entityClassName, $doctrineService)
    {
        parent::__construct($em, $entityClassName);

        $this->doctrineService = $doctrineService;
    }

    public function getRunQueueToExecute($idRunQueue=0)
    {
        $query = '  SELECT rq.id_run_queue, rq.email, rq.id_lf_user,
                            q.id_query, q.name, q.query
                    FROM pmq_run_queue rq
                        INNER JOIN pmq_query q ON rq.id_query = q.id_query';

        if($idRunQueue>0) {
            $query .= ' WHERE rq.id_run_queue = :idRunQueue';
        }
        else {
            $query .= ' WHERE (
                                rq.run_exec = :once
                                AND rq.first_run_dt = CURDATE()
                            )
                            OR (
                                rq.run_exec = :daily
                                AND rq.first_run_dt <= CURDATE()
                                AND rq.last_run_dt >= CURDATE()
                            )
                            OR (
                                rq.run_exec = :weekly
                                AND rq.first_run_dt <= CURDATE()
                                AND rq.last_run_dt >= CURDATE()
                                AND DATE_FORMAT(rq.create_dt, "%w") = DATE_FORMAT(CURDATE(), "%w")
                            )
                            OR (
                                rq.run_exec = :monthly
                                AND rq.first_run_dt <= CURDATE()
                                AND rq.last_run_dt >= CURDATE()
                                AND DATE_FORMAT(rq.create_dt, "%e") = :lastDayOfMonth
                            );';
        }

        $stmt = $this->em->getConnection()->prepare($query);
        if($idRunQueue>0) {
            $stmt->execute(array(
                ':idRunQueue' => $idRunQueue
            ));
        } else {
            $stmt->execute(array(
                ':once' => PMQRunQueue::EXECUTE_MODE_PLAN_ONCE,
                ':daily' => PMQRunQueue::EXECUTE_MODE_PLAN_DAILY,
                ':weekly' => PMQRunQueue::EXECUTE_MODE_PLAN_WEEKLY,
                ':monthly' => PMQRunQueue::EXECUTE_MODE_PLAN_MONTHLY,
                ':lastDayOfMonth' => date("t")
            ));
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}