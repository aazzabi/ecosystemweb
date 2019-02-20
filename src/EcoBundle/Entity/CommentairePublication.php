<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommentairePublication
 *
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="CASCADE")
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
     * @ORM\ManyToOne(targetEntity="PublicationForum", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $publication;

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
}

