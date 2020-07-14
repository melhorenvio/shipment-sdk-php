<?php


namespace MelhorEnvio\Resources\Shipment;


class Package
{
    /**
     * @var int
     */
    protected $insurance;

    /**
     * Package constructor
     *
     * @param $height
     * @param $width
     * @param $lenght
     * @param $weitght
     * @param $insurance
     */
    public function __construct($height, $width, $lenght, $weitght, $insurance)
    {
        $this->setHeight($height);
        $this->setWidth($width);
        $this->setLength($lenght);
        $this->setWeight($weitght);
        $this->setInsurance($insurance);
    }

    /**
     * @return mixed
     */
    public function getInsurance()
    {
        return $this->insurance;
    }

    /**
     * @param mixed $insurance
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
