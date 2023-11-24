<?php

namespace App\Tests\Api;

use App\Entity\Period;
use App\Tests\HttpClientTrait;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class PeriodCestTest extends TestCase
{
    use HttpClientTrait;

    protected function setUp(): void
    {
        $this->setUpHttpClient();
    }

    public function testGettersAndSetters()
    {
        $period = new Period();

        $period->setName('TestPeriod');
        $this->assertEquals('TestPeriod', $period->getName());

        $period->setDescription('TestDescription');
        $this->assertEquals('TestDescription', $period->getDescription());

        $weekStart = new \DateTime('2023-01-01');
        $period->setWeekStart($weekStart);
        $this->assertSame($weekStart, $period->getWeekStart());

        $weekEnd = new \DateTime('2023-01-07');
        $period->setWeekEnd($weekEnd);
        $this->assertSame($weekEnd, $period->getWeekEnd());
    }

    public function testApiOperations()
    {
        try {
            // POST request
            $response = $this->httpClient->post('periods', [
                'json' => [
                    'name' => 'TestPeriod',
                    'description' => 'TestDescription',
                    'weekStart' => '2023-01-01',
                    'weekEnd' => '2023-01-07',
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());
            $data = json_decode($response->getBody(), true);
            $periodId = $data['id'];

            // GET request
            $response = $this->httpClient->get("periods/{$periodId}");
            $this->assertEquals(401, $response->getStatusCode());

            // PUT request
            $response = $this->httpClient->put("periods/{$periodId}", [
                'json' => [
                    'name' => 'UpdatedTestPeriod',
                    'description' => 'UpdatedTestDescription',
                    'weekStart' => '2023-01-01',
                    'weekEnd' => '2023-01-07',
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());

            // PATCH request
            $response = $this->httpClient->patch("periods/{$periodId}", [
                'json' => [
                    'name' => 'PatchedTestPeriod',
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());

            // DELETE request
            $response = $this->httpClient->delete("periods/{$periodId}");
            $this->assertEquals(401, $response->getStatusCode());
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $this->assertEquals(401, $response->getStatusCode());
        }
    }
}
