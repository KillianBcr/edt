<?php

namespace App\Tests\Api;

use App\Entity\Group;
use App\Entity\NbGroup;
use App\Entity\Subject;
use App\Entity\Wish;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
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
        try {
            // POST request
            $response = $this->httpClient->post('groups', [
                'json' => [
                    'type' => 'TestType',
                ],
                'headers' => [
                    'Authorization' => '',
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());
            $data = json_decode($response->getBody(), true);
            $groupId = $data['id'];

            // GET request
            $response = $this->httpClient->get("groups/{$groupId}");
            $this->assertEquals(401, $response->getStatusCode());

            // PUT request
            $response = $this->httpClient->put("groups/{$groupId}", [
                'json' => [
                    'type' => 'UpdatedType',
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());

            // PATCH request
            $response = $this->httpClient->patch("groups/{$groupId}", [
                'json' => [
                    'type' => 'PatchedType',
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());

            // DELETE request
            $response = $this->httpClient->delete("groups/{$groupId}");
            $this->assertEquals(401, $response->getStatusCode());

        } catch (ClientException $e) {
            // Catch the exception and assert the expected status code
            $response = $e->getResponse();
            $this->assertEquals(401, $response->getStatusCode());
        }
    }
}
