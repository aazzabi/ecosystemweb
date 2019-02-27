<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CategoriePub
 *
 * @ORM\Table(name="categorie_pub")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\CategoriePubRepository")
 */
class CategoriePub
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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=300)
     *
     * @Assert\NotBlank
     *
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="domaine", type="string", length=255)
     *
     * @Assert\NotBlank
     *
     */
    private $domaine;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="PublicationForum", mappedBy="categorie")
     */
    private $publicationsForum;

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
     * @return CategoriePub
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * @param string $domaine
     */
    public function setDomaine($domaine)
    {
        $this->domaine = $domaine;
    }
    function __toString()
    {
        return $this->getLibelle() . " " . $this->getDescription() . "" ;
    }

    /**
     * @return mixed
     */
    public function getPublicationsForum()
    {
        return $this->publicationsForum;
    }

    /**
     * @param mixed $publicationsForum
     */
    public function setPublicationsForum($publicationsForum)
    {
        $this->publicationsForum = $publicationsForum;
    }

}

