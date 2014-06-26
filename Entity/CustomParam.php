<?php
namespace YoRenard\PimpMyQueryBundle\Entity;

class CustomParam
{
    /**
     * @var \YoRenard\PimpMyQueryBundle\Entity\PMQQuery
     */
    protected $pmqQuery;

    /**
     * @var string
     */
    protected $runExec;

    /**
     * @var \DateTime
     */
    protected $firstRunDt;

    /**
     * @var \DateTime
     */
    protected $lastRunDt;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $mode;

    /**
     * @param \YoRenard\PimpMyQueryBundle\Entity\PMQQuery $pmqQuery
     */
    public function setPmqQuery(PMQQuery $pmqQuery)
    {
        $this->pmqQuery = $pmqQuery;
    }

    /**
     * @return \YoRenard\PimpMyQueryBundle\Entity\PMQQuery
     */
    public function getPmqQuery()
    {
        return $this->pmqQuery;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param \DateTime $firstRunDt
     */
    public function setFirstRunDt(\DateTime $firstRunDt)
    {
        $this->firstRunDt = $firstRunDt;
    }

    /**
     * @return \DateTime
     */
    public function getFirstRunDt()
    {
        return $this->firstRunDt;
    }

    /**
     * @param \DateTime $lastRunDt
     */
    public function setLastRunDt(\DateTime $lastRunDt)
    {
        $this->lastRunDt = $lastRunDt;
    }

    /**
     * @return \DateTime
     */
    public function getLastRunDt()
    {
        return $this->lastRunDt;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $runExec
     */
    public function setRunExec($runExec)
    {
        $this->runExec = $runExec;
    }

    /**
     * @return string
     */
    public function getRunExec()
    {
        return $this->runExec;
    }
}
