<?php

namespace App\Tests\Api;

use App\Entity\Group;
use App\Entity\Subject;
use App\Entity\Wish;
use App\Entity\NbGroup;
use PHPUnit\Framework\TestCase;

class GroupCestTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $group = new Group();

        // Test setType and getType
        $group->setType('TestType');
        $this->assertEquals('TestType', $group->getType());

        // Test setSubject and getSubject
        $subject = new Subject();
        $group->setSubject($subject);
        $this->assertSame($subject, $group->getSubject());

        // Test addWish, removeWish, and getWishes
        $wish = new Wish();
        $group->addWish($wish);
        $this->assertCount(1, $group->getWishes());
        $group->removeWish($wish);
        $this->assertCount(0, $group->getWishes());

        // Test addNbGroup, removeNbGroup, and getNbGroups
        $nbGroup = new NbGroup();
        $group->addNbGroup($nbGroup);
        $this->assertCount(1, $group->getNbGroups());
        $group->removeNbGroup($nbGroup);
        $this->assertCount(0, $group->getNbGroups());
    }
}
