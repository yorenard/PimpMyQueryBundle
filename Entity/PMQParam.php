<?php

namespace YoRenard\PimpMyQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * YoRenard\PimpMyQueryBundle\Entity\PMQParam
 *
 * @ORM\Table(name="pmq_param")
 * @ORM\Entity(repositoryClass="YoRenard\PimpMyQueryBundle\Repository\PMQParamRepository")
 */
class PMQParam
{

    const FIELD_TYPE_INT    = 'INT';
    const FIELD_TYPE_STRING = 'STRING';
    const FIELD_TYPE_DATE   = 'DATE';
    const FIELD_TYPE_SELECT = 'SELECT';
    const FIELD_TYPE_FILE   = 'FILE';

    const FIRST_MAGIC_PARAM_CHARACTER = ':';
    const LAST_MAGIC_PARAM_CHARACTER  = '';

    /**
     * @var integer $idParam
     *
     * @ORM\Column(name="id_param", columnDefinition="INT UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idParam;

    /**
     * @var PMQQuery $query
     *
     * @ORM\ManyToOne(targetEntity="YoRenard\PimpMyQueryBundle\Entity\PMQQuery", inversedBy="params")
     * @ORM\JoinColumn(name="id_query", referencedColumnName="id_query", columnDefinition="INT UNSIGNED NOT NULL")
     */
    private $query;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string $fieldType
     *
     * @ORM\Column(name="field_type", type="string", columnDefinition="ENUM('INT', 'STRING', 'DATE', 'SELECT', 'FILE')")
     */
    private $fieldType;

    /**
     * @var text $fieldValues
     *
     * @ORM\Column(name="field_values", type="text", nullable=true)
     */
    private $fieldValues;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var ArrayCollection $runQueueParams
     *
     * @ORM\OneToMany(targetEntity="YoRenard\PimpMyQueryBundle\Entity\PMQRunQueueParam", mappedBy="param", cascade={"remove"})
     */
    private $runQueueParams;



    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $fieldType
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;
    }

    /**
     * @return string
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * @param string $fieldValues
     */
    public function setFieldValues($fieldValues)
    {
        $this->fieldValues = $fieldValues;
    }

    /**
     * @return string
     */
    public function getFieldValues()
    {
        return $this->fieldValues;
    }

    /**
     * @param integer $idParam
     */
    public function setIdParam($idParam)
    {
        $this->idParam = $idParam;
    }

    /**
     * @return int
     */
    public function getIdParam()
    {
        return $this->idParam;
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
     * @param integer $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return int
     */
    public function getQuery()
    {
        return $this->query;
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