<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Tests\Fixtures\Metadata\Get;
use App\Repository\YearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: YearRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Put(
            normalizationContext: ['groups' => ['get_Year']],
            denormalizationContext: ['groups' => ['set_Year']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            normalizationContext: ['groups' => ['get_Year']],
            denormalizationContext: ['groups' => ['set_Year']],
            security: "is_granted('ROLE_ADMIN')"
        ),
    ]
)]
#[ORM\Table(name: 'years')]
class Year
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_Year'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['get_Year'])]
    private ?int $startYear = null;

    #[ORM\Column]
    #[Groups(['get_Year'])]
    private ?int $endYear = null;

    #[ORM\Column(length: 20)]
    #[Groups(['get_Year', 'get_Subject'])]
    private ?string $academicYear = null;

    #[ORM\OneToMany(mappedBy: 'academicYear', targetEntity: Subject::class)]
    private Collection $subjects;

    #[ORM\Column(nullable: true)]
    #[Groups(['get_Year', 'get_Semester', 'get_Subject'])]
    private ?bool $currentYear = null;

    #[ORM\OneToMany(mappedBy: 'year', targetEntity: Wish::class)]
    private Collection $wishes;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->wishes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartYear(): ?int
    {
        return $this->startYear;
    }

    #[Groups(['get_Year', 'set_Year'])]
    public function setStartYear(int $startYear): static
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    #[Groups(['get_Year', 'set_Year'])]
    public function setEndYear(int $endYear): static
    {
        $this->endYear = $endYear;

        return $this;
    }

    public function getAcademicYear(): ?string
    {
        return $this->academicYear;
    }

    #[Groups(['get_Year', 'set_Year'])]
    public function setAcademicYear(string $academicYear): static
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    /**
     * @return Collection<int, Subject>
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): static
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects->add($subject);
            $subject->setAcademicYear($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        if ($this->subjects->removeElement($subject)) {
            // set the owning side to null (unless already changed)
            if ($subject->getAcademicYear() === $this) {
                $subject->setAcademicYear(null);
            }
        }

        return $this;
    }

    public function isCurrentYear(): ?bool
    {
        return $this->currentYear;
    }

    public function setCurrentYear(?bool $currentYear): static
    {
        $this->currentYear = $currentYear;

        return $this;
    }

    /**
     * @return Collection<int, Wish>
     */
    public function getWishes(): Collection
    {
        return $this->wishes;
    }

    public function addWish(Wish $wish): static
    {
        if (!$this->wishes->contains($wish)) {
            $this->wishes->add($wish);
            $wish->setYear($this);
        }

        return $this;
    }

    public function removeWish(Wish $wish): static
    {
        if ($this->wishes->removeElement($wish)) {
            // set the owning side to null (unless already changed)
            if ($wish->getYear() === $this) {
                $wish->setYear(null);
            }
        }

        return $this;
    }
}
