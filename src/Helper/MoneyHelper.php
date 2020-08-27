<?php


namespace App\Helper;


class MoneyHelper
{
    static function boolToInt($value): int
    {
        return (int)round($value*100, 0);
    }
}