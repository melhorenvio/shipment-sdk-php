<?php

namespace MelhorEnvio\Resources\Shipment;

use MelhorEnvio\Validations\Number;
use InvalidArgumentException;

class Product extends Volume
{
    protected string $id;

    protected float $insuranceValue;

    protected int $quantity;

    public function __construct(
        string $id,
        float $height,
        float $width,
        float $length,
        float $weight,
        float $insuranceValue,
        int $quantity = 1
    ) {
        $this->setId($id);
        $this->setHeight($height);
        $this->setWidth($width);
        $this->setLength($length);
        $this->setWeight($weight);
        $this->setInsuranceValue($insuranceValue);
        $this->setQuantity($quantity);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id = (string) $id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity)
    {
        if (! Number::isPositiveInteger($quantity)) {
            throw new InvalidArgumentException("quantity");
        }

        $this->quantity = $quantity;
    }

    public function getInsuranceValue(): float
    {
        return $this->insuranceValue;
    }

    public function setInsuranceValue(float $insuranceValue)
    {
        $this->validateNumericArgument($insuranceValue, 'insurance_value');

        $this->insuranceValue = $insuranceValue;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'height' => $this->getHeight(),
            'width' => $this->getWidth(),
            'length' => $this->getLength(),
            'weight' => $this->getWeight(),
            'insurance_value' => $this->getInsuranceValue(),
            'quantity' => $this->getQuantity(),
        ];
    }
}
