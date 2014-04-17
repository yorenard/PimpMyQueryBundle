<?php

namespace YoRenard\PimpMyQueryBundle\Tests\Provider;

use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;
use YoRenard\PimpMyQueryBundle\Provider\PMQQueryProvider;

class PMQQueryProviderTest extends \PHPUnit_Framework_TestCase
{
    protected $pmqQueryManagerMock;
    protected $pmqQueryProvider;

    public function setup()
    {
        parent::setUp();

        $this->pmqQueryManagerMock = $this->getMock('\YoRenard\PimpMyQueryBundle\Manager\PMQQueryManager', array('load'), array(), '', null);
        $this->pmqQueryProvider = new PMQQueryProvider($this->pmqQueryManagerMock);
    }

    public function providerLoad()
    {
        return array(
            array(new PMQQuery(), new PMQQuery()),
            array(null, null),
        );
    }

    /**
     * @dataProvider providerLoad
     */
    public function testLoad($pmqQuery, $expectedResult)
    {
        $this->pmqQueryManagerMock->expects($this->any())->method('load')->will($this->returnValue($pmqQuery));

        if (null === $expectedResult) {
            $this->setExpectedException('\YoRenard\PimpMyQueryBundle\Exception\PMQQueryNotFoundException');
        }

        $this->assertEquals($pmqQuery, $expectedResult);
        $this->pmqQueryProvider->load(1);
    }
}
