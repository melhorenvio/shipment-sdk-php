<?php

namespace MelhorEnvio\Tests\Resources\Shipment;

use InvalidArgumentException;
use MelhorEnvio\Resources\Shipment\Volume;
use PHPUnit\Framework\TestCase;

abstract class VolumeTestCase extends TestCase
{
    /**
     * @param  int  $height
     * @param  int  $width
     * @param  int  $length
     * @param  int  $weight
     * @return Volume
     * @throws InvalidArgumentException
     */
    abstract protected function instantiateVolume(
        int $height = 1,
        int $width = 1,
        int $length = 1,
        int $weight = 1
    ): Volume;

    /**
     * @test
     * @small
     */
    public function it_can_set_a_height(): void
    {
        $instance = $this->instantiateVolume();

        $expected = 123;

        $instance->setHeight($expected);

        $this->assertEquals($expected, $instance->getHeight());
    }

    /**
     * @test
     * @small
     */
    public function it_can_set_a_width(): void
    {
        $instance = $this->instantiateVolume();

        $expected = 123;

        $instance->setWidth($expected);

        $this->assertEquals($expected, $instance->getWidth());
    }

    /**
     * @test
     * @small
     */
    public function it_can_set_a_length(): void
    {
        $instance = $this->instantiateVolume();

        $expected = 123;

        $instance->setLength($expected);

        $this->assertEquals($expected, $instance->getLength());
    }

    /**
     * @test
     * @small
     */
    public function it_can_set_a_weight(): void
    {
        $instance = $this->instantiateVolume();

        $expected = 123;

        $instance->setWeight($expected);

        $this->assertEquals($expected, $instance->getWeight());
    }

    /**
     * @test
     * @small
     */
    public function it_throws_when_constructing_with_a_non_positive_height(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('height');

        $this->instantiateVolume(-1, 1, 1, 1);
    }

    /**
     * @test
     * @small
     */
    public function it_throws_when_constructing_with_a_non_positive_width(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('width');

        $this->instantiateVolume(1, -1, 1, 1);
    }

    /**
     * @test
     * @small
     */
    public function it_throws_when_constructing_with_a_non_positive_length(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('length');

        $this->instantiateVolume(1, 1, -1, 1);
    }

    /**
     * @test
     * @small
     */
    public function it_throws_when_constructing_with_a_non_positive_weight(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('weight');

        $this->instantiateVolume(1, 1, 1, -1);
    }

    /**
     * @test
     * @small
     */
    public function it_throws_when_setting_a_non_positive_height(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('height');

        $instance = $this->instantiateVolume();
        $instance->setHeight(-1);
    }

    /**
     * @test
     * @small
     */
    public function it_throws_when_setting_a_non_positive_width(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('width');

        $instance = $this->instantiateVolume();
        $instance->setWidth(-1);
    }

    /**
     * @test
     * @small
     */
    public function it_throws_when_setting_a_non_positive_length(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('length');

        $instance = $this->instantiateVolume();
        $instance->setLength(-1);
    }

    /**
     * @test
     * @small
     */
    public function it_throws_when_setting_a_non_positive_weight(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('weight');

        $instance = $this->instantiateVolume();
        $instance->setWeight(-1);
    }

    /**
     * Right now, is not possible to create an invalid volume because the constructor validates the params.
     * So, the method $volume->isValid() always returns true.
     *
     * @test
     * @small
     */
    public function it_returns_true_when_checking_the_volume_is_valid(): void
    {
        $volume = $this->instantiateVolume();

        $this->assertTrue($volume->isValid());
    }
}
