<?php

namespace EcoBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



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
     */
    private $nom;

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
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=150, nullable=false)
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
     * @ORM\OneToMany(targetEntity="Evenement", mappedBy="createdBy")
     *
     */
    private $eventsCrees;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Evenement", mappedBy="participants")
     */
    private $eventsParticipes;

    public function __construct()
    {
        parent::__construct();
        $this->photoUpdatedAt = new \DateTime('now');
        $this->enabled = true;
        $this->roles = array();
        $this->eventsCrees = new ArrayCollection();
        $this->eventsParticipes = new ArrayCollection();
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

        // we need to make sure to have at least one role
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
}
