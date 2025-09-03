<?php

namespace App\TollBox;

class DataFormat
{
    public static function unmask($value)
    {
        return preg_replace('/[^0-9]/', '', $value);

    }

    public static function numberFormat(float $value,int $decimal = 2, string $simbolDecimal = '.', string $simbolTousands = '')
    {
        return number_format($value,$decimal,$simbolDecimal,$simbolTousands);
    }
}
