<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

;

class MemberFixtures extends Fixture implements DependentFixtureInterface

{
    const NB_MEMBER = 10;

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $campus = $manager->getRepository(Campus::class)->findAll();

        // création de l'administrateur
        $admin = new Member();
        $admin->setMail('admin@campus-eni.fr');
        $admin->setPseudo('ADMINPseudo');
        $admin->setName('ADMIN');
        $admin->setFirstName('admin');
        $admin->setPhone('0606060606');
        $admin->setIsAdmin(true);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setActive(true);
        $admin->setCampus($faker->randomElement($campus));
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, '123456'));
        $manager->persist($admin);

        //Fixture for SpecialMember
        $specialMember = new Member();
        $specialMember->setMail('sylvain@campus-eni.fr');
        $specialMember->setPseudo('Unicorn35');
        $specialMember->setName('TROPEE');
        $specialMember->setFirstName('Sylvain');
        $specialMember->setPhone('0601020304');
        $specialMember->setIsAdmin(false);
        $specialMember->setRoles(['ROLE_USER']);
        $specialMember->setActive(true);
        $specialMember->setCampus($faker->randomElement($campus));
        $specialMember->setPassword($this->userPasswordHasher->hashPassword($specialMember, '123456'));
        $manager->persist($specialMember);
        $this->addReference('specialMember', $specialMember);


        //TODO Create other fixtures for members with random properties
        for ($i = 1; $i <= self::NB_MEMBER; $i++) {
            $member = new Member();
            $member->setMail('test' . $i . '@campus-eni.fr');
            $member->setPseudo('TESTPseudo' . $i);
            $member->setName('TEST' . $i);
            $member->setFirstName('test' . $i);
            $member->setPhone('0606060606');
            $member->setIsAdmin(false);
            $member->setRoles(['ROLE_USER']);
            $member->setActive($faker->boolean(90));
            $member->setCampus($faker->randomElement($campus));

            $member->setPassword($this->userPasswordHasher->hashPassword($admin, '123456'));
            $manager->persist($member);

            $this->addReference('test' . $i, $member);
        }

        $manager->flush();

        $this->addReference('organizerAdmin', $admin);
        $this->addReference('organizerSpecialMember', $specialMember);

    }

    public function getDependencies()
    {
        return [
            CampusFixtures::class,
        ];
    }
}
