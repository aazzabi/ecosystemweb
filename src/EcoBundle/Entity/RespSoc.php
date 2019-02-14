<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EcoBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * RespSoc
 *
 * @ORM\Table(name="resp_soc")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\RespSocRepository")
 */
class RespSoc extends User
{
    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="PtCollecte", mappedBy="responsable")
     */
    private $ptCollectes;

    /**
     * RespSoc constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->ptCollectes = new ArrayCollection();
        $this->roles = array('ROLE_REPRESENTANT_SOCIETE');
    }


}

