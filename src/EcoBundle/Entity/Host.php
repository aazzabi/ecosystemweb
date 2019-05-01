<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Host
 *
 * @ORM\Table(name="host")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\HostRepository")
 */
class Host
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
     * @var string
     *
     * @ORM\Column(name="Owner", type="string", length=200, nullable=true)
     */
    private $owner;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateStart", type="date", nullable=true)
     */
    private $datestart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateEnd", type="date", nullable=true)
     */
    private $dateend;

    /**
     * @var integer
     *
     * @ORM\Column(name="TotalPlaces", type="integer", nullable=true)
     */
    private $totalplaces;

    /**
     * @var integer
     *
     * @ORM\Column(name="AvailablePlaces", type="integer", nullable=true)
     */
    private $availableplaces;

    /**
     * @var string
     *
     * @ORM\Column(name="Localisation", type="string", length=200, nullable=true)
     */
    private $localisation;

    /**
     * @var string
     *
     * @ORM\Column(name="Participants", type="text", length=65535, nullable=true)
     */
    private $participants;

    /**
     * @var integer
     *
     * @ORM\Column(name="OwnerID", type="integer", nullable=true)
     */
    private $ownerid;


}

