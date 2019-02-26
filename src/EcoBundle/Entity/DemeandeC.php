<?php

namespace EcoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * DemeandeC
 * @Vich\Uploadable
 * @ORM\Table(name="demeande_c")
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\DemeandeCRepository")
 */
class DemeandeC
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id", onDelete="CASCADE")
     *
     */
    private $reparateur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateDeLaDemande", type="date")
     */

    private $dateDeLaDemande;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true,name="statut", type="string", length=255)
     */
    private $type;

    /**
     * @Vich\UploadableField(mapping="compteprof_photo", fileNameProperty="photo")
     *
     * @var File
     */
    private $compteprof_photo;

    /**
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $photo;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @var \DateTime
     */
    private $photoUpdatedAt;


    public function __construct()
    {

        $this->dateDeLaDemande = new \DateTime('now');
        $this->photoUpdatedAt = new \DateTime('now');


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
     * Set dateDeLaDemande
     *
     * @param \DateTime $dateDeLaDemande
     *
     * @return DemeandeC
     */
    public function setDateDeLaDemande($dateDeLaDemande)
    {
        $this->dateDeLaDemande = $dateDeLaDemande;

        return $this;
    }

    /**
     * Get dateDeLaDemande
     *
     * @return \DateTime
     */
    public function getDateDeLaDemande()
    {
        return $this->dateDeLaDemande;
    }

    /**
     * @return User
     */
    public function getReparateur()
    {
        return $this->reparateur;
    }

    /**
     * @param User $reparateur
     */
    public function setReparateur($reparateur)
    {
        $this->reparateur = $reparateur;
    }




    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return File
     */
    public function getCompteprofPhoto()
    {
        return $this->compteprof_photo;
    }

    /**
     * @param File $compteprof_photo
     */
    public function setCompteprofPhoto($compteprof_photo)
    {
        $this->compteprof_photo = $compteprof_photo;
    }


    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return \DateTime
     */
    public function getPhotoUpdatedAt()
    {
        return $this->photoUpdatedAt;
    }

    /**
     * @param \DateTime $photoUpdatedAt
     */
    public function setPhotoUpdatedAt($photoUpdatedAt)
    {
        $this->photoUpdatedAt = $photoUpdatedAt;
    }


}

