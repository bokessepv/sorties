<?php

namespace App\Security;

use App\Entity\Participant as AppUser;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    /**
     * @inheritDoc
     */
    public function checkPreAuth(UserInterface $user):void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if ($user->isDeleted()) {
            throw new CustomUserMessageAccountStatusException('Cet utilisateur a été supprimé.');
        }
    }

    /**
     * @inheritDoc
     */
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }


        if ($user->isExpired()) {
            throw new AccountExpiredException('Ce compte a expiré.');
        }
    }
}