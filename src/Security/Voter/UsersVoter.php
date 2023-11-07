<?php

namespace App\Security\Voter;

use App\Entity\Excursion;
use App\Entity\Member;
use App\Entity\State;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UsersVoter extends Voter
{
    public const EXCURSION_VIEWCANCEL = 'EXCURSION_VIEW_CANCEL';
    // Si admin ou organisateur + statut Opened

    public const EXCURSION_EDITPUBLISH = 'EXCURSION_EDIT_PUBLISH';
    // Si admin ou organisateur + statut Created

    public const EXCURSION_VIEW = 'EXCURSION_VIEW';
    // Si pas org + inProgress ou Finished
    public const EXCURSION_SUBSCRIBE = 'EXCURSION_SUBSCRIBE';
    // Si pas org + nbLimit pas atteint + datelimite pas atteinte + Pas participant + opened

    public const EXCURSION_UNSUBSCRIBE ='EXCURSION_UNSUBSCRIBE';
    // Si je suis participant et que la date de début de sortie n'est pas passée


    protected function supports(string $attribute, mixed $excursion): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EXCURSION_VIEWCANCEL, self::EXCURSION_EDITPUBLISH,
                self::EXCURSION_VIEW, self::EXCURSION_SUBSCRIBE, self::EXCURSION_UNSUBSCRIBE,])
            && $excursion instanceof \App\Entity\Excursion;
    }

    protected function voteOnAttribute(string $attribute, mixed $excursion, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EXCURSION_VIEWCANCEL:
                // logic to determine if the user can VIEW OR CANCEL
                // return true or false
                return $this->canViewOrCancel($excursion, $user);
                break;
            case self::EXCURSION_EDITPUBLISH:
                return $this->canEditAndPublish($excursion, $user);
                break;
            case self::EXCURSION_VIEW:
                return $this->canView($excursion, $user);
                break;
            case self::EXCURSION_SUBSCRIBE:
                return $this->canSubscribe($excursion, $user);
                break;
            case self::EXCURSION_UNSUBSCRIBE:
                return $this->canUnsubscribe($excursion, $user);
                break;
        }

        return false;
    }

    private function canViewOrCancel(Excursion $excursion, Member $user): bool{
        if (($excursion->getState()->getCaption() == 'Opened' &&
            ($user === $excursion->getOrganizer() || $user->isIsAdmin()))){
            return true;
        }
        return false;
    }
    private function canEditAndPublish(Excursion $excursion, Member $user): bool{
        if (($excursion->getState()->getCaption() == 'Created') &&
            ($user === $excursion->getOrganizer()) ){
            return true;
        }
        return false;
    }

    private function canView(Excursion $excursion, Member $user): bool{
        if (($excursion->getState()->getCaption() == 'In Progress'
                || $excursion->getState()->getCaption() == 'Finished')
            && ($user !== $excursion->getOrganizer()) ){
            return true;
        }
        return false;
    }

    private function canSubscribe(Excursion $excursion, Member $user): bool{
        if (($excursion->getState()->getCaption() == 'Opened')
            && ($excursion->getLimitRegistrationDate() > new \DateTime())
            && ($excursion->getMaxRegistrationNumber() < $excursion->getParticipants()->count() )
            && ($user !== $excursion->getOrganizer())
            && (!$excursion->getParticipants()->contains($user)) ){
            return true;
        }
        return false;
    }


    private function canUnsubscribe(Excursion $excursion, Member $user): bool{

        if (($excursion->getStartDate() >= new \DateTime())
            && ($excursion->getParticipants()->contains($user))
            && ($user !== $excursion->getOrganizer())
        ) {
            return true;
        }
            return false;
    }

}
