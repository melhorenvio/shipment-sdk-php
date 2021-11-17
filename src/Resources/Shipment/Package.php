<?php

namespace MelhorEnvio\Resources\Shipment;

class Package extends Volume
{
    protected float $insurance;

    public function __construct(
        float $height,
        float $width,
        float $length,
        float $weight,
        float $insurance
    ) {
        $this->setHeight($height);
        $this->setWidth($width);
        $this->setLength($length);
        $this->setWeight($weight);
        $this->setInsurance($insurance);
    }

    public function getInsurance(): float
    {
        return $this->insurance;
    }

    public function setInsurance(float $insurance)
    {
        $this->insurance = $insurance;
    }

    public function toArray(): array
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
