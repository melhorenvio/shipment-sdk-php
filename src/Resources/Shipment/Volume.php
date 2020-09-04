<?php

namespace MelhorEnvio\Resources\Shipment;

use MelhorEnvio\Concerns\Arrayable;
use MelhorEnvio\Validations\Number;
use InvalidArgumentException;

abstract class Volume implements Arrayable
{
    /**
     * @var int|float
     */
    protected $height;

    /**
     * @var int|float
     */
    protected $width;

    /**
     * @var int|float
     */
    protected $length;

    /**
     * @var int|float
     */
    protected $weight;

    /**
     * @return int|float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int|float $weight
     */
    public function setWeight($weight)
    {
        $this->validateNumericArgument($weight, 'weight');

        $this->weight = $weight;
    }

    /**
     * @return int|float
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int|float $length
     */
    public function setLength($length)
    {
        $this->validateNumericArgument($length, 'length');

        $this->length = $length;
    }

    /**
     * @return int|float
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int|float $width
     */
    public function setWidth($width)
    {
        $this->validateNumericArgument($width, 'width');

        $this->width = $width;
    }

    /**
     * @return int|float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int|float $height
     */
    public function setHeight($height)
    {
        $this->validateNumericArgument($height, 'height');

        $this->height = $height;
    }

    /**
     * @param int|float $number
     * @param string $argument
     */
    protected function validateNumericArgument($number, $argument)
    {
        if (! Number::isPositive($number)) {
            throw new InvalidArgumentException($argument);
        }
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return ! empty($this->getHeight())
            && ! empty($this->getWidth())
            && ! empty($this->getLength())
            && ! empty($this->getWeight());
    }
}
