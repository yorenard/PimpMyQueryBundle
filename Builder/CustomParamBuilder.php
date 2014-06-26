<?php
namespace YoRenard\PimpMyQueryBundle\Builder;

use YoRenard\PimpMyQueryBundle\Creator\CustomParamCreator;
use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;

class CustomParamBuilder
{
    /**
     * @var \YoRenard\PimpMyQueryBundle\Creator\CustomParamCreator
     */
    protected $customParamCreator;

    /**
     * Construct
     *
     * @param CustomParamCreator $customParamCreator
     */
    public function __construct(CustomParamCreator $customParamCreator)
    {
        $this->customParamCreator = $customParamCreator;
    }

    /**
     * @param PMQQuery $pmqQuery
     * @return \YoRenard\PimpMyQueryBundle\Entity\CustomParam
     */
    public function build(PMQQuery $pmqQuery)
    {
        $customParam = $this->customParamCreator->create();
        $customParam->setPmqQuery($pmqQuery);

        return $customParam;
    }
}
