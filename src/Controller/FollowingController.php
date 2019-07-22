<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
class FollowingController extends AbstractController
{
    /**
     * @Route("/follow/{id}",name="following_follow")
     *
     */
     public function follow(User $user ,ObjectManager $manager)
     {
         $currentUser=$this->getUser();
         if($currentUser->getFollowing()->contains($user)===false)
         {
            $currentUser->getFollowing()->add($user);
         }
        
         $manager->flush();

         return $this->redirectToRoute('micro_post_user',['username'=>$currentUser->getUsername()]);
     }
    /**
     * @Route("/unfollow/{id}",name="following_unfollow")
     *
     */
     public function unfollow(User $user ,ObjectManager $manager)
     {
        $currentUser=$this->getUser();

        $currentUser->getFollowing()->removeElement($user);
       
        $manager->flush();

        return $this->redirectToRoute('micro_post_user',['username'=>$currentUser->getUsername()]);
     }
}
