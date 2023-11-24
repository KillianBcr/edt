<?php

namespace App\Tests\Api;

use App\Entity\Group;
use App\Entity\NbGroup;
use App\Entity\Subject;
use App\Tests\HttpClientTrait;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class NbGroupCestTest extends TestCase
{
    use HttpClientTrait;

    protected function setUp(): void
    {
        $this->setUpHttpClient();
    }

    public function testGettersAndSetters()
    {
        $nbGroup = new NbGroup();

        $nbGroup->setNbGroup(5);
        $this->assertEquals(5, $nbGroup->getNbGroup());

        $subject = new Subject();
        $nbGroup->addSubject($subject);
        $this->assertCount(1, $nbGroup->getSubject());
        $nbGroup->removeSubject($subject);
        $this->assertCount(0, $nbGroup->getSubject());

        $group = new Group();
        $nbGroup->addGroup($group);
        $this->assertCount(1, $nbGroup->getGroups());
        $nbGroup->removeGroup($group);
        $this->assertCount(0, $nbGroup->getGroups());
    }

    public function testApiOperations()
    {
        try {
            // POST request
            $response = $this->httpClient->post('nbGroups', [
                'json' => [
                    'nbGroup' => 5,
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());
            $data = json_decode($response->getBody(), true);
            $nbGroupId = $data['id'];

            // GET request
            $response = $this->httpClient->get("nbGroups/{$nbGroupId}");
            $this->assertEquals(401, $response->getStatusCode());

            // PUT request
            $response = $this->httpClient->put("nbGroups/{$nbGroupId}", [
                'json' => [
                    'nbGroup' => 10,
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());

            // PATCH request
            $response = $this->httpClient->patch("nbGroups/{$nbGroupId}", [
                'json' => [
                    'nbGroup' => 8,
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());

            // DELETE request
            $response = $this->httpClient->delete("nbGroups/{$nbGroupId}");
            $this->assertEquals(401, $response->getStatusCode());
        } catch (ClientException $e) {
            // Catch the exception and assert the expected status code
            $response = $e->getResponse();
            $this->assertEquals(401, $response->getStatusCode());
        }
    }
}
