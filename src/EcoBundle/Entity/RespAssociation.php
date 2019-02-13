<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EcoBundle\Entity\User;
/**
 * RespAssociation
 *
 * @ORM\Table(name="resp_association")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\RespAssociationRepository")
 */
class RespAssociation extends User
{


    /**
     * @var int
     *
     * @ORM\Column(name="cin", type="integer")
     */
    private $cin;




    /**
     * Set cin
     *
     * @param integer $cin
     *
     * @return RespAssociation
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

