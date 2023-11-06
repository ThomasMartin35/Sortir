<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PlaceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        //Retrieve all the data from City
        $cities = $manager->getRepository(City::class)->findAll();

        $place1 = new Place();
        $place1->setName('Thabor');
        $place1->setStreet('Place de la Mairie');
        // Allows you to randomly retrieve data from the database with FAKER
        $place1->setCity($faker->randomElement($cities));
        $place1->setLatitude(48.114384);
        $place1->setLongitude(-1.6694940);
        $manager->persist($place1);

        $place2 = new Place();
        $place2->setName('Opéra');
        $place2->setStreet('Place de l\'opéra');
        $place2->setCity($faker->randomElement($cities));
        $place2->setLatitude(97.114384);
        $place2->setLongitude(-1.065620);
        $manager->persist($place2);

        $place3 = new Place();
        $place3->setName('Oh My Biche');
        $place3->setStreet('Mail François Mitterand');
        $place3->setCity($faker->randomElement($cities));
        $place3->setLatitude(54.114384);
        $place3->setLongitude(-1.456489);
        $manager->persist($place3);

        $manager->flush();

        $this->addReference('place1', $place1);
        $this->addReference('place2', $place2);
        $this->addReference('place3', $place3);
    }



    public function getDependencies(): array
    {
        return [
            CityFixtures::class,
        ];
    }

}