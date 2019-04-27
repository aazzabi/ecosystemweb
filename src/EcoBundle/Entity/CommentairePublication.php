<?php

namespace EcoBundle\Entity;

use BlogBundle\Entity\PublicationForum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * CommentairePublication
 * @Vich\Uploadable
 * @ORM\Table(name="commentaire_publication")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\CommentairePublicationRepository")
 */
class CommentairePublication
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
     * @ORM\Column(nullable= true, name="description", type="string", length=255)
     *
     * @Assert\NotBlank
     *
     */
    private $description;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="SET NULL")
     *
     */
    private $commentedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="commented_at", type="datetime", nullable=true)
     *
     * @Assert\DateTime
     *
     */
    private $commentedAt;

    /**
     * @var PublicationForum
     *
     * @ORM\ManyToOne(targetEntity="PublicationForum")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $publication;

    /**
     *
     * var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SignalisationForumComm", mappedBy="commentaire", cascade={"remove"})
     *
     */
    private $signlaisations;

    /**
     * @Vich\UploadableField(mapping="commentaire_photo", fileNameProperty="photo")
     *
     * @var File
     */
    private $commentairePhoto;

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
     * @var int
     *
     * @ORM\Column(name="likes", type="integer")
     */
    private $likes;

    /**
     * @var int
     *
     * @ORM\Column(name="nbSignalisation", type="integer")
     */
    private $nbrSignalisation;

    /**
     * @var int
     *
     * @ORM\Column(name="dislikes", type="integer")
     */
    private $dislikes;

    public function __construct()
    {
        $this->commentedAt = new \DateTime('now');
        $this->dislikes = 0;
        $this->likes = 0;
        $this->nbrSignalisation = 0;
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
     * @return User
     */
    public function getCommentedBy()
    {
        return $this->commentedBy;
    }

    /**
     * @param User $commentedBy
     */
    public function setCommentedBy($commentedBy)
    {
        $this->commentedBy = $commentedBy;
        $this->photoUpdatedAt = new \DateTime('now');
    }

    /**
     * @return PublicationForum
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * @param PublicationForum $publication
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;
    }

    /**
     * @return \DateTime
     */
    public function getCommentedAt()
    {
        return $this->commentedAt;
    }

    /**
     * @param \DateTime $commentedAt
     */
    public function setCommentedAt($commentedAt)
    {
        $this->commentedAt = $commentedAt;
    }

    /**
     * @return File
     */
    public function getCommentairePhoto()
    {
        return $this->commentairePhoto;
    }

    /**
     * @param File $commentairePhoto
     */
    public function setCommentairePhoto($commentairePhoto)
    {
        $this->commentairePhoto = $commentairePhoto;
        if ($commentairePhoto instanceof UploadedFile) {
            $this->setPhotoUpdatedAt(new \DateTime());
        } }

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
     * @return mixed
     */
    public function getSignlaisations()
    {
        return $this->signlaisations;
    }

    /**
     * @param mixed $signlaisations
     */
    public function setSignlaisations($signlaisations)
    {
        $this->signlaisations = $signlaisations;
    }

    /**
     *
     * @param \EcoBundle\Entity\SignalisationForumComm $signlaisation
     *
     * @return CommentairePublication
     */
    public function addCommentaire(SignalisationForumComm $signlaisation)
    {
        $this->signlaisations[] = $signlaisation;

        return $this;
    }

    /**
     *
     * @param \EcoBundle\Entity\SignalisationForumComm $signlaisation
     */
    public function removeCommentaire(SignalisationForumComm $signlaisation)
    {
        $this->signlaisations->removeElement($signlaisation);
    }

    /**
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    /**
     * @return int
     */
    public function getDislikes()
    {
        return $this->dislikes;
    }

    /**
     * @param int $dislikes
     */
    public function setDislikes($dislikes)
    {
        $this->dislikes = $dislikes;
    }

    /**
     * @return int
     */
    public function getNbrSignalisation()
    {
        return $this->nbrSignalisation;
    }

    /**
     * @param int $nbrSignalisation
     */
    public function setNbrSignalisation($nbrSignalisation)
    {
        $this->nbrSignalisation = $nbrSignalisation;
    }
}

