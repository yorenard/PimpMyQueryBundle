<?php

namespace YoRenard\PimpMyQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * YoRenard\PimpMyQueryBundle\Entity\PMQRunQueue
 *
 * @ORM\Table(name="pmq_run_queue",indexes={@ORM\Index(name="pmq_run_queue_first_run_dt", columns={"first_run_dt"}), @ORM\Index(name="pmq_run_queue_last_run_dt", columns={"last_run_dt"}), @ORM\Index(name="pmq_run_queue_run_exec", columns={"run_exec"})})
 * @ORM\Entity(repositoryClass="YoRenard\PimpMyQueryBundle\Repository\PMQRunQueueRepository")
 */
class PMQRunQueue
{

    const EXECUTE_MODE_PLAN_ONCE    = 'ONCE';
    const EXECUTE_MODE_PLAN_DAILY   = 'DAILY';
    const EXECUTE_MODE_PLAN_WEEKLY  = 'WEEKLY';
    const EXECUTE_MODE_PLAN_MONTHLY = 'MONTHLY';

    /**
     * @var integer $idRunQueue
     *
     * @ORM\Column(name="id_run_queue", columnDefinition="INT UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idRunQueue;

    /**
     * @var PMQQuery $query
     *
     * @ORM\ManyToOne(targetEntity="YoRenard\PimpMyQueryBundle\Entity\PMQQuery", inversedBy="runQueues")
     * @ORM\JoinColumn(name="id_query", referencedColumnName="id_query", columnDefinition="INT UNSIGNED NOT NULL")
     */
    private $query;

    /**
     * @var LFUser $lfUser
     *
     * @ORM\ManyToOne(targetEntity="YoRenard\LFUserBundle\Entity\LFUser", inversedBy="runQueues")
     * @ORM\JoinColumn(name="id_lf_user", referencedColumnName="id_lf_user", nullable=true)
     */
    private $lfUser;

    /**
     * @var datetime $createDt
     *
     * @ORM\Column(name="create_dt", type="datetime")
     */
    private $createDt;

    /**
     * @var datetime $firstRunDt
     *
     * @ORM\Column(name="first_run_dt", type="date")
     */
    private $firstRunDt;

    /**
     * @var datetime $lastRunDt
     *
     * @ORM\Column(name="last_run_dt", type="date")
     */
    private $lastRunDt;

    /**
     * @var string $runExec
     *
     * @ORM\Column(name="run_exec", type="string", columnDefinition="ENUM('ONCE', 'DAILY', 'WEEKLY', 'MONTHLY') DEFAULT 'ONCE'")
     */
    private $runExec;

    /**
     * @var text $email
     *
     * @ORM\Column(name="email", type="text", nullable=true)
     */
    private $email;

    /**
     * @var ArrayCollection $runQueueParams
     *
     * @ORM\OneToMany(targetEntity="YoRenard\PimpMyQueryBundle\Entity\PMQRunQueueParam", mappedBy="runQueue", cascade={"persist", "remove"})
     */
    private $runQueueParams;


    public function __construct()
    {
        $this->setCreateDt(new \DateTime());
    }

    public static function getExecutePlanList()
    {
        return array(
            self::EXECUTE_MODE_PLAN_ONCE,
            self::EXECUTE_MODE_PLAN_DAILY,
            self::EXECUTE_MODE_PLAN_WEEKLY,
            self::EXECUTE_MODE_PLAN_MONTHLY,
        );
    }

    /**
     * @param \Datetime $createDt
     */
    public function setCreateDt($createDt)
    {
        $this->createDt = $createDt;
    }

    /**
     * @return \Datetime
     */
    public function getCreateDt()
    {
        return $this->createDt;
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
     * @param integer $idRunQueue
     */
    public function setIdRunQueue($idRunQueue)
    {
        $this->idRunQueue = $idRunQueue;
    }

    /**
     * @return int
     */
    public function getIdRunQueue()
    {
        return $this->idRunQueue;
    }

    /**
     * @param \YoRenard\LFUserBundle\Entity\LFUser $lfUser
     */
    public function setLfUser($lfUser)
    {
        $this->lfUser = $lfUser;
    }

    /**
     * @return \YoRenard\LFUserBundle\Entity\LFUser
     */
    public function getLfUser()
    {
        return $this->lfUser;
    }

    /**
     * @param PMQQuery $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return PMQQuery
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param \Datetime $firstRunDt
     */
    public function setFirstRunDt($firstRunDt)
    {
        $this->firstRunDt = $firstRunDt;
    }

    /**
     * @return \Datetime
     */
    public function getFirstRunDt()
    {
        return $this->firstRunDt;
    }

    /**
     * @param \Datetime $lastRunDt
     */
    public function setLastRunDt($lastRunDt)
    {
        $this->lastRunDt = $lastRunDt;
    }

    /**
     * @return \Datetime
     */
    public function getLastRunDt()
    {
        return $this->lastRunDt;
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

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $runQueueParams
     */
    public function setRunQueueParams($runQueueParams)
    {
        $this->runQueueParams = $runQueueParams;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRunQueueParams()
    {
        return $this->runQueueParams;
    }

}