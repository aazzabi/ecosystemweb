<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hostparticipation
 *
 * @ORM\Table(name="hostparticipation")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\HostParticiperRepository")
 */
class Hostparticipation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="prim", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $prim;

    /**
     * @var integer
     *
     * @ORM\Column(name="UserID", type="integer", nullable=false)
     */
    private $userid;

    /**
     * @var integer
     *
     * @ORM\Column(name="HostID", type="integer", nullable=false)
     */
    private $hostid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ParticipationDate", type="date", nullable=true)
     */
    private $participationdate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Active", type="boolean", nullable=false)
     */
    private $active;


}

