<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\SubjectCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SubjectCodeRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['get_SubjectCode']],
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['get_SubjectCode']],
        ),
        new Post(
            security: "is_granted('ROLE_USER')"
        ),
        new Put(
            security: "is_granted('ROLE_USER')"
        ),
        new Patch(
            security: "is_granted('ROLE_USER')"
        ),
        new Delete(
            security: "is_granted('ROLE_USER')"
        ),
    ]
)]
class SubjectCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_SubjectCode', 'get_Subject'])]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    #[Groups(['get_SubjectCode'])]
    private ?string $code = null;

    #[ORM\OneToMany(mappedBy: 'subjectCode', targetEntity: Subject::class)]
    #[Groups(['get_SubjectCode'])]
    private Collection $subjects;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    #[Groups(['get_SubjectCode', 'set_SubjectCode'])]
    public function setCode(string $code): static
    {
        $this->code = $code;

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
            $subject->setSubjectCode($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        if ($this->subjects->removeElement($subject)) {
            // set the owning side to null (unless already changed)
            if ($subject->getSubjectCode() === $this) {
                $subject->setSubjectCode(null);
            }
        }

        return $this;
    }
}
