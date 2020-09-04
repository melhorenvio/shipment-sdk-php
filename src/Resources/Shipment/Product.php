<?php

namespace MelhorEnvio\Resources\Shipment;

use MelhorEnvio\Validations\Number;
use InvalidArgumentException;

/**
 * Class Product
 * @package MelhorEnvio\Resources\Shipment
 */
class Product extends Volume
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var int|float
     */
    protected $insuranceValue;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * Product constructor.
     *
     * @param string $id
     * @param $height
     * @param $width
     * @param $length
     * @param $weight
     * @param $insuranceValue
     * @param $quantity
     */
    public function __construct($id, $height, $width, $length, $weight, $insuranceValue, $quantity = 1)
    {
        $this->setId($id);
        $this->setHeight($height);
        $this->setWidth($width);
        $this->setLength($length);
        $this->setWeight($weight);
        $this->setInsuranceValue($insuranceValue);
        $this->setQuantity($quantity);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = (string) $id;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        if (! Number::isPositiveInteger($quantity)) {
            throw new InvalidArgumentException("quantity");
        }

        $this->quantity = $quantity;
    }

    /**
     * @return int|float
     */
    public function getInsuranceValue()
    {
        return $this->insuranceValue;
    }

    /**
     * @param int|float $insuranceValue
     */
    public function setInsuranceValue($insuranceValue)
    {
        $this->validateNumericArgument($insuranceValue, 'insurance_value');

        $this->insuranceValue = $insuranceValue;
    }

    /**
     * @return array
     */
    public function toArray()
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
