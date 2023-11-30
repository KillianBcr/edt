<?php

namespace App\Tests\Api;

use App\Entity\User;
use App\Tests\HttpClientTrait;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

class UserCestTest extends TestCase
{
    use HttpClientTrait;

    protected function setUp(): void
    {
        $this->setUpHttpClient();
    }

    public function testGettersAndSetters()
    {
        $user = new User();

        // Test setters and getters
        $user->setLogin('testuser');
        $this->assertEquals('testuser', $user->getLogin());

        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $this->assertEquals(['ROLE_USER', 'ROLE_ADMIN'], $user->getRoles());

        $user->setPassword('password123');
        $this->assertEquals('password123', $user->getPassword());

        $user->setFirstname('John');
        $this->assertEquals('John', $user->getFirstname());

        $user->setLastname('Doe');
        $this->assertEquals('Doe', $user->getLastname());

        $user->setPhone('123456789');
        $this->assertEquals('123456789', $user->getPhone());

        $user->setPostalCode('12345');
        $this->assertEquals('12345', $user->getPostalCode());

        $user->setAddress('123 Main St');
        $this->assertEquals('123 Main St', $user->getAddress());

        $user->setCity('City');
        $this->assertEquals('City', $user->getCity());

        $user->setEmail('john.doe@example.com');
        $this->assertEquals('john.doe@example.com', $user->getEmail());

        $user->setMinHours(10);
        $this->assertEquals(10, $user->getMinHours());

        $user->setMaxHours(20);
        $this->assertEquals(20, $user->getMaxHours());
    }

    public function testGetUser()
    {
        try {
            // Perform GET request
            $response = $this->httpClient->get('users/2');

            // Assert the expected status code
            $this->assertEquals(401, $response->getStatusCode());
        } catch (ClientException $e) {
            // Catch the exception and assert the expected status code
            $response = $e->getResponse();
            $this->assertEquals(401, $response->getStatusCode());
        }
    }

    public function testApiOperations()
    {
        try {
            // POST request to create a new user
            $response = $this->httpClient->post('users', [
                'json' => [
                    'login' => 'testuser',
                    'password' => 'password123',
                    // ... provide other required fields
                ],
            ]);


            $this->assertEquals(401, $response->getStatusCode());

            $data = json_decode($response->getBody(), true);
            $userId = $data['id'];

            // GET request
            $response = $this->httpClient->get("users/{$userId}");



            $this->assertEquals(401, $response->getStatusCode());

            // PUT request (modify as needed)
            $response = $this->httpClient->put("users/{$userId}", [
                'json' => [
                    'login' => 'updateduser',
                    // ... update other fields as needed
                ],
            ]);



            $this->assertEquals(401, $response->getStatusCode());

            // PATCH request (modify as needed)
            $response = $this->httpClient->patch("users/{$userId}", [
                'json' => [
                    'firstname' => 'UpdatedFirstName',
                    // ... patch other fields as needed
                ],
            ]);



            $this->assertEquals(401, $response->getStatusCode());

            // DELETE request (modify as needed)
            $response = $this->httpClient->delete("users/{$userId}");



            $this->assertEquals(401, $response->getStatusCode());
        } catch (ClientException $e) {
            // Catch the exception and assert the expected status code


            $response = $e->getResponse();
            $this->assertEquals(401, $response->getStatusCode());
        }
    }
}
