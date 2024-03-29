<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\UserRepository;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


/**
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController{

     private $twig;
     private $microPostRepository;
     private $formFactory;
     private $entityManager;
     private $flashBag;
     
  
     public function __construct(\Twig_Environment $twig,MicroPostRepository $microPostRepository
     ,FormFactoryInterface $formFactory,
     EntityManagerInterface $entityManager,
     RouterInterface $router,FlashBagInterface $flashBag)
     {
        $this->twig=$twig;
        $this->microPostRepository=$microPostRepository; 
        $this->formFactory=$formFactory;
        $this->entityManager=$entityManager;
        $this->router=$router;
        $this->flashBag=$flashBag;
       
     }

     /**
      * @Route("/",name="micro_post_index")
      */
  public function index(UserRepository $userRepo)
  {
       
          $currentUser=$this->getUser();
          
          $followUser=[];
          if($currentUser instanceof User)
          {
               $users=$currentUser->getFollowing();  
              
               $posts=$this->microPostRepository->findAllByUsers($users);

               if(count($posts)===0)
               {
                 $followUser=$userRepo->findAllWithMoreThan5Posts();
                 
               }
               
          }else
          {
            $posts=$this->microPostRepository->findBy([],['time'=>'DESC']);
          }
      $html=$this->twig->render('micro-post/index.html.twig',[
        
         'posts'=>$posts,
         'followUser'=>$followUser
        
      ]);

      return new Response($html);
  }
  
  /**
   * @Route("/edit/{id}",name="micro_post-edit")
   * @Security("is_granted('edit',micropost)",message="denied access hermi montassar")
   */
  public function edit(MicroPost $micropost,Request $request,AuthorizationCheckerInterface $authorazation)
  {
 
      /*if(!$authorazation->isGranted('edit',$micropost))
      {
         throw new UnauthorizedHttpException("Token");
      } 
      */
     $form=$this->formFactory->create(MicroPostType::class,$micropost);

     $form->handleRequest($request);
     
     if($form->isSubmitted() && $form->isValid()){
          
     
          $this->entityManager->persist($micropost);
          $this->entityManager->flush();

          return new RedirectResponse($this->router->generate('micro_post_index'));
     }
    return new Response($this->twig->render('micro-post/add.html.twig',

            ['form'=>$form->createView()]
    ));
  }
 
   /**
    * @Route("/delete/{id}",name="micro_post_delete")
    * @Security("is_granted('edit',micropost)",message="denied access hermi montassar")
    */
   public function delete(MicroPost $micropost)
   {
        
     $this->entityManager->remove($micropost);
     $this->entityManager->flush(); 
     
     $this->flashBag->add('notice','Micro post was deleted');
  
      return new RedirectResponse($this->router->generate('micro_post_index'));
   }

 /**
  * @Route("/add",name="micro_post_add")
  * @Security("is_granted('ROLE_USER')")
  */
  public function add(Request $request)
  {
     
      $user=$this->getUser();
      $micropost=new MicroPost;
      $micropost->setUser($user);
      $form=$this->formFactory->create(MicroPostType::class,$micropost);

      

      $form->handleRequest($request);
      
      if($form->isSubmitted() && $form->isValid()){
      
           $this->entityManager->persist($micropost);
           $this->entityManager->flush();

           return new RedirectResponse($this->router->generate('micro_post_index'));
      }
     return new Response($this->twig->render('micro-post/add.html.twig',

             ['form'=>$form->createView()]
     ));
  }
  
  /**
   * @Route("/user/{username}",name="micro_post_user")
   *
   */
   public function userPosts(User $userWithPosts)
   {
     $html=$this->twig->render('micro-post/user-posts.html.twig',[
        
          /*'posts'=>$this->microPostRepository->findBy(
               ['user'=>$userWithPosts],['time'=>'DESC'])
            */
            'posts'=>$userWithPosts->getPosts(),
            'user'=>$userWithPosts
       ]);
 
       return new Response($html);
   }
   /**
   * @Route("/{id}",name="micro_post_post")
   *
   */
  public function post(MicroPost $microPost)
  {

        
       //$post=$this->microPostRepository->find($id);

       return new Response($this->twig->render('micro-post/single-post.html.twig',[

            'post'=>$microPost
       ]));
  }
}