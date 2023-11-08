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
        $created->setCaption('Created');
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

        $archived = new State();
        $archived->setCaption('Archived');
        $manager->persist($archived);

        $canceled = new State();
        $canceled->setCaption('Canceled');
        $manager->persist($canceled);


        $manager->flush();

        $this->addReference('created', $created);
        $this->addReference('opened', $opened);
        $this->addReference('closed', $closed);
        $this->addReference('inProgress', $inProgress);
        $this->addReference('finished', $finished);
        $this->addReference('archived', $archived);
        $this->addReference('canceled', $canceled);

    }

}