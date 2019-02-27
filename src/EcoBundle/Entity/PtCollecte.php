<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * PtCollecte
 *
 * @ORM\Table(name="pt_collecte")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\PtCollecteRepository")
 */
class PtCollecte
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
     * @var string
     *
     * @ORM\Column(name="localisation", type="string", length=255)
     */
    private $localisation;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     *
     * @Assert\NotBlank
     */
    private $type;

    /**
     * Many ptCollecte(features) have one respsonsable(product). This is the owning side.
     * @ORM\ManyToOne(targetEntity="RespSoc", inversedBy="ptCollectes")
     * @ORM\JoinColumn(name="responsableSoc_id", referencedColumnName="id")
     */
    private $responsable;


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
     * @return PtCollecte
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
     * Set localisation
     *
     * @param string $localisation
     *
     * @return PtCollecte
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get localisation
     *
     * @return string
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return PtCollecte
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set responsable
     *
     * @param string $responsable
     *
     * @return PtCollecte
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return string
     */
    public function getResponsable()
    {
        return $this->responsable;
    }
}

