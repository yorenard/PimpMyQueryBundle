<?php
namespace YoRenard\PimpMyQueryBundle\Tests\Builder;

use YoRenard\PimpMyQueryBundle\Entity\CustomParam;
use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;
use YoRenard\PimpMyQueryBundle\Builder\CustomParamBuilder;

class CustomParamBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $customParamCreatorMock;
    protected $customParamBuilder;

    public function setUp()
    {
        $this->customParamCreatorMock = $this->getMock('YoRenard\PimpMyQueryBundle\Creator\CustomParamCreator', array('create'), array(), '', null);

        $this->customParamBuilder = new CustomParamBuilder($this->customParamCreatorMock);
    }

    public function testBuild()
    {
        $customParam = new CustomParam();
        $pmqQuery = new PMQQuery();

        $this->customParamCreatorMock->expects($this->once())->method('create')->will($this->returnValue($customParam));
        $customParam->setPmqQuery($pmqQuery);

        $this->assertEquals($customParam, $this->customParamBuilder->build($pmqQuery));
    }
}
