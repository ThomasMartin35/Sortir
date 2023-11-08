<?php

namespace App\Services;

use App\Repository\ExcursionRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;

class StateManager
{
    public function __construct(private readonly EntityManagerInterface $em,
                                private ExcursionRepository $excursionRepository,
                                private StateRepository $stateRepository) {
    }

    public function checkExcursionState(): void
    {
        $currentDate = new \DateTime();

        $excursions = $this->excursionRepository->findAll();

        $opened = $this->stateRepository->findOneBy(['caption' => 'Opened']);
        $closed = $this->stateRepository->findOneBy(['caption' => 'Closed']);
        $inProgress = $this->stateRepository->findOneBy(['caption' => 'In Progress']);
        $finished = $this->stateRepository->findOneBy(['caption' => 'Finished']);
        $archived = $this->stateRepository->findOneBy(['caption' => 'Archived']);

        foreach ($excursions as $excursion) {
            $startDate = $excursion->getStartDate();
            $limitDate = $excursion->getLimitRegistrationDate();
            $duration = $excursion->getDuration();

            $endDate = clone $startDate;
            $endDate->modify('+' . $duration . 'minute');

            $archivedDate = clone $endDate;
            $archivedDate->modify('+' . 1 . 'month');

            if ($excursion->getState()->getCaption() != 'Created'
                && $excursion->getState()->getCaption() != 'Canceled') {
                if ($currentDate < $limitDate &&
                    (count($excursion->getParticipants())) < $excursion->getMaxRegistrationNumber()) {
                    $excursion->setState($opened);
                } elseif ($currentDate < $startDate ||
                    (count($excursion->getParticipants())) == $excursion->getMaxRegistrationNumber()) {
                    $excursion->setState($closed);
                } elseif ($currentDate < $endDate) {
                    $excursion->setState($inProgress);
                } elseif ($currentDate >= $archivedDate) {
                    $excursion->setState($archived);
                } else {
                    $excursion->setState($finished);
                }
                $this-> em-> persist($excursion);
                $this -> em-> flush();
            }
        }

    }


}