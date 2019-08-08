<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController{

     private $twig;

     public function __construct(\Twig_Environment $twig)
     {
        $this->twig=$twig;
     }
 /**
  * @Route("/login",name="security_login")
  *
  */
    public function login(AuthenticationUtils $authenticationUtils)
    {
         
         return new Response($this->twig->render('security/login.html.twig',[

            'last_username'=>$authenticationUtils->getLastUsername(),
            'error'=>$authenticationUtils->getLastAuthenticationError()
         ]));
    }

/**
 * @Route("/logout",name="security_logout")
 *
 */
    public function logout()
    {

    }

    /**
     * @Route("/confirm/{token}",name="security_confirm")
     *
     * @return void
     */
    public function confirm($token,UserRepository $userRepository,ObjectManager $manager)
    {

       $user=$userRepository->findOneBy([

         'confirmationToken'=>$token
       ]);

         if($user!==null)
         {
            $user->setEnabled(true);

            $user->setConfirmationToken("");

            $manager->flush();
         }
       return $this->render('security/confirmation.html.twig',[

            'user'=>$user
       ]);
    }
}