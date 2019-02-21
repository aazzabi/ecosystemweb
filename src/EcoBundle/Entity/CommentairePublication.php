<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

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

    public function __construct()
    {
        $this->commentedAt = new \DateTime('now');
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


}

