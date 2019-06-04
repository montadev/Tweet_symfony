<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\MicroPost;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class AppFixtures extends Fixture

{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
       $this->encoder=$encoder; 
    }
    public function load(ObjectManager $manager)
    {
        
          $this->loadMicroPosts($manager);
          $this->loadUsers($manager);
    }


    public function loadMicroPosts(ObjectManager $manager)
    {
        for ($i=0; $i < 10; $i++) { 
            $micropost=new MicroPost;
            $micropost->setText('some random text '.rand(0,100));
            $micropost->setTime(new \DateTime());
            $manager->persist($micropost);

        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
         $user=new User;
         $user->setUsername('montassar');
         $user->setFullName('hermi montassar');
         $user->setEmail('montasar.hermi@gmail.com');
         $user->setPassword($this->encoder->encodePassword($user,'21798978'));

         $manager->persist($user);
         $manager->flush();
    }
}
