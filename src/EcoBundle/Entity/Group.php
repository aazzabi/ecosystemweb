<?php namespace EcoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
//use Vich\UploaderBundle\Mapping\Annotation as Vich;

//Add  * @Vich\Uploadable


/**
 * @ORM\Entity
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="group")
     *
     */
    private $users;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $name;
//
//    /**
//     * @Vich\UploadableField(mapping="logo_file", fileNameProperty="logo")
//     *
//     * @var File
//     */
//    private $logoFile;
//
//    /**
//     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
//     *
//     * @var string
//     */
//    private $logo;
//
//    /**
//     * @ORM\Column(type="datetime",nullable=true)
//     *
//     * @var \DateTime
//     */
//    private $logoUpdatedAt;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="type", type="string", length=150, nullable=true)
//     */
//    private $type;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
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

//    /**
//     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
//     *
//     * @return Group
//     */
//    public function setLogoFile(File $logoFile = null)
//    {
//        $this->logoFile = $logoFile;
//
//        if ($logoFile) {
//            $this->logoUpdatedAt = new \DateTimeImmutable();
//        }
//
//        return $this;
//    }
//
//    /**
//     * @return File|null
//     */
//    public function getLogoFile()
//    {
//        return $this->logoFile;
//    }
//
//    /**
//     * @param string $logo
//     *
//     * @return Group
//     */
//    public function setLogo($logo)
//    {
//        $this->logo = $logo;
//
//        return $this;
//    }
//
//    /**
//     * @return string|null
//     */
//    public function getLogo()
//    {
//        return $this->logo;
//    }
//
//    /**
//     * Set logoUpdatedAt
//     *
//     * @param \DateTime $logoUpdatedAt
//     *
//     * @return Group
//     */
//    public function setLogoUpdatedAt($logoUpdatedAt)
//    {
//        $this->logoUpdatedAt = $logoUpdatedAt;
//
//        return $this;
//    }
//
//    /**
//     * Get logoUpdatedAt
//     *
//     * @return \DateTime
//     */
//    public function getLogoUpdatedAt()
//    {
//        return $this->logoUpdatedAt;
//    }
//
//    /**
//     * @return string
//     */
//    public function getType()
//    {
//        return $this->type;
//    }
//
//    /**
//     * @param string $type
//     */
//    public function setType($type)
//    {
//        $this->type = $type;
//    }

}
