<?php

namespace YoRenard\PimpMyQueryBundle\Behavior;

use YoRenard\PimpMyQueryBundle\Manager\PMQQueryManager;
use YoRenard\PimpMyQueryBundle\Paginator\CustomPaginator;

class GetPaginatedQueryListBehavior
{
    /**
     * @var \YoRenard\PimpMyQueryBundle\Manager\PMQQueryManager
     */
    protected $pmqQueryManager;

    /**
     * @var \YoRenard\PimpMyQueryBundle\Paginator\CustomPaginator
     */
    protected $customPaginator;

    /**
     * Construct
     *
     * @param PMQQueryManager $pmqQueryManager
     * @param CustomPaginator $customPaginator
     */
    public function __construct(PMQQueryManager $pmqQueryManager, CustomPaginator $customPaginator)
    {
        $this->pmqQueryManager = $pmqQueryManager;
        $this->customPaginator = $customPaginator;
    }

    /**
     * get paginated Query list
     *
     * @param null $filter
     * @param $orderBy
     * @param null $direction
     * @param int $page
     * @return CustomPaginator
     */
    public function getPaginatedQueryList($filter=null, $orderBy=PMQQuery::ORDER_BY_FAVORITE, $direction=null, $page=1)
    {
        $qb = $this->pmqQueryManager->getQueryList($filter, $orderBy, $direction);

        return $this->customPaginator->paginate($qb, $page);
    }
}
