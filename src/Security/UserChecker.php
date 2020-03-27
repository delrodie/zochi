<?php


namespace App\Security;


use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) return;
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) return;

        // Le compte de l'utilisateur n'est plus actif, alors notification
        if (!$user->getIsActive()) throw new \Exception("Ce membre n'est plus actif");
    }
}