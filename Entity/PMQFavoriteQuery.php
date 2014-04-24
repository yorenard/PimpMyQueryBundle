<?php

namespace YoRenard\PimpMyQueryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * YoRenard\PimpMyQueryBundle\Entity\PMQRunQueue
 *
 * @ORM\Table(name="pmq_favorite_query")
 * @ORM\Entity(repositoryClass="YoRenard\PimpMyQueryBundle\Repository\PMQFavoriteQueryRepository")
 */
class PMQFavoriteQuery
{

    const QUERY_IS_NOT_FAVORITE   = 0;
    const QUERY_IS_FAVORITE       = 1;

    /**
     * @var PMQQuery $query
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="YoRenard\PimpMyQueryBundle\Entity\PMQQuery", inversedBy="favoriteQueries")
     * @ORM\JoinColumn(name="id_query", referencedColumnName="id_query", columnDefinition="INT UNSIGNED NOT NULL")
     */
    private $query;

    /**
     * @var integer $lfUser
     *
     * @ORM\Column(name="id_lf_user", columnDefinition="INT UNSIGNED NULL")
     */
    private $lfUser;

    /**
     * @param \YoRenard\LFUserBundle\Entity\LFUser $lfUser
     */
    public function setLfUser(/*\YoRenard\LFUserBundle\Entity\LFUser*/ $lfUser)
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

}