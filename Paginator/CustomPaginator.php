<?php

namespace YoRenard\PimpMyQueryBundle\Paginator;

use Doctrine\DBAL\Query\QueryBuilder;
use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;

class CustomPaginator
{
    /**
     * todo improve this with Doctrine Paginator : return new Paginator($qb); doesn't work for now !!
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return array
     */
    public function paginate(QueryBuilder $queryBuilder, $page=1)
    {
        $count = $this->getCount($queryBuilder);

        $queryBuilder = $queryBuilder
            ->setFirstResult($this->getOffsetFromPage($page))
            ->setMaxResults(PMQQuery::NB_QUERY_ON_PAGE);

        $results = $this->getResult($queryBuilder);

        return array(
            'results' => $results,
            'count'   => $count
        );
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return int
     */
    public function getCount(QueryBuilder $queryBuilder)
    {
        return count($this->getResult($queryBuilder));
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return mixed
     */
    public function getResult(QueryBuilder $queryBuilder)
    {
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param int $page
     * @return int
     */
    public function getOffsetFromPage($page=1)
    {
        if ($page < 1) {
            return 0;
        }

        return ($page * PMQQuery::NB_QUERY_ON_PAGE) - PMQQuery::NB_QUERY_ON_PAGE;
    }
}
