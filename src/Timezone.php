<?php

namespace Angorb\ZipCodeTimezone;

class Timezone
{

    const FORMAT_JSON = 0;
    const FORMAT_GZIP = 1;
    const FORMAT_MSGPACK = 2;

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

    protected static $database = [
        0 => __DIR__ . "/../data/ziptzdb.json",
        1 => __DIR__ . "/../data/ziptzdb.json.gz",
        2 => __DIR__ . "/../data/ziptzdb.msgpack"
    ];

    public function __construct(int $format)
    {
        require_once __DIR__ . "/FileNotFoundException.php";

        $this->file = self::$database[$format];

        if (!\file_exists($this->file)) {
            throw new FileNotFoundException("Could not find zip code map data file at '{$file}'");
        }

        switch ($format) {
            case 0:
                $this->loadJson();
                break;
            case 1:
                $this->loadJson(true);
                break;
            case 2:
                $this->loadMsgPack();
                break;
        }
    }

    private function loadJson(bool $compressed = false)
    {
        $data = $compressed ? \gzuncompress(\file_get_contents($this->file)) : \file_get_contents($this->file);

        $this->map = \json_decode($data, \true);
    }

    private function loadMsgPack()
    {
        $this->map = msgpack_unpack(\file_get_contents($this->file));
    }

    public function getForZipCode(string $zip)
    {

        if (!array_key_exists($zip, $this->map)) {
            return false;
        }

        return self::$timezones[$this->map[$zip]];
    }
}