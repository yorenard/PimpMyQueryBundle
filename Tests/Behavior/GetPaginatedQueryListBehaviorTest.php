<?php

namespace YoRenard\PimpMyQueryBundle\Tests\Behavior;

use YoRenard\PimpMyQueryBundle\Behavior\GetPaginatedQueryListBehavior;
use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;

class GetPaginatedQueryListBehaviorTest extends \PHPUnit_Framework_TestCase
{
    protected $pmqQueryManagerMock;
    protected $customPaginatorMock;
    protected $getPaginatedQueryListBehavior;

    public function setUp()
    {
        parent::setUp();

        $this->pmqQueryManagerMock = $this->getMock('YoRenard\PimpMyQueryBundle\Manager\PMQQueryManager', array('getQueryList'), array(), '', null);
        $this->customPaginatorMock = $this->getMock('YoRenard\PimpMyQueryBundle\Paginator\CustomPaginator', array('paginate'), array(), '', null);

        $this->getPaginatedQueryListBehavior = new GetPaginatedQueryListBehavior($this->pmqQueryManagerMock, $this->customPaginatorMock);
    }

    public function testGetPaginatedQueryList()
    {
        $filter = null;
        $orderBy = PMQQuery::ORDER_BY_FAVORITE;
        $direction = null;
        $page = 1;

        $qb = $this->getMock('\Doctrine\DBAL\Query\QueryBuilder', array(), array(), '', null);

        $this->pmqQueryManagerMock->expects($this->once())->method('getQueryList')->with($filter, $orderBy, $direction)->will($this->returnValue($qb));
        $this->customPaginatorMock->expects($this->once())->method('paginate')->with($qb, $page)->will($this->returnValue('page'));

        $this->assertEquals('page', $this->getPaginatedQueryListBehavior->getPaginatedQueryList($filter, $orderBy, $direction, $page));
    }
}
