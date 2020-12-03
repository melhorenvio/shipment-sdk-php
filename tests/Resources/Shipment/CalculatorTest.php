<?php

namespace MelhorEnvio\Tests\Resources\Shipment;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\InvalidArgumentException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MelhorEnvio\Exceptions\InvalidCalculatorPayloadException;
use MelhorEnvio\Resources\Resource;
use MelhorEnvio\Resources\Shipment\Calculator;
use MelhorEnvio\Resources\Shipment\Product;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        parent::setUp();

        $mock = new MockHandler([
            new Response(200, ['payload'], '[{"id":1,"name":"PAC","price":"27.48","custom_price":"27.48","discount":"0.00","currency":"R$","delivery_time":10,"delivery_range":{"min":9,"max":10},"custom_delivery_time":10,"custom_delivery_range":{"min":9,"max":10},"packages":[{"price":"27.48","discount":"0.00","format":"box","dimensions":{"height":6,"width":11,"length":18},"weight":"0.40","insurance_value":"0.00"}],"additional_services":{"receipt":false,"own_hand":true,"collect":false},"company":{"id":1,"name":"Correios","picture":"https:\/\/logistic.melhorenvio.work\/images\/shipping-companies\/correios.png"}}]'),
            new Response(200, ['payload'], '[{"id":1,"name":"PAC","price":"27.48","custom_price":"27.48","discount":"0.00","currency":"R$","delivery_time":10,"delivery_range":{"min":9,"max":10},"custom_delivery_time":10,"custom_delivery_range":{"min":9,"max":10},"packages":[{"price":"27.48","discount":"0.00","format":"box","dimensions":{"height":6,"width":11,"length":18},"weight":"0.40","insurance_value":"0.00"}],"additional_services":{"receipt":false,"own_hand":true,"collect":false},"company":{"id":1,"name":"Correios","picture":"https:\/\/logistic.melhorenvio.work\/images\/shipping-companies\/correios.png"}}]'),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $httpClient = new Client([
            'handler' => $handlerStack
        ]);

        $this->client = $httpClient;
    }

    /**
     * @test
     */
    public function isValidCalculate()
    {

        $response = $this->client->request('POST', 'me/shipment/calculate');

        $resourceStub = $this->createMock(Resource::class);
        $resourceStub->method('getHttp')->willReturn($this->client);

        $calculator = new Calculator($resourceStub);

        $calculator->postalCode('96010760', '08226021');

        $calculator->addProduct(new Product(uniqid(), 40, 30, 50, 10.00, 100.0, 1));

        $quotation = $calculator->calculate();

        $this->assertEquals(json_encode($quotation), $response->getBody());
    }

    /**
     * @test
     */
    public function isInvalidCalculate()
    {
        $clientStub = $this->createMock(Client::class);
        $clientStub->method('send')->willReturn(function () { throw new \Exception; });

        $resourceStub = $this->createMock(Resource::class);
        $resourceStub->method('getHttp')->willReturn($clientStub);

        $calculator = new Calculator($resourceStub);

        $this->expectException(InvalidCalculatorPayloadException::class);

        $calculator->calculate();
    }
}
