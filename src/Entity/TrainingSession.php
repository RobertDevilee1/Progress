<?php

namespace App\Entity;

use App\Repository\TrainingSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingSessionRepository::class)]
class TrainingSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'trainingSessions')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'trainingSessions')]
    private ?Training $training = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $sets = null;

    #[ORM\Column]
    private ?int $reps = null;

    #[ORM\Column]
    private ?float $weight = null;

    /**
     * @var Collection<int, Trophy>
     */
    #[ORM\OneToMany(targetEntity: Trophy::class, mappedBy: 'trainingSession')]
    private Collection $trophies;

    #[ORM\Column]
    private ?bool $wasPresent = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $plannedDate = null;

    public function __construct()
    {
        $this->trophies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTraining(): ?Training
    {
        return $this->training;
    }

    public function setTraining(?Training $training): static
    {
        $this->training = $training;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSets(): ?int
    {
        return $this->sets;
    }

    public function setSets(int $sets): static
    {
        $this->sets = $sets;

        return $this;
    }

    public function getReps(): ?int
    {
        return $this->reps;
    }

    public function setReps(int $reps): static
    {
        $this->reps = $reps;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return Collection<int, Trophy>
     */
    public function getTrophies(): Collection
    {
        return $this->trophies;
    }

    public function addTrophy(Trophy $trophy): static
    {
        if (!$this->trophies->contains($trophy)) {
            $this->trophies->add($trophy);
            $trophy->setTrainingSession($this);
        }

        return $this;
    }

    public function removeTrophy(Trophy $trophy): static
    {
        if ($this->trophies->removeElement($trophy)) {
            // set the owning side to null (unless already changed)
            if ($trophy->getTrainingSession() === $this) {
                $trophy->setTrainingSession(null);
            }
        }

        return $this;
    }

    public function isWasPresent(): ?bool
    {
        return $this->wasPresent;
    }

    public function setWasPresent(bool $wasPresent): static
    {
        $this->wasPresent = $wasPresent;

        return $this;
    }

    public function getPlannedDate(): ?\DateTimeInterface
    {
        return $this->plannedDate;
    }

    public function setPlannedDate(?\DateTimeInterface $plannedDate): static
    {
        $this->plannedDate = $plannedDate;
        return $this;
    }

}
