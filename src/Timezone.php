<?php

namespace Angorb\ZipCodeTimezone;

class Timezone
{
    const DATA_FILE = __DIR__ . "/../data/ziptzdb.json";
    const COMPRESSED_DATA_FILE = __DIR__ . "/../data/ziptzdb.json.gz";

    private $map;
    private static $timezones = [
        "America/New_York",
        "America/Puerto_Rico",
        "America/Chicago",
        "America/Denver",
        "America/Los_Angeles",
        "America/Phoenix",
        "Pacific/Honolulu",
        "Pacific/Pago_Pago",
        "Pacific/Majuro",
        "Pacific/Guam",
        "Pacific/Palau",
        "Pacific/Pohnpei",
        "America/Anchorage",
        "America/Adak",
    ];

    public function __construct(bool $compressed = false)
    {
        require_once __DIR__ . "/FileNotFoundException.php";

        $file = self::DATA_FILE;
        if ($compressed) {
            $file = self::COMPRESSED_DATA_FILE;
        }

        if (!\file_exists($file)) {
            throw new FileNotFoundException("Could not find zip code map data file at '{$file}'");
        }

        $data = $compressed ? \gzuncompress(\file_get_contents($file)) : \file_get_contents($file);

        $this->map = \json_decode($data, \true);

    }

    public function getForZipCode(string $zip)
    {

        if (!array_key_exists($zip, $this->map)) {
            return false;
        }

        return self::$timezones[$this->map[$zip]];
    }
}