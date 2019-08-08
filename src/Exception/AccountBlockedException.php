<?php

namespace App\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class AccountBlockedException extends AccountStatusException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Account has Blocked.';
    }
}