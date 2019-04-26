<?php

namespace EcoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;


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
     *
     * @Assert\NotBlank
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=555)
     *
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $etat;

    /**
     * @var CategoriePub
     *
     * @ORM\ManyToOne(targetEntity="CategoriePub")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="CASCADE")
     * @JMS\MaxDepth(2)
     */
    private $categorie;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="CASCADE")
     * @JMS\MaxDepth(2)
     */
    private $publicationCreatedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pub_created_at", type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    private $publicationCreatedAt;

    /**
     *
     * var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="CommentairePublication", mappedBy="publication", cascade={"remove"})
     *
     */
    private $commentaires;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrVues", type="integer")
     */
    private $nbrVues;

    public function __construct()
    {
        $this->publicationCreatedAt = new \DateTime('now');
        $this->etat= "publié";
        $this->nbrVues= 0;
        $this->commentaires = new ArrayCollection();
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

    /**
     * @return \DateTime
     */
    public function getPublicationCreatedAt()
    {
        return $this->publicationCreatedAt;
    }

    /**
     * @param \DateTime $publicationCreatedAt
     */
    public function setPublicationCreatedAt($publicationCreatedAt)
    {
        $this->publicationCreatedAt = $publicationCreatedAt;
    }

    /**
     * @return CategoriePub
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param CategoriePub $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * @param mixed $commentaires
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
    }

    /**
     *
     * @param \EcoBundle\Entity\CommentairePublication $commentaire
     *
     * @return PublicationForum
     */
    public function addCommentaire(CommentairePublication $commentaire)
    {
        $this->commentaires[] = $commentaire;

        return $this;
    }

    /**
     *
     * @param \EcoBundle\Entity\CommentairePublication $commentaire
     */
    public function removeCommentaire(CommentairePublication $commentaire)
    {
        $this->commentaires->removeElement($commentaire);
    }

    function __toString()
    {
        return $this->getTitre();
    }

    /**
     * @return int
     */
    public function getNbrVues()
    {
        return $this->nbrVues;
    }

    /**
     * @param int $nbrVues
     */
    public function setNbrVues($nbrVues)
    {
        $this->nbrVues = $nbrVues;
    }
}

