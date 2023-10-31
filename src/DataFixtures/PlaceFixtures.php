<?php

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PlaceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $place1 = new Place();
        $place1->setName('Thabor');
        $place1->setStreet('Place de la Mairie');
        //Reprends le addReference de CityFixtures
        $city = $this->getReference('chartres');
        $place1->setCity($city);
        $place1->setLatitude(48.114384);
        $place1->setLongitude(-1.6694940);
        $manager->persist($place1);

        $place2 = new Place();
        $place2->setName('Opéra');
        $place2->setStreet('Place de l\'opéra');
        //Reprends le addReference de CityFixtures
        $city = $this->getReference('chartres');
        $place2->setCity($city);
        $place2->setLatitude(48.114384);
        $place2->setLongitude(-1.6694940);
        $manager->persist($place2);

        $place3 = new Place();
        $place3->setName('Oh My Biche');
        $place3->setStreet('Mail François Mitterand');
        //Reprends le addReference de CityFixtures
        $city = $this->getReference('chartres');
        $place3->setCity($city);
        $place3->setLatitude(48.114384);
        $place3->setLongitude(-1.6694940);
        $manager->persist($place3);

        $manager->flush();

        $this->addReference('place1', $place1);
//        $this->addReference('place2', $place2);
//        $this->addReference('place3', $place3);
    }



    public function getDependencies(): array
    {
        return [
            CityFixtures::class,
        ];
    }

}