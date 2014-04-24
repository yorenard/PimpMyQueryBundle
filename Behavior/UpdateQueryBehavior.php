<?php

namespace YoRenard\PimpMyQueryBundle\Behavior;

use YoRenard\PimpMyQueryBundle\Entity\PMQQuery;
use YoRenard\PimpMyQueryBundle\Manager\PMQQueryManager;

class UpdateQueryBehavior
{
    /**
     * @var \YoRenard\PimpMyQueryBundle\Manager\PMQQueryManager
     */
    protected $pmqQueryManager;

    /**
     * Construct
     *
     * @param PMQQueryManager $pmqQueryManager
     */
    public function __construct(PMQQueryManager $pmqQueryManager/*, PMQQueryUpdater $pmqQueryUpdater*/)
    {
        $this->pmqQueryManager = $pmqQueryManager;
    }

    public function update(PMQQuery $pmqQuery)
    {
//        $this->get('yorenard_pimp_my_query.behavior.update_query')->update($pmqQuery);
        $this->pmqQueryManager->save($pmqQuery);
    }
}
