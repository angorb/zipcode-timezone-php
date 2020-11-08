<?php

namespace Angorb\ZipCodeTimezone;

class TimezoneForZip
{

    public static function get(string $zip, bool $compressed = false)
    {
        require_once __DIR__ . "/Timezone.php";
        return (new \Angorb\ZipCodeTimezone\Timezone($compressed))->getForZipCode($zip);
    }
}