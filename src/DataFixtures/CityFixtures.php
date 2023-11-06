<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $chartres = new City();
        $chartres->setName('CHARTRES DE BRETAGNE');
        $chartres->setPostcode('35131');
        $manager->persist($chartres);

        $nantes = new City();
        $nantes->setName('SAINT-HERBLAIN');
        $nantes->setPostCode ('44800');
        $manager->persist($nantes);

        $roche = new City();
        $roche->setName('LA ROCHE SUR YON');
        $roche->setPostcode('85000');
        $manager->persist($roche);

        $rennes = new City();
        $rennes->setName('RENNES');
        $rennes->setPostcode('35000');
        $manager->persist($rennes);

        $manager->flush();

    }

}