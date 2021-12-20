<?php
namespace App\Tests\Controller;
namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UploadControllerTest extends WebTestCase
{
    private array $headers = ['HTTP_X_AUTH_TOKEN' => '345689245425098243509'];

    public function test_image_uploaded_and_downloaded(): void
    {

        $client = static::createClient([], $this->headers);
        $client->request('POST', '/upload', ['file' => file_get_contents(__DIR__ . '/../resources/image_ok')]);

        $this->assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent());
        $client->request('GET', $response->url);

        $this->assertResponseIsSuccessful();
    }

    public function test_file_not_found(): void
    {
        $client = static::createClient([], $this->headers);

        $client->request('POST', '/upload');

        $this->assertSame(400, $client->getResponse()->getStatusCode());
        $this->assertSame('{"error":"File not found"}', $client->getResponse()->getContent());
    }

    public function test_invalid_credentials(): void
    {
        $client = static::createClient([], ['HTTP_X_AUTH_TOKEN' => 'fake']);

        $client->request('POST', '/upload');

        $this->assertSame(401, $client->getResponse()->getStatusCode());
        $this->assertSame('{"message":"Invalid credentials."}', $client->getResponse()->getContent());
    }

    public function test_big_image(): void
    {
        $client = static::createClient([], $this->headers);

        $client->request('POST', '/upload', ['file' => file_get_contents(__DIR__ . '/../resources/image_big')]);

        $this->assertSame(400, $client->getResponse()->getStatusCode());
    }
}