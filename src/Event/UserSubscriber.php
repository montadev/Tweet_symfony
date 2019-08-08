<?php

namespace App\Event;

use Twig_Environment;
use App\Mailer\Mailer;
use App\Event\UserRegisterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface{


    private $mailer;
    private $twig;

    public function __construct(Mailer $mailer)
    {
        $this->mailer=$mailer;
       
    }

    public static function getSubscribedEvents()
    {
        return [
            
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {
        $user=$event->getRegisteredUser();    
        
        $this->mailer->sendConfirmationEmail($user);
    }
    
}