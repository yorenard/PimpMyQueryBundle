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
     * @param $mode
     * @param $user
     * @param null $filter
     * @param $orderBy
     * @param null $direction
     * @param int $page
     * @return array
     */
    public function getPaginatedQueryList($mode, $user, $filter=null, $orderBy=PMQQuery::ORDER_BY_FAVORITE, $direction=null, $page=1)
    {
        $qb = $this->pmqQueryManager->getQueryList($mode, $user, $filter, $orderBy, $direction);

        return $this->customPaginator->paginate($qb, $page);
    }
}
