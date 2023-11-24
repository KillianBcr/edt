<?php

namespace App\Tests\Api;

use App\Entity\Semester;
use App\Tests\HttpClientTrait;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class SemesterCestTest extends TestCase
{
    use HttpClientTrait;

    protected function setUp(): void
    {
        $this->setUpHttpClient();
    }

    public function testGettersAndSetters()
    {
        $semester = new Semester();

        $semester->setName('TestSemester');
        $this->assertEquals('TestSemester', $semester->getName());

        $startDate = new \DateTime('2023-01-01');
        $semester->setStartDate($startDate);
        $this->assertSame($startDate, $semester->getStartDate());

        $endDate = new \DateTime('2023-06-30');
        $semester->setEndDate($endDate);
        $this->assertSame($endDate, $semester->getEndDate());
    }

    public function testApiOperations()
    {
        try {
            // POST request
            $response = $this->httpClient->post('semesters', [
                'json' => [
                    'name' => 'TestSemester',
                    'startDate' => '2023-01-01',
                    'endDate' => '2023-06-30',
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());
            $data = json_decode($response->getBody(), true);
            $semesterId = $data['id'];

            // GET request
            $response = $this->httpClient->get("semesters/{$semesterId}");
            $this->assertEquals(401, $response->getStatusCode());

            // PUT request
            $response = $this->httpClient->put("semesters/{$semesterId}", [
                'json' => [
                    'name' => 'UpdatedTestSemester',
                    'startDate' => '2023-01-01',
                    'endDate' => '2023-06-30',
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());

            // PATCH request
            $response = $this->httpClient->patch("semesters/{$semesterId}", [
                'json' => [
                    'name' => 'PatchedTestSemester',
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());

            // DELETE request
            $response = $this->httpClient->delete("semesters/{$semesterId}");
            $this->assertEquals(401, $response->getStatusCode());
        } catch (ClientException $e) {
            // Catch the exception and assert the expected status code
            $response = $e->getResponse();
            $this->assertEquals(401, $response->getStatusCode());
        }
    }
}
