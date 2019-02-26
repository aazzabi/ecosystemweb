<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reparation
 *
 * @ORM\Table(name="reparation")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\ReparationRepository")
 */
class Reparation
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
     * @var \DateTime
     *
     * @ORM\Column(name="DatePrisEnCharge", type="date")
     */
    private $datePrisEnCharge;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true,name="Commentaire", type="string", length=255)
     */
    private $commentaire;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true,name="statut", type="string", length=255)
     */
    private $statut;

    public function __construct()
    {

        $this->datePrisEnCharge = new \DateTime('now');
        $this->statut="En cours";


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
     * Set datePrisEnCharge
     *
     * @param \DateTime $datePrisEnCharge
     *
     * @return Reparation
     */
    public function setDatePrisEnCharge($datePrisEnCharge)
    {
        $this->datePrisEnCharge = $datePrisEnCharge;

        return $this;
    }

    /**
     * Get datePrisEnCharge
     *
     * @return \DateTime
     */
    public function getDatePrisEnCharge()
    {
        return $this->datePrisEnCharge;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Reparation
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param string $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

}

