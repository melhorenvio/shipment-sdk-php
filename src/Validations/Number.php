<?php

namespace MelhorEnvio\Validations;

class Number
{
    public static function isPositive(float $number): bool
    {
        return (is_integer($number) || is_float($number)) && $number > 0;
    }

    public static function isPositiveInteger(int $number): bool
    {
        return is_integer($number) && $number > 0;
    }
}
