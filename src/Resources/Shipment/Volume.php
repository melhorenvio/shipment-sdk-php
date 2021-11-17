<?php

namespace MelhorEnvio\Resources\Shipment;

use MelhorEnvio\Concerns\Arrayable;
use MelhorEnvio\Validations\Number;
use InvalidArgumentException;

abstract class Volume implements Arrayable
{
    protected float $height;

    protected float $width;

    protected float $length;

    protected float $weight;

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight)
    {
        $this->validateNumericArgument($weight, 'weight');

        $this->weight = $weight;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function setLength(float $length)
    {
        $this->validateNumericArgument($length, 'length');

        $this->length = $length;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function setWidth(float $width)
    {
        $this->validateNumericArgument($width, 'width');

        $this->width = $width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function setHeight(float $height)
    {
        $this->validateNumericArgument($height, 'height');

        $this->height = $height;
    }

    protected function validateNumericArgument(float $number, string $argument)
    {
        if (! Number::isPositive($number)) {
            throw new InvalidArgumentException($argument);
        }
    }

    public function isValid(): bool
    {
        return ! empty($this->getHeight())
            && ! empty($this->getWidth())
            && ! empty($this->getLength())
            && ! empty($this->getWeight());
    }
}
