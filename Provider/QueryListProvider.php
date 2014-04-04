<?php

namespace YoRenard\PimpMyQueryBundle\Provider;

class QueryListProvider
{
    public function getQueryList($mode, $user, $filter=null, $orderBy=PMQQuery::ORDER_BY_FAVORITE, $direction=null, $page=1)
    {
        $qb = $this->pmqQueryManager->getQueryList($mode, $user, $filter, $orderBy, $direction);
        $count = count($qb->getQuery()->getResult());

        $results = $qb->setFirstResult((($page*PMQQuery::NB_QUERY_ON_PAGE) - PMQQuery::NB_QUERY_ON_PAGE))
            ->setMaxResults(PMQQuery::NB_QUERY_ON_PAGE)
            ->getQuery()->getResult();

        return array(
            'results' => $results,
            'count' => $count
        );

        /*
         * todo improve this with Paginator : return new Paginator($qb); doesn't work for now !!
         */
    }
}
