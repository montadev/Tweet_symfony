<?php

namespace App\Mailer;

use App\Entity\User;
use Twig_Environment;
class Mailer{

    private $mailer;
    private $twig;
    private $mailfrom;

    public function __construct(\Swift_Mailer $mailer,Twig_Environment $twig,string $mailfrom)
    {
       $this->mailer=$mailer; 
       $this->twig=$twig;
       $this->mailfrom=$mailfrom;
    }


    public function sendConfirmationEmail(User $user){

        


        $message = (new \Swift_Message('Hello Email'))
        ->setFrom($this->mailfrom)
        ->setTo('montasar.hermi@gmail.com')
        ->setBody($this->twig->render(
           // templates/hello/email.txt.twig
           'email/registration.html.twig',[

               'user'=>$user
           ]
       ),'text/html');


    $this->mailer->send($message);
    }
}