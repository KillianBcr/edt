<?php

namespace App\Tests\Api;

use App\Entity\Semester;
use App\Entity\Subject;
use App\Entity\SubjectCode;
use App\Tests\HttpClientTrait;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class SubjectCestTest extends TestCase
{
    use HttpClientTrait;

    protected function setUp(): void
    {
        $this->setUpHttpClient();
    }

    public function testGettersAndSetters()
    {
        $subject = new Subject();

        $subject->setName('TestSubject');
        $this->assertEquals('TestSubject', $subject->getName());

        $subject->setFirstWeek(1);
        $this->assertEquals(1, $subject->getFirstWeek());

        $subject->setLastWeek(10);
        $this->assertEquals(10, $subject->getLastWeek());

        $subjectCode = new SubjectCode(); // Assuming SubjectCode class exists
        $subject->setSubjectCode($subjectCode);
        $this->assertSame($subjectCode, $subject->getSubjectCode());

        $semester = new Semester(); // Assuming Semester class exists
        $subject->setSemester($semester);
        $this->assertSame($semester, $subject->getSemester());
    }

    public function testApiOperations()
    {
        try {
            // POST request
            $response = $this->httpClient->post('subjects', [
                'json' => [
                    'name' => 'TestSubject',
                    'firstWeek' => 1,
                    'lastWeek' => 10, ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());
            $data = json_decode($response->getBody(), true);
            $subjectId = $data['id'];

            // GET request
            $response = $this->httpClient->get("subjects/{$subjectId}");
            $this->assertEquals(200, $response->getStatusCode());

            // PUT request
            $response = $this->httpClient->put("subjects/{$subjectId}", [
                'json' => [
                    'name' => 'UpdatedTestSubject',
                    'firstWeek' => 1,
                    'lastWeek' => 10,
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());

            // PATCH request
            $response = $this->httpClient->patch("subjects/{$subjectId}", [
                'json' => [
                    'name' => 'PatchedTestSubject',
                ],
            ]);
            $this->assertEquals(401, $response->getStatusCode());

            // DELETE request
            $response = $this->httpClient->delete("subjects/{$subjectId}");
            $this->assertEquals(401, $response->getStatusCode());
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $this->assertEquals(401, $response->getStatusCode());
        }
    }
}
