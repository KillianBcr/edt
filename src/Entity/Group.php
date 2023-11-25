<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Tests\Fixtures\Metadata\Get;
use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            security: "is_granted('ROLE_ADMIN')",
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
        new Put(
            normalizationContext: ['groups' => ['get_Group']],
            denormalizationContext: ['groups' => ['set_Group']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            normalizationContext: ['groups' => ['get_Group']],
            denormalizationContext: ['groups' => ['set_Group']],
            security: "is_granted('ROLE_ADMIN')"
        ),
    ]
)]
#[ORM\Table(name: 'groups')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['group:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['group:read', 'group:write'])]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'groupeType', targetEntity: Wish::class)]
    private Collection $wishes;

    public function __construct()
    {
        $this->wishes = new ArrayCollection();
        $this->nbGroups = new ArrayCollection();
    }

    #[ORM\ManyToOne(inversedBy: 'groups')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['group:read'])]
    private ?Subject $subject = null;

    #[ORM\Column]
    private ?int $hourlyRate = null;

    #[ORM\ManyToMany(targetEntity: NbGroup::class, mappedBy: 'groups')]
    private Collection $nbGroups;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    #[Groups(['group:write'])]
    public function setType(string $type): static
    {
        $this->type = $type;

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
            $wish->setGroupeType($this);
        }

        return $this;
    }

    public function removeWish(Wish $wish): static
    {
        if ($this->wishes->removeElement($wish)) {
            if ($wish->getGroupeType() === $this) {
                $wish->setGroupeType(null);
            }
        }

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getHourlyRate(): ?int
    {
        return $this->hourlyRate;
    }

    public function setHourlyRate(int $hourlyRate): static
    {
        $this->hourlyRate = $hourlyRate;

        return $this;
    }

    /**
     * @return Collection<int, NbGroup>
     */
    public function getNbGroups(): Collection
    {
        return $this->nbGroups;
    }

    public function addNbGroup(NbGroup $nbGroup): static
    {
        if (!$this->nbGroups->contains($nbGroup)) {
            $this->nbGroups->add($nbGroup);
            $nbGroup->addGroup($this);
        }

        return $this;
    }

    public function removeNbGroup(NbGroup $nbGroup): static
    {
        if ($this->nbGroups->removeElement($nbGroup)) {
            $nbGroup->removeGroup($this);
        }

        return $this;
    }
}
