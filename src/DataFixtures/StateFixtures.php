<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StateFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $created = new State();
        $created->setCaption('Créée');
        $manager->persist($created);

        $opened = new State();
        $opened->setCaption('Opened');
        $manager->persist($opened);

        $closed = new State();
        $closed->setCaption('Closed');
        $manager->persist($closed);

        $inProgress = new State();
        $inProgress->setCaption('In Progress');
        $manager->persist($inProgress);

        $finished = new State();
        $finished->setCaption('Finished');
        $manager->persist($finished);

        $canceled = new State();
        $canceled->setCaption('Canceled');
        $manager->persist($canceled);

        $manager->flush();
    }

}