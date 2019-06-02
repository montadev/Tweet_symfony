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

/**
 * @Route("/micro-post")
 */
class MicroPostController {

     private $twig;
     private $microPostRepository;
     private $formFactory;
     private $entityManager;
     public function __construct(\Twig_Environment $twig,MicroPostRepository $microPostRepository
     ,FormFactoryInterface $formFactory,EntityManagerInterface $entityManager,RouterInterface $router)
     {
        $this->twig=$twig;
        $this->microPostRepository=$microPostRepository; 
        $this->formFactory=$formFactory;
        $this->entityManager=$entityManager;
        $this->router=$router;
     }

     /**
      * @Route("/",name="micro_post_index")
      */
  public function index()
  {
       
      $html=$this->twig->render('micro-post/index.html.twig',[
        
         'posts'=>$this->microPostRepository->findAll()
      ]);

      return new Response($html);
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