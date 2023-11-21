<?php

namespace App\Tests\Api;

use App\Entity\Group;
use App\Tests\Support\Helper\ApiPlatform;
use Codeception\Util\HttpCode;

class GroupCestTest
{
    public function createGroup(ApiPlatform $apiPlatform): void
    {
        $groupData = [
            'type' => 'TestGroup',
        ];

        // Utilise les fonctions de l'assistance ApiPlatform
        $apiPlatform->sendPost('/api/groups', $groupData);
        $apiPlatform->seeResponseCodeIs(HttpCode::CREATED);
        $apiPlatform->seeResponseIsJson();
        $apiPlatform->seeResponseIsAnEntity(Group::class, '/api/groups');
        $apiPlatform->seeResponseIsAnItem(['id' => 'integer', 'type' => 'string'], ['type' => 'TestGroup']);
    }

    // ...
}
