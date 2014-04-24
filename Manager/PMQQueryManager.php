<?php

namespace YoRenard\PimpMyQueryBundle\Manager;

use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;
use YoRenard\PimpMyQueryBundle\Entity\PMQRight;
use Doctrine\ORM\EntityManager;

/**
 * PMQQueryManager
 */
class PMQQueryManager extends AbstractManager
{

    protected $doctrineService;

    public function __construct(EntityManager $em, $entityClassName, $doctrineService)
    {
        parent::__construct($em, $entityClassName);

        $this->doctrineService = $doctrineService;
    }

    public function getQueryList($mode, $user, $filter=null, $orderBy=PMQQuery::ORDER_BY_FAVORITE, $direction='ASC')
    {
        $qb = $this->em->createQueryBuilder()
            ->from('YoRenardPimpMyQueryBundle:PMQQuery', 'q')
            ->select('q')
            ->where('1=1');

        if ($mode==PMQQuery::USER_MODE_USER) {
            $level = PMQRight::USER_TYPE_USER;
//            foreach ($user->getRoleList() as $role) {
//                if ($role->getIdRole() == 2) {
//                    $level = PMQRight::USER_TYPE_MANAGER;
//                }
//            }

            $qb
//                ->addSelect('fq')
//               ->leftJoin('q.favoriteQueries', 'fq', \Doctrine\ORM\Query\Expr\Join::WITH, 'fq.lfUser = :user')
//               ->setParameter('user', $user)
               ->andWhere('q.public = 1')
//               ->innerJoin('q.rights', 'r', \Doctrine\ORM\Query\Expr\Join::WITH, '(r.service = :userService OR r.service is NULL) AND (r.level = :userLevel1 OR r.level = :userLevel2)')
//               ->setParameter('userService', $user->getIdService())
//               ->setParameter('userLevel1', $level)
//               ->setParameter('userLevel2', PMQRight::USER_TYPE_USER)
            ;
        }

        if($filter) {
            $qb->andWhere($qb->expr()->like('q.name', $qb->expr()->literal('%'.$filter.'%')).' OR '.$qb->expr()->like('q.desc', $qb->expr()->literal('%'.$filter.'%')));
        }

        if($orderBy) {
            if($orderBy==PMQQuery::ORDER_BY_FAVORITE && $mode==PMQQuery::USER_MODE_USER) {
//                $qb->orderBy('fq.query', 'DESC');
            }
            elseif($orderBy!=PMQQuery::ORDER_BY_FAVORITE) {
                $qb->orderBy('q.'.$orderBy.' '.$direction);
            }
            $qb->addOrderBy('q.idQuery', 'ASC');
        }

        return $qb;
    }

    public function addCountTagToQuery($query)
    {
        $select_pos	= strpos($query, 'select') + 7;
        return substr($query, 0, $select_pos) . ' SQL_CALC_FOUND_ROWS ' . substr($query, $select_pos, strlen($query));
    }

    public function execute($query, $paramGenerated, $connectionName, $offset=0, $limit=0)
    {
        $q = clone $query;
        $this->detach($q);

        $q = $this->bindArrayParam($q, $paramGenerated);
        $paramGenerated = $this->dropArrayParam($paramGenerated);

        $q = $this->addCountTagToQuery($q->getQuery());

        $limitQ = ' LIMIT '.$offset.', '.$limit;

        if($limit>0) {
            // Remove ";" on query string
            $pos = strrpos($q, ';');
            if($pos!==false) {
                $q = substr($q, 0, $pos);
            }

            $q .= $limitQ.';';
        }

        try {
            $conn = $this->doctrineService->getConnection($connectionName);
            $stmt = $conn->prepare($q);
            $stmt->execute($paramGenerated);
        } catch (\PDOException $e) {
            return $e;
        }

        return $stmt;
    }


    public function bindArrayParam(PMQQuery $query, array $params=array())
    {
        foreach ($params as $key => $param) {
            if (is_array($param)) {
                $query->setQuery(str_replace($key, "'".implode("', '", $param)."'", $query->getQuery()));
            }
        }

        return $query;
    }

    public function dropArrayParam(array $params=array())
    {
        foreach ($params as $key => $param) {
            if (is_array($param)) {
                unset($params[$key]);
            }
        }

        return $params;
    }

    public function getCount($connectionName)
    {
        $conn = $this->doctrineService->getConnection($connectionName);

        return $conn->query("SELECT FOUND_ROWS() count;")->fetch(\PDO::FETCH_OBJ)->count;
    }

}