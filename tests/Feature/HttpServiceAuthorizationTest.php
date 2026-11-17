<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\HttpService;
use GuzzleHttp\Client;
use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class HttpServiceAuthorizationTest extends TestCase
{
    public function test_writer_cannot_access_financial_app(): void
    {
        $writer = (new User())->forceFill([
            'is_writer' => true,
            'is_admin' => false,
        ]);
        $this->actingAs($writer);

        $client = Mockery::mock(Client::class);
        $client->shouldNotReceive('request');

        $service = new class($client) extends HttpService {
            public function __construct(Client $client)
            {
                $this->client = $client;
            }
        };

        try {
            $service->getRequest('http://internal.finance:8001/user-data.php');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());

            return;
        }

        $this->fail('Il servizio doveva rifiutare la richiesta con un 403.');
    }
}