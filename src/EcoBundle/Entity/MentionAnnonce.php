<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MentionAnnonce
 *
 * @ORM\Table(name="mention_annonce")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\MentionAnnonceRepository")
 */
class MentionAnnonce
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
     *
     * @ORM\ManyToOne(targetEntity="Annonce", inversedBy="mylikesA")
     * @ORM\JoinColumn(name="annonce_id", referencedColumnName="id")
     */
    private $annonce;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="mylikes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
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

