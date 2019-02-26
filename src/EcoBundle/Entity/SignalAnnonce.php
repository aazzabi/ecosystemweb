<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SignalAnnonce
 *
 * @ORM\Table(name="signal_annonce")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\SignalAnnonceRepository")
 */
class SignalAnnonce
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Annonce", inversedBy="annonces")
     * @ORM\JoinColumn(name="annonce_id", referencedColumnName="id"))
     */
    private $annonce;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id"))
     */
    private $user;


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
     * Set description
     *
     * @param string $description
     *
     * @return SignalAnnonce
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getAnnonce()
    {
        return $this->annonce;
    }

    /**
     * @param mixed $annonce
     */
    public function setAnnonce($annonce)
    {
        $this->annonce = $annonce;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

}

