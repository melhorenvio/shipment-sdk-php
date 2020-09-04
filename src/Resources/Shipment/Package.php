<?php

namespace MelhorEnvio\Resources\Shipment;

class Package extends Volume
{
    /**
     * @var int|float
     */
    protected $insurance;

    /**
     * Package constructor
     *
     * @param $height
     * @param $width
     * @param $length
     * @param $weight
     * @param $insurance
     */
    public function __construct($height, $width, $length, $weight, $insurance)
    {
        $this->setHeight($height);
        $this->setWidth($width);
        $this->setLength($length);
        $this->setWeight($weight);
        $this->setInsurance($insurance);
    }

    /**
     * @return int|float
     */
    public function getInsurance()
    {
        return $this->insurance;
    }

    /**
     * @param int|float $insurance
     */
    public function setInsurance($insurance)
    {
        $this->insurance = $insurance;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'height' => $this->getHeight(),
            'width' => $this->getWidth(),
            'length' => $this->getLength(),
            'weight' => $this->getWeight(),
            'insurance' => $this->getInsurance(),
        ];
    }
}
