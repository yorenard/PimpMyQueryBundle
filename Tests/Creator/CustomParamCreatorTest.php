<?php
namespace YoRenard\PimpMyQueryBundle\Tests\Creator;

use YoRenard\PimpMyQueryBundle\Creator\CustomParamCreator;
use YoRenard\PimpMyQueryBundle\Entity\CustomParam;

class CustomParamCreatorTest extends \PHPUnit_Framework_TestCase
{
    protected $customParamCreator;

    public function setUp()
    {
        parent::setUp();

        $this->customParamCreator = new CustomParamCreator();
    }

    public function testCreate()
    {
        $this->assertEquals(new CustomParam(), $this->customParamCreator->create());
    }
} 