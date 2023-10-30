<?php

namespace App\DataFixtures;

use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

;

class MemberFixtures extends Fixture
{
    const NB_MEMBER = 10;
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {

        // création de l'administrateur
        $admin = new Member();
        $admin->setMail('admin@campus-eni.fr');
        $admin->setName('ADMIN');
        $admin->setFirstName('admin');
        $admin->setPhone('0606060606');
        $admin->setIsAdmin(true);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setActive(true);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, '123456'));
        $manager->persist($admin);

        //TODO Création d'autres utilisateurs.
        for ($i = 1 ; $i <= self::NB_MEMBER; $i++) {
            $member = new Member();
            $member->setMail('test'.$i.'@campus-eni.fr');
            $member->setName('TEST'.$i);
            $member->setFirstName('test'.$i);
            $member->setPhone('0606060606');
            $member->setIsAdmin(false);
            $member->setRoles(['ROLE_USER']);
            $member->setActive(true);
            $member->setPassword($this->userPasswordHasher->hashPassword($admin, '123456'));
            $manager->persist($member);
        }
            $manager->flush();
        }
    }
