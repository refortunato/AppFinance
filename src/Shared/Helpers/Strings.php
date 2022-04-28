<?php

namespace AppFinance\Shared\Helpers;

class Strings
{
    public static function onlyNumber($value) : string
    {
        return preg_replace("/[^A-Za-z0-9]/", "", $value);
    }
}