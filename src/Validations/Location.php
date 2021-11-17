<?php

namespace MelhorEnvio\Validations;

class Location
{
    public static function isPostalCode(string $postalCode): bool
    {
        return (bool) preg_match("/^[0-9]{8}/", $postalCode);
    }
}
