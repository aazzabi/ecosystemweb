<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/*use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;*/
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Missions
 * @Vich\Uploadable
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\MissionsRepository")
 */
class Missions
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
     * @ORM\Column(name="lieu", type="string", length=255)
     */
    private $lieu;

    /**
     * @var int
     *
     * @ORM\Column(name="nbvues", type="integer")
     */
    private $nbVues;

    /**
     * @var int
     *
     * @ORM\Column(name="objectif", type="integer")
     */

    private $objectif;

    /**
     * @var int
     *
     * @ORM\Column(name="atteint", type="integer")
     */

    private $atteint;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateLimite", type="datetime")
     */
    private $dateLimite;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="eventsCrees")
     * @ORM\JoinColumn(nullable=true)
     *
     */
    private $createdBy;


    /**
     * @ORM\ManyToOne(targetEntity="CategorieMission")
     * @ORM\JoinColumn(name="categorie",referencedColumnName="id",onDelete="CASCADE")

     */
    private $categorie;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="eventsParticipes")
     * @ORM\JoinTable(name="evenement_user",
     *   joinColumns={
     *     @ORM\JoinColumn(name="evenement_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   }
     * )
     */

    private $participants;




    /**
     * @Vich\UploadableField(mapping="evt_cover", fileNameProperty="cover")
     *
     * @var File
     */
    private $evtCover;

    /**
     * @ORM\Column(name="cover", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $cover;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @var \DateTime
     */
    private $coverUpdatedAt;

    /**
     * @return int
     */
    public function getObjectif()
    {
        return $this->objectif;
    }

    /**
     * @param int $objectif
     */
    public function setObjectif($objectif)
    {
        $this->objectif = $objectif;
    }

    /**
     * @return int
     */
    public function getAtteint()
    {
        return $this->atteint;
    }

    /**
     * @param int $atteint
     */
    public function setAtteint($atteint)
    {
        $this->atteint = $atteint;
    }

    /**
     * @return \DateTime
     */
    public function getDateLimite()
    {
        return $this->dateLimite;
    }

    /**
     * @param \DateTime $dateLimite
     */
    public function setDateLimite($dateLimite)
    {
        $this->dateLimite = $dateLimite;
    }




    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function __constructParticipants()
    {
        $this->participants = new ArrayCollection();
    }

    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Add profile
     *
     * @param \AppBundle\Entity\User $p
     *
     * @return Competence
     */
    public function addProfile(User $p)
    {
        $this->participants[] = $p;

        return $this;
    }

    /**
     * Remove profile
     *
     * @param \AppBundle\Entity\User $p
     */
    public function removeProfile(User $p)
    {
        $this->participants->removeElement($p);
    }


    /**
     * @return User
     */



    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Missions
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Missions
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }


    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * @return File
     */
    public function getEvtCover()
    {
        return $this->evtCover;
    }


    public function setEvtCover($evtCover)
    {
        $this->evtCover = $evtCover;
        if ($evtCover instanceof UploadedFile)
        {
            $this->setCoverUpdatedAt(new \DateTime());
        }
    }

    /**
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param string $cover
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
    }

    /**
     * @return \DateTime
     */
    public function getCoverUpdatedAt()
    {
        return $this->coverUpdatedAt;
    }

    /**
     * @param \DateTime $coverUpdatedAt
     */
    public function setCoverUpdatedAt($coverUpdatedAt)
    {
        $this->coverUpdatedAt = $coverUpdatedAt;
    }

    /**
     * @return mixed
     */
    public function getNbVues()
    {
        return $this->nbVues;
    }

    /**
     * @param mixed $nbVues
     */
    public function setNbVues($nbVues)
    {
        $this->nbVues = $nbVues;
    }


}

