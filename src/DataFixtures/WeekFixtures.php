<?php

namespace App\DataFixtures;

use App\Factory\WeekFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WeekFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        WeekFactory::new()->createMany(10);
    }
}
