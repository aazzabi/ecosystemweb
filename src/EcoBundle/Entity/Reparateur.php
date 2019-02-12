<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EcoBundle\Entity\User;
/**
 * Reparateur
 *
 * @ORM\Table(name="Reparateur")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\ReparateurRepository")
 */
class Reparateur extends  User
{
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="specialite", type="string", length=255)
     */
    private $specialite;


    /**
     * @var string
     *
     * @ORM\Column(name="horaire", type="string", length=255)
     */
    private $horaire;



    public function __construct()
    {
        parent::__construct();
        $this->roles = array('ROLE_REPARATEUR');
    }

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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Reparateur
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set specialite
     *
     * @param string $specialite
     *
     * @return Reparateur
     */
    public function setSpecialite($specialite)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite
     *
     * @return string
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }


    /**
     * Set horaire
     *
     * @param string $horaire
     *
     * @return Reparateur
     */
    public function setHoraire($horaire)
    {
        $this->horaire = $horaire;

        return $this;
    }

    /**
     * Get horaire
     *
     * @return string
     */
    public function getHoraire()
    {
        return $this->horaire;
    }
}

