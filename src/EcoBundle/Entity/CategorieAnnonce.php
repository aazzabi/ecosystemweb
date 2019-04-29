<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;


/**
 * CategorieAnnonce
 *
 * @ORM\Table(name="categorie_annonce")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\CategorieAnnonceRepository")
 */
class CategorieAnnonce
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
     *
     */
    private $libelle;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Annonce", mappedBy="categorie")
     * @ORM\JoinColumn(name="annonce_id", referencedColumnName="id")
     * @JMS\MaxDepth(2)
     */
    private $annonces;

    public function __construct() {

        $this->annonces = new ArrayCollection();
    }

    public function addAnnonce(Annonce $annonce)
    {
        $this->annonces[] = $annonce;

        return $this;
    }
    public function removeAnnonce(Annonce $annonce)
    {
        $this->annonces->removeElement($annonce);
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return CategorieAnnonce
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

    public function __toString()
    {
        return  $this->getLibelle();
    }

    /**
     * @return mixed
     */
    public function getAnnonces()
    {
        return $this->annonces;
    }

    /**
     * @param mixed $annonces
     */
    public function setAnnonces($annonces)
    {
        $this->annonces = $annonces;
    }
}

