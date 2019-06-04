<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email",message="this email already in use")
 * @UniqueEntity(fields="username",message="this username already in use")
 */
class User implements UserInterface,\Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=191 ,unique=true) 
     * @Assert\NotBlank() 
     * @Assert\Length(min=5,max=50)
     */
    private $username;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8,max=4096)
     *
     */ 
    private $plainPassword;

    /**
     * @ORM\Column(type="string",length=191,unique=true)
     * @Assert\NotBlank()  
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min=4,max=50)
     */
    private $fullName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getRoles()
    {
           return [
               'ROLE_USER'
           ];
    }
    public function getSalt()
    {
             return null;
    }
    public function eraseCredentials()
    {

    }

    public function serialize() 
    {
         return serialize([
               $this->id,
               $this->username,
               $this->password
         ]);   
    }
    public function unserialize($data)
     {
        
         list(
             $this->id,
             $this->username,
             $this->password
             )=unserialize($data);
    }
}
