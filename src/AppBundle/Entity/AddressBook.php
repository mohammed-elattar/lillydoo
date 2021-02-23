<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="address_book")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AddressBookRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("phone")
 */
class AddressBook
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Assert\NotBlank(message="First Name value should not be blank")
     */
    private ?string $firstName = null;

    /**
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\NotBlank(message="Last Name value should not be blank")
     */
    private ?string $lastName = null;

    /**
     * @ORM\Column(name="street", type="string", length=255)
     * @Assert\NotBlank(message="Street value should not be blank"))
     */
    private ?string $street = null;

    /**
     * @ORM\Column(name="house_no", type="string", length=255)
     * @Assert\NotBlank(message="House Number value should not be blank")
     */
    private ?string $houseNo = null;

    /**
     * @ORM\Column(name="zip", type="string", length=255)
     * @Assert\NotBlank(message="Zip value should not be blank")
     */
    private ?string $zip = null;

    /**
     * @ORM\Column(name="city", type="string", length=255)
     * @Assert\NotBlank(message="City value should not be blank")
     */
    private ?string $city = null;

    /**
     * @ORM\Column(name="country", type="string", length=255)
     * @Assert\NotBlank(message="Country value should not be blank")
     */
    private ?string $country = null;

    /**
     * @ORM\Column(name="phone", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Phone value should not be blank")
     */
    private ?string $phone = null;

    /**
     * @ORM\Column(name="birth_date", type="date", length=255)
     * @Assert\NotBlank(message="Birth Date value should not be blank"))
     * @Assert\Date(message="Birth Date value should be valid format")
     */
    private ?DateTime $birthDate = null;

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Email value should not be blank")
     * @Assert\Email(message="Email value should be valid email format")
     */
    private ?string $email = null;

    /**
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/jpeg" , "image/png" , "image/tiff" , "image/svg+xml"})
     */
    private ?string $picture = null;


    /**
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setHouseNo(?string $houseNo): self
    {
        $this->houseNo = $houseNo;

        return $this;
    }

    public function getHouseNo(): ?string
    {
        return $this->houseNo;
    }

    public function setZip(?string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setBirthDate(?DateTime $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getBirthDate(): ?DateTime
    {
        return $this->birthDate;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
    
    public function getPicture(): ?string
    {
        return $this->picture;
    }
}

