<?php
namespace App\Security;

use App\Entity\User;
use App\Entity\MicroPost;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class MicroPostVoter extends Voter{
     
    const Edit='edit';
    const Delete='delete';
    private$decisionManager;
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
      $this->decisionManager=$decisionManager;  
    }
     protected function supports($attribute, $subject){

          if(!in_array($attribute,[self::Edit,self::Delete]))
          {
            return false;
          }

          if(!$subject instanceof MicroPost)
          {
             return false;
          }
          return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $authenticatedUser=$token->getUser();
        
        if(!$authenticatedUser instanceof User){
             
            return false;
        }

        $micropost=$subject;

         
        $roles=$authenticatedUser->getRoles();

        if($this->decisionManager->decide($token,[User::ROLE_ADMIN]))
          {
              return true;
          }
         
        
         return $micropost->getUser()->getId()===$authenticatedUser->getId();
            
           
          
        
    }
}