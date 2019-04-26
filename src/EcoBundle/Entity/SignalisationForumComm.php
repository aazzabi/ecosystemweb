<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SignalisationCommPub
 *
 * @ORM\Table(name="signalisationforumcomm")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\SignalisationRepository")
 */
class SignalisationForumComm
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
     * @ORM\Column(name="libelle", type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $libelle;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL")
     */
    private $signaledBy;

    /**
     * @ORM\ManyToOne(targetEntity="CommentairePublication",cascade={"persist"})
     * @ORM\JoinColumn(name="commentaire_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $commentaire;


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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return SignalisationCommPub
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @return User
     */
    public function getSignaledBy()
    {
        return $this->signaledBy;
    }

    /**
     * @param User $signaledBy
     */
    public function setSignaledBy($signaledBy)
    {
        $this->signaledBy = $signaledBy;
    }

    /**
     * @return mixed
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param mixed $commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }
}

