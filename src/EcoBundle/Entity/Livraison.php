<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 *
 * @ORM\Table(name="livraison")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\LivraisonRepository")
 */
class Livraison
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
     * @var int
     *
     * @ORM\Column(name="id_commande", type="integer" ,unique=true)
     */
    private $idCommande;

    /**
     * @var int
     *
     * @ORM\Column(name="id_utilisateur", type="integer" ,unique=true)
     */
    private $idUtilisateur;

    /**
     * @var int
     *
     * @ORM\Column(name="id_livreur", type="integer" ,unique=false)
     */
    private $idLivreur;

    /**
     * @var string
     *
     * @ORM\Column(name="etat_livraison", type="string",length=255)
     */
    private $etatLivraison;
    /**
     * @var string
     *
     * @ORM\Column(name="adresse_livraison", type="string",length=255)
     */
    private $adresseComplete;
    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string",length=255)
     */
    private $ville_livraison;


    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="CASCADE")
     */
    private $LivraisonPassedBy;

    /**
     * @return User
     */
    public function getLivraisonPassedBy()
    {
        return $this->LivraisonPassedBy;
    }

    /**
     * @param User $LivraisonPassedBy
     */
    public function setLivraisonPassedBy($LivraisonPassedBy)
    {
        $this->LivraisonPassedBy = $LivraisonPassedBy;
    }


    /**
     * @return string
     */
    public function getAdresseComplete()
    {
        return $this->adresseComplete;
    }

    /**
     * @param string $adresseComplete
     */
    public function setAdresseComplete($adresseComplete)
    {
        $this->adresseComplete = $adresseComplete;
    }

    /**
     * @return string
     */
    public function getVilleLivraison()
    {
        return $this->ville_livraison;
    }

    /**
     * @param string $ville_livraison
     */
    public function setVilleLivraison($ville_livraison)
    {
        $this->ville_livraison = $ville_livraison;
    }



    /**
     * @return string
     */
    public function getEtatLivraison()
    {
        return $this->etatLivraison;
    }

    /**
     * @param string $etatLivraison
     */
    public function setEtatLivraison($etatLivraison)
    {
        $this->etatLivraison = $etatLivraison;
    }


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_livraison", type="datetime")
     */
    private $dateLivraison;

    /**
     * @return \DateTime
     */
    public function getDateLivraison()
    {
        return $this->dateLivraison;
    }

    /**
     * @param \DateTime $dateLivraison
     */
    public function setDateLivraison($dateLivraison)
    {
        $this->dateLivraison = $dateLivraison;
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
     * Set idCommande
     *
     * @param integer $idCommande
     *
     * @return Livraison
     */
    public function setIdCommande($idCommande)
    {
        $this->idCommande = $idCommande;

        return $this;
    }

    /**
     * Get idCommande
     *
     * @return int
     */
    public function getIdCommande()
    {
        return $this->idCommande;
    }

    /**
     * Set idUtilisateur
     *
     * @param integer $idUtilisateur
     *
     * @return Livraison
     */
    public function setIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    /**
     * Get idUtilisateur
     *
     * @return int
     */
    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    /**
     * Set idLivreur
     *
     * @param integer $idLivreur
     *
     * @return Livraison
     */
    public function setIdLivreur($idLivreur)
    {
        $this->idLivreur = $idLivreur;

        return $this;
    }

    /**
     * Get idLivreur
     *
     * @return int
     */
    public function getIdLivreur()
    {
        return $this->idLivreur;
    }


    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }


}

