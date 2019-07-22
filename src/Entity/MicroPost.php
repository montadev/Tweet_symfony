<?php

namespace App\Entity;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
   
    /**
     * @ORM\Column(type="string",length=280)  
     
     * @Assert\Length(min=10,minMessage="Enter please minimum 8 caracters")   
     */

    private $text;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     */

    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * 
     */
    private $user;

   /**
    * @ORM\ManyToMany(targetEntity="App\Entity\User",inversedBy="postsLiked")
    * @ORM\JoinTable(
    *      name="post_likes",
    *    joinColumns={@ORM\JoinColumn(name="post_id",referencedColumnName="id")},
    *  inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")})
    * )
      
    */
    private $likedBy;


    public function __construct()
    {
        $this->likedBy=new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    

    

    /**
     * Get the value of text
     */ 
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the value of text
     *
     * @return  self
     */ 
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the value of time
     */ 
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set the value of time
     *
     * @return  self
     */ 
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
 * @ORM\PrePersist
 */
public function setTimeValue()
{
    $this->time = new \DateTime();
}

    /**
     * Get the value of likedBy
     */ 
    public function getLikedBy()
    {
        return $this->likedBy;
    }

    public function like(User $user)
    {
        if($this->likedBy->contains($user))
        {
            return ;
        }

        $this->likedBy->add($user);
    }
}
