<?php

namespace MelhorEnvio\Tests\Resources\Shipment;

use InvalidArgumentException;
use MelhorEnvio\Resources\Shipment\Product;

class ProductTest extends VolumeTestCase
{
    /**
     * @test
     * @small
     */
    public function it_creates_a_product(): void
    {
        $id = '::id::';
        $height = 111;
        $width = 222;
        $length = 333;
        $weight = 444;
        $insuranceValue = 555;
        $quantity = 666;

        $product = new Product($id, $height, $width, $length, $weight, $insuranceValue, $quantity);

        $this->assertEquals($id, $product->getId());
        $this->assertEquals($height, $product->getHeight());
        $this->assertEquals($width, $product->getWidth());
        $this->assertEquals($length, $product->getLength());
        $this->assertEquals($weight, $product->getWeight());
        $this->assertEquals($insuranceValue, $product->getInsuranceValue());
        $this->assertEquals($quantity, $product->getQuantity());
    }

    /**
     * @test
     * @small
     */
    public function it_creates_a_product_with_quantity_1_as_default(): void
    {
        $product = new Product('::id::', 123, 123, 123, 123, 123);

        $this->assertEquals(1, $product->getQuantity());
    }

    /**
     * @test
     * @small
     */
    public function it_can_set_an_id(): void
    {
        $product = $this->instantiateVolume();

        $expexted = '::new-id::';

        $product->setId($expexted);

        $this->assertSame($expexted, $product->getId());
    }

    /**
     * @test
     * @small
     */
    public function it_can_set_a_insurance_value(): void
    {
        $product = $this->instantiateVolume();

        $expected = 123;

        $product->setInsuranceValue($expected);

        $this->assertEquals($expected, $product->getInsuranceValue());
    }

    /**
     * @test
     * @small
     */
    public function it_can_set_a_quantity(): void
    {
        $product = $this->instantiateVolume();

        $expected = 123;

        $product->setQuantity($expected);

        $this->assertEquals($expected, $product->getQuantity());
    }

    /**
     * @test
     * @small
     */
    public function it_can_convert_the_product_to_array(): void
    {
        $id = '::id::';
        $height = 111;
        $width = 222;
        $length = 333;
        $weight = 444;
        $insuranceValue = 555;
        $quantity = 666;

        $expected = [
            'id' => $id,
            'height' => $height,
            'width' => $width,
            'length' => $length,
            'weight' => $weight,
            'insurance_value' => $insuranceValue,
            'quantity' => $quantity,
        ];

        $product = new Product($id, $height, $width, $length, $weight, $insuranceValue, $quantity);

        $sut = $product->toArray();

        $this->assertEquals($expected, $sut);
    }

    protected function instantiateVolume(int $height = 1, $width = 1, $length = 1, $weight = 1): Product
    {
        return new Product(1, $height, $width, $length, $weight, 1);
    }

    /**
     * @test
     * @small
     */
    public function it_throws_when_constructing_with_a_non_positive_quantity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('quantity');

        new Product(1, 1, 1, 1, 1, 1, -1);
    }

    /**
     * @test
     * @small
     */
    public function it_throws_when_setting_a_non_positive_quantity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('quantity');

        $instance = $this->instantiateVolume();
        $instance->setQuantity(-1);
    }
}
