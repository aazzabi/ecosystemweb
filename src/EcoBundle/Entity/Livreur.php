<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EcoBundle\Entity\User;

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


    public function __construct()
    {
        parent::__construct();
        $this->roles = array('ROLE_LIVREUR');
    }

    /**
     * Set zone
     *
     * @param string $zone
     *
     * @return Livreur
     */
    public function setZone($zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return string
     */
    public function getZone()
    {
        return $this->zone;
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

