<?php

namespace Angorb\ZipCodeTimezone;

class TimezoneForZip
{

    public static function get(string $zip, int $format = Timezone::FORMAT_JSON)
    {
        require_once __DIR__ . "/Timezone.php";
        return (new \Angorb\ZipCodeTimezone\Timezone($format))->getForZipCode($zip);
    }
}