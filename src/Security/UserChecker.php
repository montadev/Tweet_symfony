<?php

namespace App\Security;



use App\Entity\User as AppUser;
use App\Exception\AccountBlockedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user is deleted, show a generic Account Not Found message.
        if (!$user->getEnabled()) {
            throw new AccountBlockedException();

           // die('oki1');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is expired, the user may be notified
        if (!$user->getEnabled()) {
            throw new AccountExpiredException('...');

            //die('oki2');
        }
    }
}
