<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SignalAnnounceRep
 *
 * @ORM\Table(name="signal_announce_rep")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\SignalAnnounceRepRepository")
 */
class SignalAnnounceRep
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
     * @ORM\Column(name="cause", type="string", length=255)
     * @Assert\NotBlank
     */
    private $cause;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL")
     *
     */
    private $signaledBy;

    /**
     * @ORM\ManyToOne(targetEntity="AnnonceRep",cascade={"persist"})
     * @ORM\JoinColumn(name="annoncerep_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $announce;


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
     * @return string
     */
    public function getCause()
    {
        return $this->cause;
    }

    /**
     * @param string $cause
     */
    public function setCause($cause)
    {
        $this->cause = $cause;
    }

    /**
     * @return User
     */
    public function getSignaledBy()
    {
        return $this->signaledBy;
    }

    /**
     * @param User $signaledBy
     */
    public function setSignaledBy($signaledBy)
    {
        $this->signaledBy = $signaledBy;
    }

    /**
     * @return mixed
     */
    public function getAnnounce()
    {
        return $this->announce;
    }

    /**
     * @param mixed $announce
     */
    public function setAnnounce($announce)
    {
        $this->announce = $announce;
    }




}

