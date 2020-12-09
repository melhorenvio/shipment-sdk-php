<?php

namespace MelhorEnvio\Validations;

class Location
{
    /**
     * @param $postalCode
     * @return bool
     */
    public static function isPostalCode($postalCode)
    {
        return (bool) preg_match("/^[0-9]{8}/", $postalCode);
    }
}
