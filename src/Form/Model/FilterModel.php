<?php

namespace App\Form\Model;

use App\Entity\Campus;
use phpDocumentor\Reflection\Types\Boolean;



class FilterModel
{
    private Campus $selectedCampus;
    private string $selectedWords;
    private \DateTime $selectedStartDate;
    private \DateTime $selectedEndDate;
    private Boolean $isOrganizer;
    private Boolean $isRegistred;
    private Boolean $isNotRegistred;
    private Boolean $isFinished;


    public function __construct()
    {
        $this->selectedWords = '';

    }

    public function getSelectedCampus(): Campus
    {
        return $this->selectedCampus;
    }

    public function setSelectedCampus(Campus $selectedCampus): void
    {
        $this->selectedCampus = $selectedCampus;
    }

    public function getSelectedWords(): string
    {
        return $this->selectedWords;
    }

    public function setSelectedWords(string $selectedWords): void
    {
        $this->selectedWords = $selectedWords;
    }

    public function getSelectedStartDate(): \DateTime
    {
        return $this->selectedStartDate;
    }

    public function setSelectedStartDate(\DateTime $selectedStartDate): void
    {
        $this->selectedStartDate = $selectedStartDate;
    }

    public function getSelectedEndDate(): \DateTime
    {
        return $this->selectedEndDate;
    }

    public function setSelectedEndDate(\DateTime $selectedEndDate): void
    {
        $this->selectedEndDate = $selectedEndDate;
    }

    public function isOrganizer(): bool
    {
        return $this->isOrganizer;
    }

    public function setIsOrganizer(bool $isOrganizer): void
    {
        $this->isOrganizer = $isOrganizer;
    }

    public function isRegistred(): bool
    {
        return $this->isRegistred;
    }

    public function setIsRegistred(bool $isRegistred): void
    {
        $this->isRegistred = $isRegistred;
    }

    public function isNotRegistred(): bool
    {
        return $this->isNotRegistred;
    }

    public function setIsNotRegistred(bool $isNotRegistred): void
    {
        $this->isNotRegistred = $isNotRegistred;
    }

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    public function setIsFinished(bool $isFinished): void
    {
        $this->isFinished = $isFinished;
    }


}