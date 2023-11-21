<?php

namespace App\DataFixtures;

use App\Repository\GroupRepository;
use App\Repository\SubjectRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * @ORMFixture(order=2)
 */
class NbGroupFixtures extends Fixture implements DependentFixtureInterface
{
    private GroupRepository $group;
    private SubjectRepository $subject;

    public function __construct(GroupRepository $group, SubjectRepository $subject)
    {
        $this->group = $group;
        $this->subject = $subject;
    }

    public function load(ObjectManager $manager): void
    {
    }

    public function getDependencies(): array
    {
        return [
            SubjectFixtures::class,
        ];
    }
}
