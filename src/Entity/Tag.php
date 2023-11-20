<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['get_Tag']],
        ),
        new Get(
            normalizationContext: ['groups' => ['get_Tag']],
        ),
        new Post(
            security: ("is_granted('ROLE_ENSEIGNANT') or is_granted('ROLE_ADMIN')")
        ),
        new Delete(
            security: ("is_granted('ROLE_ENSEIGNANT') or is_granted('ROLE_ADMIN')")
            security: "is_granted('ROLE_ADMIN')",
        ),
        new Put(
            normalizationContext: ['groups' => ['get_Tag']],
            denormalizationContext: ['groups' => ['get_Tag']],
            security: ("is_granted('ROLE_ENSEIGNANT') or is_granted('ROLE_ADMIN')")
        ),
        new Patch(
            normalizationContext: ['groups' => ['get_Tag']],
            denormalizationContext: ['groups' => ['get_Tag']],
            security: ("is_granted('ROLE_ENSEIGNANT') or is_granted('ROLE_ADMIN')")
        ),
    ]
)] class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_Subject', 'get_Tag'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_Subject', 'get_Tag'])]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Subject::class, mappedBy: 'tags')]
    #[Groups(['get_Tag'])]
    private Collection $subjects;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $subject->addTag($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        if ($this->subjects->removeElement($subject)) {
            $subject->removeTag($this);
        }

        return $this;
    }
}
