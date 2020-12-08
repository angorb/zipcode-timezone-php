<?php

use Angorb\ZipCodeTimezone\Timezone;

require_once __DIR__ . "/../src/Timezone.php";
require_once __DIR__ . "/../src/TimezoneForZip.php";
require_once __DIR__ . "/../src/FileNotFoundException.php";

class DatafileSecurityCheck extends \Angorb\ZipCodeTimezone\Timezone
{

    public function __construct()
    {
        $uncompressed_checksum = md5(gzcompress(file_get_contents(self::$database[0])));
        $compressed_checksum = md5(file_get_contents(self::$database[1]));

        printf(
            "Uncompressed Checksum:\t%s    Path: %s\nCompressed Checksum:\t%s    Path: %s\n",
            $uncompressed_checksum,
            realpath(self::$database[0]),
            $compressed_checksum,
            realpath(self::$database[1])
        );

        if ($uncompressed_checksum === $compressed_checksum) {
            printf("OK: Compressed and uncompressed JSON files match.\n");
            exit(0);
        }

        printf("WARNING: Compressed and uncompressed files DO NOT match!\n");
        exit(1);
    }
}

new DatafileSecurityCheck();