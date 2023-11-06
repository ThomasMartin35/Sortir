<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UsersVoter extends Voter
{
    public const VIEWCANCEL = 'EXCURSION_VIEW_CANCEL';
    // Si admin ou organisateur + statut Opened

    public const EDITPUBLISH = 'EXCURSION_EDIT_PUBLISH';
    // Si admin ou organisateur + statut Created

    public const VIEW = 'EXCURSION_VIEW';
    // Si pas org + inProgress ou Finished
    public const SUBSCRIBE = 'EXCURSION_SUB';
    // Si pas org + nbLimit pas atteint + datelimite pas atteinte + Pas participant + opened

    public const UNSUBSCRIBE ='EXCURSION_UNSUB';
    // Si je suis participant et que la date de début de sortie n'est pas passée


    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
//        return in_array($attribute, [self::EDIT, self::VIEW])
//            && $subject instanceof \App\Entity\User;
        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
//        $user = $token->getUser();
//        // if the user is anonymous, do not grant access
//        if (!$user instanceof UserInterface) {
//            return false;
//        }
//
//        // ... (check conditions and return true to grant permission) ...
//        switch ($attribute) {
//            case self::EDIT:
//                // logic to determine if the user can EDIT
//                // return true or false
//                break;
//            case self::VIEW:
//                // logic to determine if the user can VIEW
//                // return true or false
//                break;
//        }

        return false;
    }
}
