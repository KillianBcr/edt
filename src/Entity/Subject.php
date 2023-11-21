<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['get_Subject']],
        ),
        new Get(
            normalizationContext: ['groups' => ['get_Subject']],
        ),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Put(
            normalizationContext: ['groups' => ['get_Subject']],
            denormalizationContext: ['groups' => ['set_Subject']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            normalizationContext: ['groups' => ['get_Subject']],
            denormalizationContext: ['groups' => ['set_Subject']],
            security: "is_granted('ROLE_ADMIN')"
        ),
    ]
)]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_Subject', 'get_Semester', 'get_SubjectCode', 'get_Tag'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['get_Subject', 'get_Semester', 'get_SubjectCode', 'get_Tag'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['get_Subject', 'get_Semester', 'get_SubjectCode', 'get_Tag'])]
    private ?int $firstWeek = null;

    #[ORM\Column]
    #[Groups(['get_Subject', 'get_Semester', 'get_SubjectCode', 'get_Tag'])]
    private ?int $lastWeek = null;

    #[ORM\ManyToOne(inversedBy: 'subjects')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['get_Subject', 'get_Semester', 'get_SubjectCode', 'get_Tag'])]
    private ?Semester $semester = null;

    #[ORM\ManyToMany(targetEntity: NbGroup::class, inversedBy: 'subjects')]
    private Collection $idNbGroup;

    #[ORM\OneToMany(mappedBy: 'subject', targetEntity: Group::class, cascade: ['remove'])]
    private Collection $groups;

    #[ORM\ManyToMany(targetEntity: Week::class, mappedBy: 'subject')]
    private Collection $weeks;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'subjects')]
    #[Groups(['get_Subject', 'get_Semester'])]
    private Collection $tags;

    #[ORM\ManyToOne(inversedBy: 'subjects')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_Subject', 'get_Semester', 'get_SubjectCode', 'get_Tag'])]
    private ?SubjectCode $subjectCode = null;

    #[ORM\ManyToOne(inversedBy: 'subjects')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_Subject', 'get_Semester', 'get_SubjectCode', 'get_Year'])]
    private ?Year $academicYear = null;

    public function __construct()
    {
        $this->idNbGroup = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->weeks = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    #[Groups(['get_Subject', 'set_Subject'])]
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstWeek(): ?int
    {
        return $this->firstWeek;
    }

    #[Groups(['get_Subject', 'set_Subject'])]
    public function setFirstWeek(int $firstWeek): static
    {
        $this->firstWeek = $firstWeek;

        return $this;
    }

    public function getLastWeek(): ?int
    {
        return $this->lastWeek;
    }

    #[Groups(['get_Subject', 'set_Subject'])]
    public function setLastWeek(int $lastWeek): static
    {
        $this->lastWeek = $lastWeek;

        return $this;
    }

    public function getSubjectCode(): ?SubjectCode
    {
        return $this->subjectCode;
    }

    #[Groups(['get_Subject', 'set_Subject'])]
    public function setSubjectCode(SubjectCode $subjectCode): static
    {
        $this->subjectCode = $subjectCode;

        return $this;
    }

    public function getSemester(): ?Semester
    {
        return $this->semester;
    }

    public function setSemester(?Semester $semester): static
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): static
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->setSubject($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): static
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getSubject() === $this) {
                $group->setSubject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Week>
     */
    public function getWeeks(): Collection
    {
        return $this->weeks;
    }

    public function addWeek(Week $week): static
    {
        if (!$this->weeks->contains($week)) {
            $this->weeks->add($week);
            $week->addSubject($this);
        }

        return $this;
    }

    public function removeWeek(Week $week): static
    {
        if ($this->weeks->removeElement($week)) {
            $week->removeSubject($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, NbGroup>
     */
    public function getIdNbGroup(): Collection
    {
        return $this->idNbGroup;
    }

    public function addIdNbGroup(NbGroup $idNbGroup): static
    {
        if (!$this->idNbGroup->contains($idNbGroup)) {
            $this->idNbGroup->add($idNbGroup);
        }

        return $this;
    }

    public function removeIdNbGroup(NbGroup $idNbGroup): static
    {
        $this->idNbGroup->removeElement($idNbGroup);

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getAcademicYear(): ?Year
    {
        return $this->academicYear;
    }

    public function setAcademicYear(?Year $academicYear): static
    {
        $this->academicYear = $academicYear;

        return $this;
    }
}
