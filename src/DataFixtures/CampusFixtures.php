<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $rennes = new Campus();
        $rennes->setName('Campus ENI CHARTRES DE BRETAGNE');
        $manager->persist($rennes);

        $nantes = new Campus();
        $nantes->setName('Campus ENI SAINT-HERBLAIN');
        $manager->persist($nantes);

        $roche = new Campus();
        $roche->setName('Campus ENI LA ROCHE SUR YON');
        $manager->persist($roche);

        $manager->flush();
    }

}