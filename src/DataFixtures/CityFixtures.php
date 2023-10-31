<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rennes = new City();
        $rennes->setName('Campus ENI CHARTRES DE BRETAGNE');
        $rennes->setPostcode('35131');
        $manager->persist($rennes);

        $nantes = new City();
        $nantes->setName('Campus ENI SAINT-HERBLAIN');
        $nantes->setPostCode ('44800');
        $manager->persist($nantes);

        $roche = new City();
        $roche->setName('Campus ENI LA ROCHE SUR YON');
        $roche->setPostcode('85000');
        $manager->persist($roche);

        $manager->flush();
    }

}