<?php

namespace YoRenard\PimpMyQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * YoRenard\PimpMyQueryBundle\Entity\PMQRight
 *
 * @ORM\Table(name="pmq_right",indexes={@ORM\Index(name="pmq_right_level", columns={"level"})})
 * @ORM\Entity(repositoryClass="YoRenard\PimpMyQueryBundle\Repository\PMQRightRepository")
 */
class PMQRight
{

    const USER_TYPE_USER    = 'USER';
    const USER_TYPE_MANAGER = 'MANAGER';

    /**
     * @var integer $idRight
     *
     * @ORM\Column(name="id_right", columnDefinition="INT UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idRight;

    /**
     * @var PMQQuery $query
     *
     * @ORM\ManyToOne(targetEntity="YoRenard\PimpMyQueryBundle\Entity\PMQQuery", inversedBy="rights", cascade={"persist"})
     * @ORM\JoinColumn(name="id_query", referencedColumnName="id_query", columnDefinition="INT UNSIGNED NOT NULL")
     */
    private $query;

    /**
     * @var string $level
     *
     * @ORM\Column(name="level", type="string", columnDefinition="ENUM('USER', 'MANAGER')")
     */
    private $level;

    /**
     * @var \YoRenard\LFUserBundle\Entity\Service $service
     *
     * @ORM\ManyToOne(targetEntity="YoRenard\LFUserBundle\Entity\Service")
     * @ORM\JoinColumn(name="id_service", referencedColumnName="id_service", columnDefinition="INT UNSIGNED NOT NULL")
     */
    private $service;


    /**
     * @param integer $idRight
     */
    public function setIdRight($idRight)
    {
        $this->idRight = $idRight;
    }

    /**
     * @return integer
     */
    public function getIdRight()
    {
        return $this->idRight;
    }

    /**
     * @param boolean $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return boolean
     */
    public function getLevel()
    {
        return $this->level;
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
     * @param \YoRenard\LFUserBundle\Entity\Service $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return \YoRenard\LFUserBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

}