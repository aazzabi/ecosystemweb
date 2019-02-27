<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EcoBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

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
     *
     * @Assert\NotBlank
     */
    private $adresse;

    /**
     * @var int
     *
     * @ORM\Column(nullable=true, name="numerotel", type="integer")
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "Le numéro de téléphone doit se composer de deux chiffre",
     *      maxMessage = "Le numéro de téléphone doit se composer de deux chiffre",
     * )
     */
    private $numeroTel;

    /**
     * @var int
     *
     * @ORM\Column(nullable=true, name="numerofix", type="integer")
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "Le numéro de téléphone doit se composer de deux chiffre",
     *      maxMessage = "Le numéro de téléphone doit se composer de deux chiffre",
     * )
     */
    private $numeroFix;

    /**
     * @var string
     *
     * @ORM\Column(name="specialite", type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $specialite;


    /**
     * @var string
     *
     * @ORM\Column(name="horaire", type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $horaire;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;




    public function __construct()
    {
        parent::__construct();
        $this->roles = array('ROLE_REPARATEUR');
        $this->type="Normal";
        $this->description="aucune";
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

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getNumeroTel()
    {
        return $this->numeroTel;
    }

    /**
     * @param int $numeroTel
     *
     */
    public function setNumeroTel($numeroTel)
    {
        $this->numeroTel = $numeroTel;
    }

    /**
     * @return int
     */
    public function getNumeroFix()
    {
        return $this->numeroFix;
    }

    /**
     * @param int $numeroFix
     */
    public function setNumeroFix($numeroFix)
    {
        $this->numeroFix = $numeroFix;
    }




}

