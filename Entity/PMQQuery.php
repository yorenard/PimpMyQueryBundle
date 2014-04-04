<?php

namespace YoRenard\PimpMyQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * YoRenard\PimpMyQueryBundle\Entity\PMQQuery
 *
 * @ORM\Table(name="pmq_query",indexes={@ORM\Index(name="pmq_query_publish", columns={"public"})})
 * @ORM\Entity(repositoryClass="YoRenard\PimpMyQueryBundle\Repository\PMQQueryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PMQQuery
{

    const EXECUTE_MODE_EXECUTE  = 'EXECUTE';
    const EXECUTE_MODE_EXPORT   = 'EXPORT';
    const EXECUTE_MODE_PLAN     = 'PLAN';

    const NB_QUERY_ON_PAGE      = 25;

    const ORDER_BY_ID           = 'idQuery';
    const ORDER_BY_NAME         = 'name';
    const ORDER_BY_DESC         = 'desc';
    const ORDER_BY_NB_EXEC      = 'nbExec';
    const ORDER_BY_LAST_EXEC_DT = 'lastExecDt';
    const ORDER_BY_FAVORITE     = 'favorite';
    const ORDER_BY_CREATE_DT    = 'createDt';

    const USER_MODE_ADMIN       = 'admin';
    const USER_MODE_USER        = 'user';

    const CONNECTION_STAT       = 'stat_read_only';
    const CONNECTION_FENIX      = 'read_only';


    /**
     * @var integer $idQuery
     *
     * @ORM\Column(name="id_query", columnDefinition="INT UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idQuery;

    /**
     * @var string $connection
     *
     * @ORM\Column(name="connection", type="string", length=100)
     */
    private $connection;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string $desc
     *
     * @ORM\Column(name="`desc`", type="string", length=255)
     */
    private $desc;

    /**
     * @var text $query
     *
     * @ORM\Column(name="query", type="text", nullable=true)
     */
    private $query;

    /**
     * @var datetime $createDt
     *
     * @ORM\Column(name="create_dt", type="datetime")
     */
    private $createDt;

    /**
     * @var datetime $updateDt
     *
     * @ORM\Column(name="update_dt", type="datetime")
     */
    private $updateDt;

    /**
     * @var integer $nbExec
     *
     * @ORM\Column(name="nb_exec", columnDefinition="INT UNSIGNED NULL")
     */
    private $nbExec;

    /**
     * @var float $avgExecutionTime
     *
     * @ORM\Column(name="avg_execution_time", columnDefinition="FLOAT UNSIGNED NULL")
     */
    private $avgExecutionTime;

    /**
     * @var datetime $lastExecDt
     *
     * @ORM\Column(name="last_exec_dt", type="datetime")
     */
    private $lastExecDt;

    /**
     * @var boolean $public
     *
     * @ORM\Column(name="public", type="boolean")
     */
    private $public;

    /**
     * @var ArrayCollection $params
     *
     * @ORM\OneToMany(targetEntity="YoRenard\PimpMyQueryBundle\Entity\PMQParam", mappedBy="query", cascade={"persist", "remove"})
     */
    private $params;

    /**
     * @var ArrayCollection $runQueues
     *
     * @ORM\OneToMany(targetEntity="YoRenard\PimpMyQueryBundle\Entity\PMQRunQueue", mappedBy="query", cascade={"remove"})
     */
    private $runQueues;

    /**
     * @var ArrayCollection $favoriteQueries
     *
     * @ORM\OneToMany(targetEntity="YoRenard\PimpMyQueryBundle\Entity\PMQFavoriteQuery", mappedBy="query", cascade={"remove"})
     */
    private $favoriteQueries;

    /**
     * @var ArrayCollection $rights
     *
     * @ORM\OneToMany(targetEntity="YoRenard\PimpMyQueryBundle\Entity\PMQRight", mappedBy="query", cascade={"persist", "remove"})
     */
    private $rights;


    public function __construct()
    {
        $this->setCreateDt(new \DateTime());
        $this->setUpdateDt(new \DateTime());
        $this->setNbExec(0);
        $this->setAvgExecutionTime(0);
        $this->setLastExecDt(null);
        $this->setPublic(false);

        $this->params = new ArrayCollection();
        $this->rights = new ArrayCollection();
        $this->favoriteQueries = new ArrayCollection();
    }

    public static function getExecuteModeList()
    {
        return array(
            self::EXECUTE_MODE_EXECUTE,
            self::EXECUTE_MODE_EXPORT,
            self::EXECUTE_MODE_PLAN,
        );
    }

    public static function getConnectionList()
    {
        return array(
            self::CONNECTION_FENIX,
            self::CONNECTION_STAT
        );
    }

    /**
     * @param string $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return string
     */
    public function getConnection()
    {
        return $this->connection;
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
     * @param string $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param integer $idQuery
     */
    public function setIdQuery($idQuery)
    {
        $this->idQuery = $idQuery;
    }

    /**
     * @return integer
     */
    public function getIdQuery()
    {
        return $this->idQuery;
    }

    /**
     * @param \Datetime $lastExecDt
     */
    public function setLastExecDt($lastExecDt)
    {
        $this->lastExecDt = $lastExecDt;
    }

    /**
     * @return \Datetime
     */
    public function getLastExecDt()
    {
        return $this->lastExecDt;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param integer $nbExec
     */
    public function setNbExec($nbExec)
    {
        $this->nbExec = $nbExec;
    }

    /**
     * @return integer
     */
    public function getNbExec()
    {
        return $this->nbExec;
    }

    /**
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param string $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param \Datetime $updateDt
     */
    public function setUpdateDt()
    {
        $this->updateDt = new \DateTime;
    }

    /**
     * @return \Datetime
     */
    public function getUpdateDt()
    {
        return $this->updateDt;
    }

    /**
     * @param ArrayCollection $params
     */
    public function setParams(ArrayCollection $params)
    {
        $this->params = $params;
    }

    public function removeParams()
    {
        $this->params = null;
    }

    /**
     * Add params
     *
     * @param YoRenard\PimpMyQueryBundle\Entity\PMQParam $params
     */
    public function addParam(\YoRenard\PimpMyQueryBundle\Entity\PMQParam $param)
    {
        $this->params[] = $param;
    }

    /**
     * @return ArrayCollection
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param ArrayCollection $runQueues
     */
    public function setRunQueues($runQueues)
    {
        $this->runQueues = $runQueues;
    }

    /**
     * @return ArrayCollection
     */
    public function getRunQueues()
    {
        return $this->runQueues;
    }

    /**
     * @param ArrayCollection $favoriteQueries
     */
    public function setFavoriteQueries($favoriteQueries)
    {
        $this->favoriteQueries = $favoriteQueries;
    }

    /**
     * @return ArrayCollection
     */
    public function getFavoriteQueries()
    {
        return $this->favoriteQueries;
    }

    /**
     * @param ArrayCollection $rights
     */
    public function setRights(ArrayCollection $rights)
    {
        foreach ($rights as $right) {
            $right->addQuery($this);
        }

        $this->rights = $rights;
    }

    /**
     * Add rights
     *
     * @param YoRenard\PimpMyQueryBundle\Entity\PMQRight $rights
     */
    public function addRight(\YoRenard\PimpMyQueryBundle\Entity\PMQRight $right)
    {
        $this->rights[] = $right;
    }

    public function removeRights()
    {
        $this->rights = null;
    }

    /**
     * @return ArrayCollection
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updateDt = new \DateTime;
    }

    /**
     * @param float $avgExecutionTime
     */
    public function setAvgExecutionTime($avgExecutionTime)
    {
        $this->avgExecutionTime = $avgExecutionTime;
    }

    /**
     * @return float
     */
    public function getAvgExecutionTime()
    {
        return $this->avgExecutionTime;
    }
}