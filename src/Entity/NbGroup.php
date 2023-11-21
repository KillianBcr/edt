<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\NbGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NbGroupRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            paginationClientItemsPerPage: null,
            normalizationContext: ['groups' => ['get_NbGroup']],
        ),
        new GetCollection(
            paginationClientItemsPerPage: null,
        ),
        new Post(
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')"
        ),
    ]
)]
#[ORM\Table(name: 'nbGroups')]
class NbGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_NbGroup', 'set_NbGroup'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['get_NbGroup', 'set_NbGroup'])]
    private ?int $nbGroup = null;

    #[ORM\ManyToMany(targetEntity: Subject::class)]
    #[Groups(['get_NbGroup', 'set_NbGroup'])]
    private Collection $subject;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'nbGroups')]
    #[Groups(['get_NbGroup', 'set_NbGroup'])]
    private Collection $groups;

    public function __construct()
    {
        $this->subject = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbGroup(): ?int
    {
        return $this->nbGroup;
    }

    public function setNbGroup(int $nbGroup): static
    {
        $this->nbGroup = $nbGroup;

        return $this;
    }

    /**
     * @return Collection<int, Subject>
     */
    public function getSubject(): Collection
    {
        return $this->subject;
    }

    public function addSubject(Subject $subject): static
    {
        if (!$this->subject->contains($subject)) {
            $this->subject->add($subject);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        $this->subject->removeElement($subject);

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
        }

        return $this;
    }

    public function removeGroup(Group $group): static
    {
        $this->groups->removeElement($group);

        return $this;
    }
}
