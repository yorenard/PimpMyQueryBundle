<?php

namespace YoRenard\PimpMyQueryBundle\Behavior;

use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;

class UpdateQueryBehaviorTest extends \PHPUnit_Framework_TestCase
{
    protected $pmqQueryManagerMock;
    protected $updateQueryBehavior;

    public function setUp()
    {
        $this->pmqQueryManagerMock = $this->getMock('YoRenard\PimpMyQueryBundle\Manager\PMQQueryManager', array('save'), array(), '', null);

        $this->updateQueryBehavior = new UpdateQueryBehavior($this->pmqQueryManagerMock);
    }

    public function testUpdate()
    {
        $pmqQuery = new PMQQuery();

        $this->pmqQueryManagerMock->expects($this->once())->method('save')->with($pmqQuery);

        $this->updateQueryBehavior->update($pmqQuery);
    }

    public function update(PMQQuery $pmqQuery)
    {
//        $this->get('yorenard_pimp_my_query.behavior.update_query')->update($pmqQuery);

    }
}
