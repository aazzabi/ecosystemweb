<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneCommande
 *
 * @ORM\Table(name="ligne_commande")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\LigneCommandeRepository")
 */
class LigneCommande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_commande", type="integer")
     */
    public $idCommande;

    /**
     * @var int
     *
     * @ORM\Column(name="id_annonce", type="integer" ,unique=true)
     */
    public $idAnnonce;

    /**
     * @var int
     *
     * @ORM\Column(name="id_utilisateur", type="integer")
     */

    public $id_utilisateur;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_annonce", type="float")
     */
    public $prixAnnonce;

    /**
     * @return int
     */
    public function getIdUtilisateur()
    {
        return $this->id_utilisateur;
    }

    /**
     * @param int $id_utilisateur
     */
    public function setIdUtilisateur($id_utilisateur)
    {
        $this->id_utilisateur = $id_utilisateur;
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
     * @return LigneCommande
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
     * Set idAnnonce
     *
     * @param integer $idAnnonce
     *
     * @return LigneCommande
     */
    public function setIdAnnonce($idAnnonce)
    {
        $this->idAnnonce = $idAnnonce;

        return $this;
    }

    /**
     * Get idAnnonce
     *
     * @return int
     */
    public function getIdAnnonce()
    {
        return $this->idAnnonce;
    }

    /**
     * Set prixAnnonce
     *
     * @param float $prixAnnonce
     *
     * @return LigneCommande
     */
    public function setPrixAnnonce($prixAnnonce)
    {
        $this->prixAnnonce = $prixAnnonce;

        return $this;
    }

    /**
     * Get prixAnnonce
     *
     * @return float
     */
    public function getPrixAnnonce()
    {
        return $this->prixAnnonce;
    }
}

