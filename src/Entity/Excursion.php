<?php

namespace App\Entity;

use App\Repository\ExcursionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExcursionRepository::class)]

class Excursion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un titre')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message :'Veuillez renseigner une date')]
    #[Assert\GreaterThan("today", message: 'La date doit être supérieure à aujourd\'hui.')]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner la durée de l\'activité')]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner une date')]
    #[Assert\LessThan(propertyPath: 'startDate', message: 'La date de fin d\'inscription doit être inférieure à la date de la sortie')]
    private ?\DateTimeInterface $limitRegistrationDate = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un nombre maximum de participants')]
    private ?int $maxRegistrationNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez renseigner une description')]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Member::class, inversedBy: 'excursions')]
    private Collection $participants;

    #[ORM\ManyToOne(inversedBy: 'organizedExcursions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Member $organizer = null;

    #[ORM\ManyToOne(inversedBy: 'excursions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez renseigner ce champs')]
    private ?Campus $campus = null;

    #[ORM\ManyToOne(inversedBy: 'excursions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?State $state = null;

    #[ORM\ManyToOne(inversedBy: 'excursions')]
    #[Assert\NotBlank(message: 'Veuillez renseigner un lieu')]
    private ?Place $place = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Reason = null;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLimitRegistrationDate(): ?\DateTimeInterface
    {
        return $this->limitRegistrationDate;
    }

    public function setLimitRegistrationDate(?\DateTimeInterface $limitRegistrationDate): static
    {
        $this->limitRegistrationDate = $limitRegistrationDate;

        return $this;
    }

    public function getMaxRegistrationNumber(): ?int
    {
        return $this->maxRegistrationNumber;
    }

    public function setMaxRegistrationNumber(?int $maxRegistrationNumber): static
    {
        $this->maxRegistrationNumber = $maxRegistrationNumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Member>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Member $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(Member $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function getOrganizer(): ?Member
    {
        return $this->organizer;
    }

    public function setOrganizer(?Member $organizer): static
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->Reason;
    }

    public function setReason(?string $Reason): static
    {
        $this->Reason = $Reason;

        return $this;
    }
}
