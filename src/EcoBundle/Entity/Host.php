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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return \DateTime
     */
    public function getDatestart()
    {
        return $this->datestart;
    }

    /**
     * @param \DateTime $datestart
     */
    public function setDatestart($datestart)
    {
        $this->datestart = $datestart;
    }

    /**
     * @return \DateTime
     */
    public function getDateend()
    {
        return $this->dateend;
    }

    /**
     * @param \DateTime $dateend
     */
    public function setDateend($dateend)
    {
        $this->dateend = $dateend;
    }

    /**
     * @return int
     */
    public function getTotalplaces()
    {
        return $this->totalplaces;
    }

    /**
     * @param int $totalplaces
     */
    public function setTotalplaces($totalplaces)
    {
        $this->totalplaces = $totalplaces;
    }

    /**
     * @return int
     */
    public function getAvailableplaces()
    {
        return $this->availableplaces;
    }

    /**
     * @param int $availableplaces
     */
    public function setAvailableplaces($availableplaces)
    {
        $this->availableplaces = $availableplaces;
    }

    /**
     * @return string
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * @param string $localisation
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;
    }

    /**
     * @return string
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param string $participants
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
    }

    /**
     * @return int
     */
    public function getOwnerid()
    {
        return $this->ownerid;
    }

    /**
     * @param int $ownerid
     */
    public function setOwnerid($ownerid)
    {
        $this->ownerid = $ownerid;
    }

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

