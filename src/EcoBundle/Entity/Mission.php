<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Recyclage
 *
 * @ORM\Table(name="mission")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\MissionRepository")
 */
class Mission
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="objectif", type="integer")
     *
     * @Assert\NotNull
     *
     */
    private $objectif;

    /**
     * @var int
     *
     * @ORM\Column(name="matscollectes", type="integer")
     *
     * @Assert\NotNull
     *
     */
    private $matscollectes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateLimite", type="date")*
     *
     * @Assert\DateTime
     */
    private $dateLimite;

    /**
     * @var float
     *
     * @ORM\Column(name="recompense", type="float")
     *
     * @Assert\NotNull
     */
    private $recompense;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set objectif
     *
     * @param integer $objectif
     *
     * @return Mission
     */
    public function setObjectif($objectif)
    {
        $this->objectif = $objectif;

        return $this;
    }

    /**
     * Get objectif
     *
     * @return int
     */
    public function getObjectif()
    {
        return $this->objectif;
    }

    /**
     * Set matscollectes
     *
     * @param integer $matscollectes
     *
     * @return Mission
     */
    public function setMatscollectes($matscollectes)
    {
        $this->matscollectes = $matscollectes;

        return $this;
    }

    /**
     * Get matscollectes
     *
     * @return int
     */
    public function getMatscollectes()
    {
        return $this->matscollectes;
    }

    /**
     * Set dateLimite
     *
     * @param \DateTime $dateLimite
     *
     * @return Mission
     */
    public function setDateLimite($dateLimite)
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }

    /**
     * Get dateLimite
     *
     * @return \DateTime
     */
    public function getDateLimite()
    {
        return $this->dateLimite;
    }

    /**
     * Set recompense
     *
     * @param float $recompense
     *
     * @return Mission
     */
    public function setRecompense($recompense)
    {
        $this->recompense = $recompense;

        return $this;
    }

    /**
     * Get recompense
     *
     * @return float
     */
    public function getRecompense()
    {
        return $this->recompense;
    }
}

