<?php

namespace MelhorEnvio\Tests\Enums;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        $mockHandler = new MockHandler([
            new Response(200, ['Foo' => 'Bar'], 'Hello'),
            new RequestException('Communicating with server failed', new Request('POST', 'https://sandbox.melhorenvio.com.br'))
        ]);

        $handlerStack = HandlerStack::create($mockHandler);

        $httpClient = new Client([
           'handler' => $handlerStack
        ]);

        $this->client = $httpClient;
    }

    /**
     * @test
     */
    public function is_valid_endpoint_production()
    {
        $response = $this->client->post('https://sandbox.melhorenvio.com.br', array(
            'request.options' => array(
                'exceptions' => false
            )
        ));

        $this->assertEquals(200, $response->getStatusCode());
    }
}
