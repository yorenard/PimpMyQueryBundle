<?php

namespace YoRenard\PimpMyQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * YoRenard\PimpMyQueryBundle\Entity\PMQRunQueueParam
 *
 * @ORM\Table(name="pmq_run_queue_param")
 * @ORM\Entity(repositoryClass="YoRenard\PimpMyQueryBundle\Repository\PMQRunQueueParamRepository")
 */
class PMQRunQueueParam
{

    /**
     * @var integer $idRunQueueParam
     *
     * @ORM\Column(name="id_run_queue_param", columnDefinition="INT UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idRunQueueParam;

    /**
     * @var PMQRunQueue $runQueue
     *
     * @ORM\ManyToOne(targetEntity="YoRenard\PimpMyQueryBundle\Entity\PMQRunQueue", inversedBy="runQueueParams")
     * @ORM\JoinColumn(name="id_run_queue", referencedColumnName="id_run_queue", columnDefinition="INT UNSIGNED NOT NULL")
     */
    private $runQueue;

    /**
     * @var PMQParam $param
     *
     * @ORM\ManyToOne(targetEntity="YoRenard\PimpMyQueryBundle\Entity\PMQParam", inversedBy="runQueueParams")
     * @ORM\JoinColumn(name="id_param", referencedColumnName="id_param", columnDefinition="INT UNSIGNED NOT NULL")
     */
    private $param;

    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;


    /**
     * @param integer $idRunQueueParam
     */
    public function setIdRunQueueParam($idRunQueueParam)
    {
        $this->idRunQueueParam = $idRunQueueParam;
    }

    /**
     * @return int
     */
    public function getIdRunQueueParam()
    {
        return $this->idRunQueueParam;
    }

    /**
     * @param \YoRenard\PimpMyQueryBundle\Entity\PMQParam $param
     */
    public function setParam($param)
    {
        $this->param = $param;
    }

    /**
     * @return \YoRenard\PimpMyQueryBundle\Entity\PMQParam
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @param PMQRunQueue $runQueue
     */
    public function setRunQueue($runQueue)
    {
        $this->runQueue = $runQueue;
    }

    /**
     * @return PMQRunQueue
     */
    public function getRunQueue()
    {
        return $this->runQueue;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

}