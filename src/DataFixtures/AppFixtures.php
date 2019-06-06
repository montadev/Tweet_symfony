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

    private const USERS = [
                    [
                        'username' => 'john_doe',
                        'email' => 'john_doe@doe.com',
                        'password' => 'john123',
                        'fullName' => 'John Doe',
                        'roles'=>[User::ROLE_USER]
                    ],
                    [
                        'username' => 'rob_smith',
                        'email' => 'rob_smith@smith.com',
                        'password' => 'rob12345',
                        'fullName' => 'Rob Smith',
                        'roles'=>[User::ROLE_USER]
                    ],
                    [
                        'username' => 'marry_gold',
                        'email' => 'marry_gold@gold.com',
                        'password' => 'marry12345',
                        'fullName' => 'Marry Gold',
                        'roles'=>[User::ROLE_USER]
                    ],
                    [
                        'username' => 'super_admin',
                        'email' => 'super_admin@admin.com',
                        'password' => 'admin12345',
                        'fullName' => 'Micro Admin',
                        'roles'=>[User::ROLE_ADMIN]
                    ],
          ];

            private const POST_TEXT = [
                    'Hello, how are you?',
                    'It\'s nice sunny weather today',
                    'I need to buy some ice cream!',
                    'I wanna buy a new car',
                    'There\'s a problem with my phone',
                    'I need to go to the doctor',
                    'What are you up to today?',
                    'Did you watch the game yesterday?',
                    'How was your day?'
                ];

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
       $this->encoder=$encoder; 
    }
    public function load(ObjectManager $manager)
    {
          $this->loadUsers($manager);
          $this->loadMicroPosts($manager);
         
    }


    public function loadMicroPosts(ObjectManager $manager)
    {
        for ($i=0; $i < 30; $i++) { 
            $micropost=new MicroPost;
            $micropost->setText('some random text '.rand(0,100));
            $date=new \DateTime();
            $date->modify('-' . rand(1,10) .' day');
            $micropost->setTime($date);
            $micropost->setUser($this->getReference(self::USERS[rand(0,count(self::USERS)-1)]['username']));
            $manager->persist($micropost);

        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {

         foreach (self::USERS as $userData) {
             
            $user=new User;
            $user->setUsername($userData['username']);
            $user->setFullName($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->encoder->encodePassword($user,$userData['password']));
            $user->setRoles($userData['roles']);
            $this->setReference($userData['username'],$user);
            $manager->persist($user);
         }
         
         $manager->flush();
    }
}
