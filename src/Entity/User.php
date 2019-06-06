<?php
// src/Entity/User.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Serializable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface,\Serializable
{

     const ROLE_USER='ROLE_USER';
     const ROLE_ADMIN='ROLE_ADMIN';
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
    * @ORM\Column(type="string",length=191)
    * @Assert\NotBlank()
    * @Assert\Length(min=4,max=20)
    */
    private $fullName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64,nullable=true)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost", mappedBy="user")
     */
    private $posts;

  /**
   * @ORM\Column(type="simple_array")
   *
   */
    private $roles;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

   
    
   

    

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

 

    public function eraseCredentials()
    {
    }
    public function getRoles()
    {
           return $this->roles;
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

    /**
     * Get the value of fullName
     */ 
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set the value of fullName
     *
     * @return  self
     */ 
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return Collection|MicroPost[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(MicroPost $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(MicroPost $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    

    

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */ 
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }
}
