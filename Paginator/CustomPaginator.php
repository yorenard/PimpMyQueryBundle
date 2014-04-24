<?php

namespace YoRenard\PimpMyQueryBundle\Paginator;

use Doctrine\DBAL\Query\QueryBuilder;
use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;

class CustomPaginator
{
    /**
     * @var array $results
     */
    protected $queryList;

    /**
     * @var int $lastIdPage
     */
    protected $lastIdPage;

    /**
     * @var int $previousIdPage
     */
    protected $previousIdPage;

    /**
     * @var int $nextIdPage
     */
    protected $nextIdPage;

    /**
     * @var int $count
     */
    protected $count;

    /**
     * @param int $lastIdPage
     */
    public function setLastIdPage($lastIdPage)
    {
        $this->lastIdPage = $lastIdPage;
    }

    /**
     * @return int
     */
    public function getLastIdPage()
    {
        return $this->lastIdPage;
    }

    /**
     * @param int $nextIdPage
     */
    public function setNextIdPage($nextIdPage)
    {
        $this->nextIdPage = $nextIdPage;
    }

    /**
     * @return int
     */
    public function getNextIdPage()
    {
        return $this->nextIdPage;
    }

    /**
     * @param int $previousIdPage
     */
    public function setPreviousIdPage($previousIdPage)
    {
        $this->previousIdPage = $previousIdPage;
    }

    /**
     * @return int
     */
    public function getPreviousIdPage()
    {
        return $this->previousIdPage;
    }

    /**
     * @param array $queryList
     */
    public function setQuerylist($querylist)
    {
        $this->querylist = $querylist;
    }

    /**
     * @return array
     */
    public function getQuerylist()
    {
        return $this->querylist;
    }

    /**
     * todo improve this with Doctrine Paginator : return new Paginator($qb); doesn't work for now !!
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return array
     */
    public function paginate(/*QueryBuilder */$queryBuilder, $page=1)
    {
        $this->count = $this->getCount($queryBuilder);

        $queryBuilder = $queryBuilder
            ->setFirstResult($this->getOffsetFromPage($page))
            ->setMaxResults(PMQQuery::NB_QUERY_ON_PAGE)
        ;



        $this->querylist      = $this->getResult($queryBuilder);
        $this->lastIdPage     = ceil($this->count / PMQQuery::NB_QUERY_ON_PAGE);
        $this->previousIdPage = $page>1? $page-1:1;
        $this->nextIdPage     = $page<$this->lastIdPage? $page+1:$this->lastIdPage;




        return $this;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return int
     */
    public function getCount(/*QueryBuilder */$queryBuilder)
    {
        return count($this->getResult($queryBuilder));
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return mixed
     */
    public function getResult(/*QueryBuilder */$queryBuilder)
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
