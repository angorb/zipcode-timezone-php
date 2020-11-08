<?php

require_once __DIR__ . "/../src/Timezone.php";

class DatafileSecurityCheck extends \Angorb\ZipCodeTimezone\Timezone
{

    public function __construct()
    {
        $uncompressed_checksum = md5(gzcompress(file_get_contents(self::DATA_FILE)));
        $compressed_checksum = md5(file_get_contents(self::COMPRESSED_DATA_FILE));

        printf(
            "Uncompressed Checksum:\t%s    Path: %s\nCompressed Checksum:\t%s    Path: %s\n",
            $uncompressed_checksum,
            realpath(self::DATA_FILE),
            $compressed_checksum,
            realpath(self::COMPRESSED_DATA_FILE)
        );

        if ($uncompressed_checksum === $compressed_checksum) {
            printf("OK: Compressed and uncompressed files match.\n");
            exit(0);
        }

        printf("WARNING: Compressed and uncompressed files DO NOT match!\n");
        exit(1);
    }

}

new DatafileSecurityCheck();