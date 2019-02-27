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
     * @Assert\NotBlank
     */
    private $libelle;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float")
     * @Assert\NotBlank
     */
    private $lat;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float")
     * @Assert\NotBlank
     */
    private $lng;

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }

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

