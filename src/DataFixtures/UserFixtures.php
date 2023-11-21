<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(10);
        UserFactory::createOne(['email' => 'root@example.com', 'login' => 'admin', 'password' => 'admin', 'roles' => ['ROLE_ADMIN'], 'firstname' => 'admin', 'lastname' => 'admin']);
        UserFactory::createOne(['email' => 'rootenseignant@example.com', 'login' => 'enseignant', 'password' => 'enseignant', 'roles' => ['ROLE_ENSEIGNANT'], 'firstname' => 'enseignant', 'lastname' => 'enseignant']);
        UserFactory::createOne(['email' => 'rootetudiant@example.com', 'login' => 'etudiant', 'password' => 'etudiant', 'roles' => ['ROLE_ETUDIANT'], 'firstname' => 'etudiant', 'lastname' => 'etudiant']);
        UserFactory::createMany(50,['roles' => ['ROLE_ENSEIGNANT']]);
    }
}
