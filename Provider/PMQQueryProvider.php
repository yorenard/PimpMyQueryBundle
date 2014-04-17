<?php

namespace YoRenard\PimpMyQueryBundle\Provider;

use YoRenard\PimpMyQueryBundle\Exception\PMQQueryNotFoundException;
use YoRenard\PimpMyQueryBundle\Manager\PMQQueryManager;

class PMQQueryProvider
{
    protected $pmqQueryManager;

    /**
     * Construct
     *
     * @param PMQQueryManager $pmqQueryManager
     */
    public function __construct(PMQQueryManager $pmqQueryManager)
    {
        $this->pmqQueryManager = $pmqQueryManager;
    }

    /**
     * @param $idPMQQuery
     * @return \YoRenard\PimpMyQueryBundle\Entity\PMQQuery
     * @throws CallNotFoundException
     */
    public function load($idPMQQuery)
    {
        $pmqQuery = $this->pmqQueryManager->load($idPMQQuery);

        if (null === $pmqQuery) {
            throw new PMQQueryNotFoundException(sprintf('No pmq_query identified by id "%s"', $idPMQQuery));
        }

        return $pmqQuery;
    }
}
