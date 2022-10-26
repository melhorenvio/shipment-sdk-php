<?php

namespace MelhorEnvio\Tests\Resources\Shipment;

use MelhorEnvio\Resources\Shipment\Package;

class PackageTest extends VolumeTestCase
{
    /**
     * @test
     * @small
     */
    public function it_creates_a_package(): void
    {
        $height = 111;
        $width = 222;
        $length = 333;
        $weight = 444;
        $insurance = 555;

        $package = new Package($height, $width, $length, $weight, $insurance);

        $this->assertEquals($height, $package->getHeight());
        $this->assertEquals($width, $package->getWidth());
        $this->assertEquals($length, $package->getLength());
        $this->assertEquals($weight, $package->getWeight());
        $this->assertEquals($insurance, $package->getInsurance());
    }

    /**
     * @test
     * @small
     */
    public function it_can_set_a_insurance(): void
    {
        $product = $this->instantiateVolume();

        $expected = 123;

        $product->setInsurance($expected);

        $this->assertEquals($expected, $product->getInsurance());
    }

    /**
     * @test
     * @small
     */
    public function it_can_convert_the_package_to_array(): void
    {
        $height = 111;
        $width = 222;
        $length = 333;
        $weight = 444;
        $insurance = 555;

        $expected = [
            'height' => $height,
            'width' => $width,
            'length' => $length,
            'weight' => $weight,
            'insurance' => $insurance,
        ];

        $package = new Package($height, $width, $length, $weight, $insurance);

        $sut = $package->toArray();

        $this->assertEquals($expected, $sut);
    }

    protected function instantiateVolume(int $height = 1, $width = 1, $length = 1, $weight = 1): Package
    {
        return new Package($height, $width, $length, $weight, 1);
    }
}
