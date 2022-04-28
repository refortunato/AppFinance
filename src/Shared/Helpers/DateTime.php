<?php

namespace AppFinance\Shared\Helpers;

class DateTime
{
    public static function createDateTimeObjFromDateString(
        string $date, 
        string $timezone = "America/Fortaleza", 
        bool $return_now_when_format_not_found = false) : \DateTime
    {
        $formats = [
            'Y-m-d H:i',
            'Y-m-d H:i:s',
            'Y-m-d\TH:i:s',
            'Y-m-d H:i:s.000',
            'Y-m-d H:i:s.u',
            'Y-m-d\TH:i:s.u',
            'Y-m-d',
            'Y-m',
            'd/m/Y H:i',
            'd/m/Y H:i:s',
            'd/m/Y H:i:s.000',
            'd/m/Y H:i:s.u',
            'd/m/Y',
            'm/Y',
            'c', // 2021-05-05T15:30:00-03:00
        ];

        foreach ($formats as $format) {
            $dateTimeObj = \DateTime::createFromFormat($format, $date, new \DateTimeZone($timezone));
            if ($dateTimeObj) {
                return $dateTimeObj;
            }
        }
        
        if ($return_now_when_format_not_found) {
            return new \DateTime('now', new \DateTimeZone($timezone));
        }

        throw new \Exception("Date format is not found.");
    }
}