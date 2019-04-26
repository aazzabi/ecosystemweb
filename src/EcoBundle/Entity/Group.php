<?php namespace EcoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="EcoBundle\Repository\GroupRepository")
 * @ORM\Table(name="groupe")
 */
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="group")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $users;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @Vich\UploadableField(mapping="group_logo", fileNameProperty="logo")
     *
     * @var File
     */
    private $groupPhoto;

    /**
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $logo;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @var \DateTime
     */
    private $logoUpdatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=150, nullable=true)
     *
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=150, nullable=true)
     *
     * @Assert\NotBlank
     */
    private $type;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->logoUpdatedAt = new \DateTime('now');
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }



    /**
     * Add user
     *
     * @param \EcoBundle\Entity\User $user
     *
     * @return Group
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \EcoBundle\Entity\User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    function __toString()
    {
        return $this->getName();
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $type
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
    public function getGroupPhoto()
    {
        return $this->groupPhoto;
    }

    /**
     * @param File $groupPhoto
     */
    public function setGroupPhoto($groupPhoto)
    {
        $this->groupPhoto = $groupPhoto;
        if ($groupPhoto instanceof UploadedFile) {
            $this->setLogoUpdatedAt(new \DateTime());
        }
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return \DateTime
     */
    public function getLogoUpdatedAt()
    {
        return $this->logoUpdatedAt;
    }

    /**
     * @param \DateTime $logoUpdatedAt
     */
    public function setLogoUpdatedAt($logoUpdatedAt)
    {
        $this->logoUpdatedAt = $logoUpdatedAt;
    }
}
