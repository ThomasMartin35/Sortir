<?php

namespace App\DataFixtures;

use App\Entity\Excursion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ExcursionsFixtures extends Fixture
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
            $excursion->setLimitRegistrationDate($faker->dateTimeInInterval('$dateStart', '+2 months'));
            $excursion->setMaxRegistrationNumber(mt_rand(1, 30));
            $excursion->setDescription($faker->sentence(8));
            //TO DO Handle State
            $manager->persist($excursion);
        }

        $manager->flush();
    }
}
