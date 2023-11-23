<?php

namespace App\Tests\Api;

use App\Entity\Group;
use App\Entity\Subject;
use App\Entity\Wish;
use App\Entity\NbGroup;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class GroupCestTest extends TestCase
{
    private $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = new Client([
            'base_uri' => 'http://127.0.0.1:8000/api/',
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function testGettersAndSetters()
    {
        $group = new Group();

        $group->setType('TestType');
        $this->assertEquals('TestType', $group->getType());

        $subject = new Subject();
        $group->setSubject($subject);
        $this->assertSame($subject, $group->getSubject());

        $wish = new Wish();
        $group->addWish($wish);
        $this->assertCount(1, $group->getWishes());
        $group->removeWish($wish);
        $this->assertCount(0, $group->getWishes());

        $nbGroup = new NbGroup();
        $group->addNbGroup($nbGroup);
        $this->assertCount(1, $group->getNbGroups());
        $group->removeNbGroup($nbGroup);
        $this->assertCount(0, $group->getNbGroups());
    }

    public function testApiOperations()
    {
        $response = $this->httpClient->post('groups', [
            'json' => [
                'type' => 'TestType',
            ],
        ]);

        $this->assertEquals(201, $response->getStatusCode());

        $data = json_decode($response->getBody(), true);
        $groupId = $data['id'];

        $response = $this->httpClient->get("groups/{$groupId}");
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->httpClient->put("groups/{$groupId}", [
            'json' => [
                'type' => 'UpdatedType',
            ],
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->httpClient->patch("groups/{$groupId}", [
            'json' => [
                'type' => 'PatchedType',
            ],
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->httpClient->delete("groups/{$groupId}");
        $this->assertEquals(200, $response->getStatusCode());
    }
}
