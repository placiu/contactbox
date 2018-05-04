<?php

namespace ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Contact
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="ContactBundle\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="Address", mappedBy="person")
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="Phone", mappedBy="person")
     */
    private $phones;

    /**
     * @ORM\OneToMany(targetEntity="Mail", mappedBy="person")
     */
    private $mails;

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
     * @ORM\Column(name="firstname", type="string", length=50)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=50)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="image_path", type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/png" })
     */
    private $imagePath;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Person
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Person
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     *
     * @return Person
     */
    public function setImagePath($imagePath)
    {
        if($imagePath !== null) {
            /** @var UploadedFile $imagePath */
            $fileName = $this->getFirstName() . $this->getLastName() . '_' . rand(1, 1000) . '.' . $imagePath->guessExtension();
            $imagePath->move('images', $fileName);
            $this->imagePath = $fileName;
            return $this;
        }
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * Get imagePath
     *
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Person
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->phones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mails = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add address
     *
     * @param \ContactBundle\Entity\Address $address
     *
     * @return Person
     */
    public function addAddress(\ContactBundle\Entity\Address $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove address
     *
     * @param \ContactBundle\Entity\Address $address
     */
    public function removeAddress(\ContactBundle\Entity\Address $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Add phone
     *
     * @param \ContactBundle\Entity\Phone $phone
     *
     * @return Person
     */
    public function addPhone(\ContactBundle\Entity\Phone $phone)
    {
        $this->phones[] = $phone;

        return $this;
    }

    /**
     * Remove phone
     *
     * @param \ContactBundle\Entity\Phone $phone
     */
    public function removePhone(\ContactBundle\Entity\Phone $phone)
    {
        $this->phones->removeElement($phone);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhones()
    {
        return $this->phones;
    }


    /**
     * Add mail
     *
     * @param \ContactBundle\Entity\Mail $mail
     *
     * @return Person
     */
    public function addMail(\ContactBundle\Entity\Mail $mail)
    {
        $this->mails[] = $mail;

        return $this;
    }

    /**
     * Remove mail
     *
     * @param \ContactBundle\Entity\Mail $mail
     */
    public function removeMail(\ContactBundle\Entity\Mail $mail)
    {
        $this->mails->removeElement($mail);
    }

    /**
     * Get mails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMails()
    {
        return $this->mails;
    }

    /**
     * Set owner
     *
     * @param \ContactBundle\Entity\User $owner
     *
     * @return Person
     */
    public function setOwner(\ContactBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \ContactBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
