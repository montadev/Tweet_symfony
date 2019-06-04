<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * @Route("/micro-post")
 */
class MicroPostController {

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
  public function index()
  {
       
      $html=$this->twig->render('micro-post/index.html.twig',[
        
         'posts'=>$this->microPostRepository->findBy([],['time'=>'DESC'])
      ]);

      return new Response($html);
  }
  
  /**
   * @Route("/edit/{id}",name="micro_post-edit")
   *
   */
  public function edit(MicroPost $micropost,Request $request)
  {

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
    *
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
  *
  */
  public function add(Request $request)
  {
     
    
      $micropost=new MicroPost;
      $micropost->setTime(new \DateTime());
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