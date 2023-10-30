<?php

namespace App\DataFixtures;

use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

;

class MemberFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        // création de l'administrateur
        $admin = new Member();
        $admin->setMail()('admin@test.fr');
        $admin->setName('ADMIN');
        $admin->setFirstName(['admin']);
        $admin->setPhone('0606060606');
        $admin->setIsAdmin('true');
        $admin->setRoles('ROLE_ADMIN');
        $admin->setActive('true');
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, '123456'));
        $manager->persist($admin);

        //TODO Création d'autres utilisateurs.

            $manager->flush();
        }
    }
