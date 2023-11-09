<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Excursion;
use App\Entity\Member;
use App\Entity\Place;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExcursionsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $campus = $manager->getRepository(Campus::class)->findAll();
        $members = $manager->getRepository(Member::class)->findAll();
        $places = $manager->getRepository(Place::class)->findAll();

        //Fixture for example : AquaPoney
        $specialExcursion = new Excursion();
        $specialExcursion->setName('AquaPoney');
        $organizerSpecialMember = $this->getReference('organizerSpecialMember');
        $specialExcursion->setOrganizer($organizerSpecialMember);
        $stateSpecialMember = $this->getReference('opened');
        $specialExcursion->setState($stateSpecialMember);
        $specialExcursion->setCampus($faker->randomElement($campus));
        $specialExcursion->setDescription('Venez nombreux pour une séance d\'Aqua Poney à UnicornLand');

        $specialExcursion->addParticipant($organizerSpecialMember);
        $this->addParticipant($specialExcursion);

        $startDatespecialExcursion = new DateTime();
        $startDatespecialExcursion->modify('+1 month');
        $specialExcursion->setStartDate($startDatespecialExcursion);
        $specialExcursion->setDuration(mt_rand(30, 240));
        $endDatespecialExcursion = (clone $startDatespecialExcursion)->modify('-2 days');
        $specialExcursion->setLimitRegistrationDate($endDatespecialExcursion);
        $specialExcursion->setMaxRegistrationNumber(mt_rand(2, 10));
        $placeSpecialExcursion = $this->getReference('place1');
        $specialExcursion->setPlace($placeSpecialExcursion);

        $manager->persist($specialExcursion);

        for ($i = 1; $i <= 15; $i++) {
            $excursion = new Excursion();
            $excursion->setName($faker->word());
            $dateStart = $faker->dateTimeBetween('-2 months', '+2 months');
            $excursion->setStartDate($dateStart);
            $excursion->setDuration(mt_rand(30, 240));
            $excursion->setLimitRegistrationDate($faker->dateTimeInInterval($dateStart, '-2 days'));
            $excursion->setMaxRegistrationNumber(mt_rand(2, 30));
            $excursion->setDescription($faker->sentence(8));
            $organizerMember = $faker->randomElement($members);
            $excursion->setOrganizer($organizerMember);
            $excursion->addParticipant($organizerMember);
            $excursion->setCampus($faker->randomElement($campus));
            //Fixture with addReference to State
            $state = $this->getReference('opened');
            $excursion->setState($state);
            $excursion->setPlace($faker->randomElement($places));
            //Condition for our SpecialMember (30% to be participant of an excursion).
            if (mt_rand(1, 100) <= 30) {
                $this->addParticipantSpecialMember($excursion);
            }

            $manager->persist($excursion);
        }

        $manager->flush();
    }

    public function addParticipant(Excursion $excursion): void {
        for ($i = 0; $i <= mt_rand(0,1); $i++) {
            $participant = $this->getReference('test' . rand (1,10));
            $excursion->addParticipant($participant);
        }
    }
    public function addParticipantSpecialMember(Excursion $excursion):void {
        $specialMember = $this->getReference('specialMember');
        $excursion->addParticipant($specialMember);
    }

    public function getDependencies(): array
    {
        return [
            PlaceFixtures::class,
            StateFixtures::class,
            MemberFixtures::class,
            CampusFixtures::class,
        ];
    }

}
