<?php

namespace YoRenard\PimpMyQueryBundle\Tests\Paginator;

use Doctrine\ORM\Query;
use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;
use YoRenard\PimpMyQueryBundle\Paginator\CustomPaginator;

class CustomPaginatorTest extends \PHPUnit_Framework_TestCase
{
    protected $customPaginator;

    public function setUp()
    {
        parent::setUp();

        $this->customPaginator = new CustomPaginator();
    }

    public function testPaginate()
    {
        $queryBuilder = $this->getMock('Doctrine\DBAL\Query\QueryBuilder', array('setFirstResult', 'setMaxResults'), array(), '', null);

        $customPaginator = $this->getMock('YoRenard\PimpMyQueryBundle\Paginator\CustomPaginator', array('getCount', 'getResult', 'getOffsetFromPage'), array(), '', null);

        $customPaginator->expects($this->once())->method('getCount')->with($queryBuilder)->will($this->returnValue(15));

        $offset = 0;
        $customPaginator->expects($this->once())->method('getOffsetFromPage')->with(1)->will($this->returnValue($offset));

        $queryBuilder->expects($this->once())->method('setFirstResult')->with($offset)->will($this->returnValue($queryBuilder));
        $queryBuilder->expects($this->once())->method('setMaxResults')->with(PMQQuery::NB_QUERY_ON_PAGE)->will($this->returnValue($queryBuilder));

        $customPaginator->expects($this->once())->method('getResult')->with($queryBuilder)->will($this->returnValue(array('a', 'b')));

        $this->assertEquals(array('results' => array('a', 'b'), 'count' => 15), $customPaginator->paginate($queryBuilder, 1));
    }

    public function testGetCount()
    {
        $queryBuilder = $this->getMock('Doctrine\DBAL\Query\QueryBuilder', array('getQuery'), array(), '', null);

        $customPaginator = $this->getMock('YoRenard\PimpMyQueryBundle\Paginator\CustomPaginator', array('getResult'), array(), '', null);
        $customPaginator->expects($this->once())->method('getResult')->with($queryBuilder)->will($this->returnValue(array('a', 'b')));

        $this->assertEquals(2, $customPaginator->getCount($queryBuilder));
    }

    public function testGetResult()
    {
        $queryBuilder = $this->getMock('Doctrine\DBAL\Query\QueryBuilder', array('getQuery'), array(), '', null);

        $query = $this->getMock('Doctrine\ORM\AbstractQuery', array('getResult', 'getSQL', '_doExecute'), array(), '', null);
        $queryBuilder->expects($this->once())->method('getQuery')->will($this->returnValue($query));

        $results = array('results');
        $query->expects($this->once())->method('getResult')->will($this->returnValue($results));

        $this->assertEquals($results, $this->customPaginator->getResult($queryBuilder));
    }

    public function getOffsetFromPageDataProvider()
    {
        return array(
            array(0, 0),
            array(1, 0),
            array(2, 25),
            array(3, 50),
            array(4, 75),
            array(5, 100),
        );
    }

    /**
     * @dataProvider getOffsetFromPageDataProvider
     */
    public function testGetOffsetFromPage($page, $expectedResult)
    {
        $this->assertEquals($expectedResult, $this->customPaginator->getOffsetFromPage($page));
    }
}
