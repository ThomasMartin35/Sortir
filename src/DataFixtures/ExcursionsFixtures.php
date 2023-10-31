<?php

namespace App\DataFixtures;

use App\Entity\Excursion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExcursionsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 15; $i++) {
            $excursion = new Excursion();
            $excursion->setName($faker->word());
            $dateStart = $faker->dateTimeBetween('-2 months', '+2 months');
            $excursion->setStartDate($dateStart);
            $excursion->setDuration(mt_rand(30, 240));
            $excursion->setLimitRegistrationDate($faker->dateTimeInInterval($dateStart, '+2 months'));
            $excursion->setMaxRegistrationNumber(mt_rand(1, 30));
            $excursion->setDescription($faker->sentence(8));

            //Fixture reprenant le addReference de MemberFixtures
            $organizer = $this->getReference('organizerAdmin');
            $excursion->setOrganizer($organizer);
            //Fixture reprenant le addReference de CampusFixtures
            $campus = $this->getReference('campusRennes');
            $excursion->setCampus($campus);
            //Fixture reprenant le addReference de StateFixtures
            $state = $this->getReference('created');
            $excursion->setState($state);
            //Fixture reprenant le addReference de PlaceFixtures
            $place = $this->getReference('place1');
            $excursion->setPlace($place);

            $manager->persist($excursion);
        }

        $manager->flush();
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
