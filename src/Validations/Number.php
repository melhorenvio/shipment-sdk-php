<?php


namespace MelhorEnvio\Validations;


class Number
{
    /**
     * @param int|float $number
     * @return
     */
    public static function isPostive($number)
    {
        return (is_integer($number) || is_float($number)) && $number > 0;
    }

    /**
     * @param int $number
     * @return bool
     */
    public static function isPostiveInteger($number)
    {
        return is_integer($number) && number > 0;
    }
}
