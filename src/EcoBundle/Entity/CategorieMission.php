<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieMission
 *
 * @ORM\Table(name="categorie_missions")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\CategorieMissionRepository")
 */
class CategorieMission
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
     * @ORM\Column(name="Libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="But", type="string", length=255)
     */
    private $but;


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
     * @return CategorieMission
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
     * Set but
     *
     * @param string $but
     *
     * @return CategorieMission
     */
    public function setBut($but)
    {
        $this->but = $but;

        return $this;
    }

    /**
     * Get but
     *
     * @return string
     */
    public function getBut()
    {
        return $this->but;
    }

    function __toString()
    {
        return $this->getLibelle();
    }

}

