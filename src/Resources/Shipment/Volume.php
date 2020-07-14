<?php


namespace MelhorEnvio\Resources\Shipment;

use MelhorEnvio\Concerns\Arrayble;
use MelhorEnvio\Validations\Number;
use InvalidArgumentException;

abstract class Volume implements Arrayable
{
    /**
     *
     */
    protected $height;
    protected $width;
    protected $length;
    protected $weight;

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->validateNumericArgument($weight, 'weight');

        $this->weight = $weight;
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width)
    {
        $this->validateNumericArgument($width, 'width');

        $this->width = $width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
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
