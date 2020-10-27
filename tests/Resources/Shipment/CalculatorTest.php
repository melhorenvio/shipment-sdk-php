<?php

namespace MelhorEnvio\Tests\Resources\Shipment;

use GuzzleHttp\ClientInterface;
use MelhorEnvio\Resources\Resource;
use MelhorEnvio\Resources\Shipment\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function is_valid_calculate()
    {
        $response = $this->createMock(Calculator::class);
        $response->method('getPayload');

        $clientStub = $this->createMock(ClientInterface::class);
        $clientStub->method('send')->willReturn($response);

        $resourceStub = $this->createMock(Resource::class);
        $resourceStub->method('getHttp')->willReturn($clientStub);
    }

    /**
     * @test
     */
    public function is_invalid_calculate()
    {
        $clientStub = $this->createMock(ClientInterface::class);
        $clientStub->method('send')->willReturn(function () { throw new \Exception; });

        $resourceStub = $this->createMock(Resource::class);
        $resourceStub->method('getHttp')->willReturn($clientStub);
    }
}
