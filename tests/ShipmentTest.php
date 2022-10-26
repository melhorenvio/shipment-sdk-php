<?php

namespace MelhorEnvio\Tests;

use MelhorEnvio\Resources\Shipment\Calculator;
use MelhorEnvio\Shipment;

class ShipmentTest extends BaseResourceTest
{
    protected function getClass(): string
    {
        return Shipment::class;
    }

    /**
     * @test
     * @small
     */
    public function it_can_get_a_calculator_instance(): void
    {
        $sut = (new Shipment('::token::'))->calculator();

        $this->assertInstanceOf(Calculator::class, $sut);
    }
}
