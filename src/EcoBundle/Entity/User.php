<?php

namespace EcoBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity
 * @Vich\Uploadable
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "user" = "User",
 *     "reparateur" = "Reparateur",
 *     "livreur" = "Livreur",
 *     "respAsso" = "RespAsso",
 *     "respSoc" = "RespSoc"
 * })
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=150, nullable=false)
     *
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_propriete", type="string", length=255, nullable=true)
     */
    private $nomPropriete;
    /**
     * @var string
     *
     * @ORM\Column(name="rue", type="string", length=255 , nullable=true)
     */
    private $rue;
    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255 , nullable=true)
     */
    private $ville;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @Vich\UploadableField(mapping="user_photo", fileNameProperty="photo")
     *
     * @var File
     */
    private $userPhoto;

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
    /**
     *
     * @ORM\OneToMany(targetEntity="Annonce", mappedBy="User")
     */
    private $myAnnonces;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=150, nullable=false)
     *
     * @Assert\NotBlank
     */
    private $prenom;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $group;

    /**
     *
     * var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Evenement", mappedBy="createdBy", cascade={"remove"})
     *
     */
    private $eventsCrees;


    /**
     *
     * var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Missions", mappedBy="createdBy")
     *
     */
    private $missionsCrees;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Evenement", mappedBy="participants")
     */
    private $eventsParticipes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Missions", mappedBy="participants")
     */
    private $missionsParticipes;

    /**
     * @var string
     *
     * @ORM\Column(name="numtel", type="string",length=8,nullable=true )
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "Le numéro de téléphone doit se composer de deux chiffre",
     *      maxMessage = "Le numéro de téléphone doit se composer de deux chiffre",
     * )
     */
    private $numtel;


    public function __construct()
    {
        parent::__construct();
        $this->photoUpdatedAt = new \DateTime('now');
        $this->enabled = true;
        $this->roles = array();
        $this->eventsCrees = new ArrayCollection();
        $this->missionsCrees = new ArrayCollection();
        $this->eventsParticipes = new ArrayCollection();
        $this->missionsParticipes = new ArrayCollection();
        $this->myAnnonces = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getNomPropriete()
    {
        return $this->nomPropriete;
    }

    /**
     * @param string $nomPropriete
     */
    public function setNomPropriete($nomPropriete)
    {
        $this->nomPropriete = $nomPropriete;
    }

    /**
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * @param string $rue
     */
    public function setRue($rue)
    {
        $this->rue = $rue;
    }

    /**
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**

    /**
     * @return string
     */
    public function getNumtel()
    {
        return $this->numtel;
    }

    /**
     * @param int $numtel
     */
    public function setNumtel($numtel)
    {
        $this->numtel = $numtel;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMissionsParticipes()
    {
        return $this->missionsParticipes;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $missionsParticipes
     */
    public function setMissionsParticipes($missionsParticipes)
    {
        $this->missionsParticipes = $missionsParticipes;
    }


    public function addMyAnnonce(Annonce $annonce)
    {
        $this->myAnnonces[] = $annonce;
        return $this;
    }

    public function removeMyAnnonce(Annonce $annonce)
    {
        $this->myAnnonces->removeElement($annonce);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get Group
     *
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = $this->roles;

        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        $roles[] = static::ROLE_DEFAULT;

        return $roles;
    }

    public function getImageLink()
    {
        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($this->getEmail())))."?s=32&d=identicon&f=y";
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set Group
     *
     * @param \EcoBundle\Entity\Group $group
     *
     * @return User
     */
    public function setGroup(\EcoBundle\Entity\Group $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return File
     */
    public function getUserPhoto()
    {
        return $this->userPhoto;
    }

    public function setUserPhoto($userPhoto)
    {
        $this->userPhoto = $userPhoto;

        if ($userPhoto instanceof UploadedFile) {
            $this->setPhotoUpdatedAt(new \DateTime());
        }
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

    /**
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEventsCrees()
    {
        return $this->eventsCrees;
    }

    /**
     * @param mixed $eventsCrees
     */
    public function setEventsCrees($eventsCrees)
    {
        $this->eventsCrees = $eventsCrees;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventsParticipes()
    {
        return $this->eventsParticipes;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $eventsParticipes
     */
    public function setEventsParticipes($eventsParticipes)
    {
        $this->eventsParticipes = $eventsParticipes;
    }


    /**
     *
     * @param \EcoBundle\Entity\Evenement $e
     *
     * @return User
     */
    public function addEventsCrees(Evenement $e)
    {
        $this->eventsCrees[] = $e;

        return $this;
    }

    /**
     *
     * @param \EcoBundle\Entity\Evenement $eventsCrees
     */
    public function removeEventsCrees(Evenement $e)
    {
        $this->eventsCrees->removeElement($e);
    }
    public function getMyAnnonces()
    {
        return $this->myAnnonces;
    }

    /**
     * @param mixed $myAnnonces
     */
    public function setMyAnnonces($myAnnonces)
    {
        $this->myAnnonces = $myAnnonces;
    }
    /**
     *
     * @param \EcoBundle\Entity\Evenement $e
     *
     * @return User
     */
    public function addEventsParticipes(Evenement $e)
    {
        $this->eventsParticipes[] = $e;

        return $this;
    }

    /**
     *
     * @param \EcoBundle\Entity\Evenement $eventsParticipes
     */
    public function removeEventsParticipes(Evenement $e)
    {
        $this->eventsParticipes->removeElement($e);
    }

    /**
     * @return mixed
     */
    public function getMissionsCrees()
    {
        return $this->missionsCrees;
    }

    /**
     * @param mixed $missionsCrees
     */
    public function setMissionsCrees($missionsCrees)
    {
        $this->missionsCrees = $missionsCrees;
    }
    /**
     *
     * @param \EcoBundle\Entity\Missions $m
     *
     * @return User
     */
    public function addMissionsCrees(Missions $e)
    {
        $this->missionsCrees[] = $e;

        return $this;
    }

    /**
     *
     * @param \EcoBundle\Entity\Missions $m
     */
    public function removeMissionsCrees(Missions $m)
    {
        $this->missionsCrees->removeElement($m);
    }
    /**
     *
     * @param \EcoBundle\Entity\Missions $e
     *
     * @return User
     */
    public function addMissionsParticipes(Missions $e)
    {
        $this->missionsParticipes[] = $e;

        return $this;
    }

    /**
     *
     * @param \EcoBundle\Entity\Missions $missionsParticipes
     */
    public function removeMissionsParticipes(Missions $e)
    {
        $this->missionsParticipes->removeElement($e);
    }
}
