<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hostrating
 *
 * @ORM\Table(name="hostrating")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\HostRatingRepository")
 */
class Hostrating
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="HostID", type="integer", nullable=true)
     */
    private $hostid;

    /**
     * @var integer
     *
     * @ORM\Column(name="OwnerID", type="integer", nullable=true)
     */
    private $ownerid;

    /**
     * @var string
     *
     * @ORM\Column(name="Comment", type="text", length=65535, nullable=true)
     */
    private $comment;

    /**
     * @var integer
     *
     * @ORM\Column(name="Rank", type="integer", nullable=true)
     */
    private $rank;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="date", nullable=true)
     */
    private $date = '2019-02-20';


}

