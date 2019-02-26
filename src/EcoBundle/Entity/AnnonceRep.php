<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AnnonceRep
 * @Vich\Uploadable
 *
 * @ORM\Table(name="annonce_rep")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\AnnonceRepRepository")
 */
class AnnonceRep
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
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datepublication", type="date")
     */
    private $datepublication;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datemodification", type="date")
     */
    private $datemodification;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="CASCADE")
     *
     */
    private $utilisateur;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="CASCADE")
     *
     */
    private $reparateur;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, name="categorie", type="string", length=255)
     */
    private $categorie;

    /**
     * @var int
     *
     * @ORM\Column(nullable=true, name="lastprix", type="integer")
     */
    private $lastprix;


    /**
     * @Vich\UploadableField(mapping="annoncerep_photo", fileNameProperty="photo")
     *
     * @var File
     */
    private $annoncerepPhoto;

    /**
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $photo;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @var \DateTime
     */
    private $photoUpdatedAt;

    /**
     * AnnonceRep constructor.
     * @param \DateTime $photoUpdatedAt
     */
    public function __construct()
    {
        $this->photoUpdatedAt = new \DateTime('now');
        $this->datemodification = new \DateTime('now');
        $this->datepublication = new \DateTime('now');
        $this->etat="En cours";


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
     * Set titre
     *
     * @param string $titre
     *
     * @return AnnonceRep
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return AnnonceRep
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set datepublication
     *
     * @param \DateTime $datepublication
     *
     * @return AnnonceRep
     */
    public function setDatepublication($datepublication)
    {
        $this->datepublication = $datepublication;

        return $this;
    }

    /**
     * Get datepublication
     *
     * @return \DateTime
     */
    public function getDatepublication()
    {
        return $this->datepublication;
    }

    /**
     * Set datemodification
     *
     * @param \DateTime $datemodification
     *
     * @return AnnonceRep
     */
    public function setDatemodification($datemodification)
    {
        $this->datemodification = $datemodification;

        return $this;
    }

    /**
     * Get datemodification
     *
     * @return \DateTime
     */
    public function getDatemodification()
    {
        return $this->datemodification;
    }

    /**
     * @return User
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param User $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }




    /**
     * Get note
     *
     * @return int
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return File
     */
    public function getAnnoncerepPhoto()
    {
        return $this->annoncerepPhoto;
    }

    /**
     * @param File $annoncerepPhoto
     */
    public function setAnnoncerepPhoto($annoncerepPhoto)
    {
        $this->annoncerepPhoto = $annoncerepPhoto;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return \DateTime
     */
    public function getPhotoUpdatedAt()
    {
        return $this->photoUpdatedAt;
    }

    /**
     * @param \DateTime $photoUpdatedAt
     */
    public function setPhotoUpdatedAt($photoUpdatedAt)
    {
        $this->photoUpdatedAt = $photoUpdatedAt;
    }

    /**
     * @return int
     */
    public function getLastprix()
    {
        return $this->lastprix;
    }

    /**
     * @param int $lastprix
     */
    public function setLastprix($lastprix)
    {
        $this->lastprix = $lastprix;
    }

    /**
     * @return User
     */
    public function getReparateur()
    {
        return $this->reparateur;
    }

    /**
     * @param User $reparateur
     */
    public function setReparateur($reparateur)
    {
        $this->reparateur = $reparateur;
    }



    /**
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param string $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param string $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }








}

