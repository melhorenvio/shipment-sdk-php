<?php


namespace MelhorEnvio;

use MelhorEnvio\Resources\Base;
use MelhorEnvio\Resources\Shipment\Calculator;

class Shipment extends Base
{
    /**
     * @return Calculator
     */
    public function calculator()
    {
        new Calculator($this);
    }
}
