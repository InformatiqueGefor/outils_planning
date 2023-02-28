<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $start = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $end = null;

    #[ORM\Column]
    private ?int $theorieHours = null;

    #[ORM\Column]
    private ?int $stageHours = null;

    #[ORM\Column]
    private ?int $totalHours = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    private ?Formation $formation = null;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Stage::class)]
    private Collection $stages;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Conges::class)]
    private Collection $conges;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Examens::class)]
    private Collection $examens;

    #[ORM\Column(nullable: true)]
    private ?int $maxHoursPerDay = null;

    #[ORM\Column(nullable: true)]
    private ?int $minHoursPerDay = null;

    public function __construct()
    {
        $this->stages = new ArrayCollection();
        $this->conges = new ArrayCollection();
        $this->examens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStart(): ?\DateTimeImmutable
    {
        return $this->start;
    }

    public function setStart(?\DateTimeImmutable $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeImmutable
    {
        return $this->end;
    }

    public function setEnd( ?\DateTimeImmutable $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getTheorieHours(): ?int
    {
        return $this->theorieHours;
    }

    public function setTheorieHours(int $theorieHours): self
    {
        $this->theorieHours = $theorieHours;

        return $this;
    }

    public function getStageHours(): ?int
    {
        return $this->stageHours;
    }

    public function setStageHours(int $stageHours): self
    {
        $this->stageHours = $stageHours;

        return $this;
    }

    public function getTotalHours(): ?int
    {
        return $this->totalHours;
    }

    public function setTotalHours(int $totalHours): self
    {
        $this->totalHours = $totalHours;

        return $this;
    }
    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection<int, Stage>
     */
    public function getStages(): Collection
    {
        return $this->stages;
    }

    public function addStage(Stage $stage): self
    {
        if (!$this->stages->contains($stage)) {
            $this->stages->add($stage);
            $stage->setSession($this);
        }

        return $this;
    }

    public function removeStage(Stage $stage): self
    {
        if ($this->stages->removeElement($stage)) {
            // set the owning side to null (unless already changed)
            if ($stage->getSession() === $this) {
                $stage->setSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conges>
     */
    public function getConges(): Collection
    {
        return $this->conges;
    }

    public function addConge(Conges $conge): self
    {
        if (!$this->conges->contains($conge)) {
            $this->conges->add($conge);
            $conge->setSession($this);
        }

        return $this;
    }

    public function removeConge(Conges $conge): self
    {
        if ($this->conges->removeElement($conge)) {
            // set the owning side to null (unless already changed)
            if ($conge->getSession() === $this) {
                $conge->setSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Examens>
     */
    public function getExamens(): Collection
    {
        return $this->examens;
    }

    public function addExamen(Examens $examen): self
    {
        if (!$this->examens->contains($examen)) {
            $this->examens->add($examen);
            $examen->setSession($this);
        }

        return $this;
    }

    public function removeExamen(Examens $examen): self
    {
        if ($this->examens->removeElement($examen)) {
            // set the owning side to null (unless already changed)
            if ($examen->getSession() === $this) {
                $examen->setSession(null);
            }
        }

        return $this;
    }

    public function getMaxHoursPerDay(): ?int
    {
        return $this->maxHoursPerDay;
    }

    public function setMaxHoursPerDay(?int $maxHoursPerDay): self
    {
        $this->maxHoursPerDay = $maxHoursPerDay;

        return $this;
    }

    public function getMinHoursPerDay(): ?int
    {
        return $this->minHoursPerDay;
    }

    public function setMinHoursPerDay(?int $minHoursPerDay): self
    {
        $this->minHoursPerDay = $minHoursPerDay;

        return $this;
    }
}
