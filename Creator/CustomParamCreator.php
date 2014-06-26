<?php
namespace YoRenard\PimpMyQueryBundle\Creator;

use YoRenard\PimpMyQueryBundle\Entity\CustomParam;

/**
 * Class CustomParamCreator
 * @package YoRenard\PimpMyQueryBundle\Creator
 */
class CustomParamCreator implements CreatorInterface
{
    public function create()
    {
        return new CustomParam();
    }
} 