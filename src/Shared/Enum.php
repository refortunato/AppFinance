<?php

namespace AppFinance\Shared;

abstract class Enum
{
    public static function getConstants()
    {
        $reflection_class = new \ReflectionClass(static::class);
        return $reflection_class->getConstants();
    }

    public static function getValues()
    {
        $constants = self::getConstants();
        $values = [];
        foreach ($constants as $key => $value) {
            $values[] = $value;
        }
        return $values;
    }
}