<?php

namespace MelhorEnvio;

use MelhorEnvio\Resources\Base;
use MelhorEnvio\Resources\Shipment\Calculator;

class Shipment extends Base
{
    public function calculator(): Calculator
    {
        return new Calculator($this);
    }
}
