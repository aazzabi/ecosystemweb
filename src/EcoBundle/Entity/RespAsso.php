<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EcoBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RespAsso
 *
 * @ORM\Table(name="resp_asso")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\RespAssoRepository")
 */
class RespAsso extends User
{
    /**
     * @var int
     *
     * @ORM\Column(name="cin", type="integer")
     *
     * Assert\NotNull
     */
    private $cin;

    public function __construct()
    {
        parent::__construct();
        $this->roles = array('ROLE_REPRESENTANT_ASSOC');
    }


    /**
     * Set cin
     *
     * @param integer $cin
     *
     * @return RespAsso
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin
     *
     * @return int
     */
    public function getCin()
    {
        return $this->cin;
    }
}

