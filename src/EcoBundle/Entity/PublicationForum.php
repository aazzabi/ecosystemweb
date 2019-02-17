<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PublicationForum
 *
 * @ORM\Table(name="publication_forum")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\PublicationForumRepository")
 */
class PublicationForum
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="publicationsForum")
     * @ORM\JoinColumn(nullable=true)
     *
     */
    private $publicationCreatedBy;

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
     * @return PublicationForum
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
     * @return PublicationForum
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
     * Set etat
     *
     * @param string $etat
     *
     * @return PublicationForum
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @return User
     */
    public function getPublicationCreatedBy()
    {
        return $this->publicationCreatedBy;
    }

    /**
     * @param User $publicationCreatedBy
     */
    public function setPublicationCreatedBy($publicationCreatedBy)
    {
        $this->publicationCreatedBy = $publicationCreatedBy;
    }
}

