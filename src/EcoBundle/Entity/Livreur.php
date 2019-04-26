<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EcoBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Livreur
 *
 * @ORM\Table(name="livreur")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\LivreurRepository")
 */
class Livreur extends User
{

    /**
     * @var string
     *
     * @ORM\Column(name="zone", type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $zone;

    /**
     * @var string
     *
     * @ORM\Column(name="disponibilite", type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $disponibilite;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_livraison", type="integer", nullable=true ,options={"default" : 0})
     */
    private $nbrLivraison;

    /**
     * @var note
     *
     * @ORM\Column(name="note", type="integer", nullable=true ,options={"default" : 0})
     */
    private $note;

    /**
     * @return note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param note $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }




    /**
     * @return int
     */
    public function getNbrLivraison()
    {
        return $this->nbrLivraison;
    }

    /**
     * @param int $nbrLivraison
     */
    public function setNbrLivraison($nbrLivraison)
    {
        $this->nbrLivraison = $nbrLivraison;
    }


    public function __construct()
    {
        parent::__construct();
        $this->roles = array('ROLE_LIVREUR');
    }

    /**
     * @return string
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @param string $zone
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
    }


    /**
     * Set disponibilite
     *
     * @param string $disponibilite
     *
     * @return Livreur
     */
    public function setDisponibilite($disponibilite)
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    /**
     * Get disponibilite
     *
     * @return string
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }
}

