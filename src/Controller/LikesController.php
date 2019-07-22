<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/likes")
 */
class LikesController extends AbstractController
{
     
    /**
     * @Route("/like",name="likes_like")
     *
     */
     public function like(Request $request,MicroPostRepository $micropostrepo)
     {
         $id=$request->request->get('name');

        $micropost=$micropostrepo->find($id);
           $currentUser=$this->getUser();

           if(!$currentUser instanceof User)
           {
              return new JsonResponse([],Response::HTTP_UNAUTHORIZED);
           }

           $micropost->like($currentUser);

           $this->getDoctrine()->getManager()->flush();
             
           return new JsonResponse([
   
             'count'=>$micropost->getLikedBy()->count()
           ]);
           

          return new JsonResponse([
   
            'count'=>$request->request->get('name')
          ]);
     }

      /**
       * @Route("/unlike",name="like_unlike")
       *
       */
     public function unlike(Request $request,MicroPostRepository $micropostrepo)
     {
        $id=$request->request->get('name');
        $micropost=$micropostrepo->find($id); 
        $currentUser=$this->getUser();

        if(!$currentUser instanceof User)
        {
           return new JsonResponse([],Response::HTTP_UNAUTHORIZED);
        }

        $micropost->getLikedBy()->removeElement($currentUser);

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([

          'count'=>$micropost->getLikedBy()->count()
        ]);
     }
}
