<?php

namespace App\DataFixtures;

use App\Factory\WishFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        WishFactory::createMany(2);
        $manager->flush();
    }
}
