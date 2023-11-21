<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Tests\Fixtures\Metadata\Get;
use App\Repository\YearRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: YearRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
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
    #[Groups(['year:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['year:read', 'year:write'])]
    private ?string $season = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): ?string
    {
        return $this->season;
    }

    #[Groups(['year:write'])]
    public function setSeason(string $season): static
    {
        $this->season = $season;

        return $this;
    }
}
