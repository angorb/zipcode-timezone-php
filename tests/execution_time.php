<?php
require_once __DIR__ . "/../src/Timezone.php";
require_once __DIR__ . "/../src/TimezoneForZip.php";
require_once __DIR__ . "/../src/FileNotFoundException.php";

use Angorb\ZipCodeTimezone\Timezone;
use Angorb\ZipCodeTimezone\TimezoneForZip;

$exampleZipCodes = [
    "Denver, CO" => 80204,
    "Los Angeles, CA" => 91367,
    "New York, NY" => 10009,
    "San Juan, PR" => '00909',
    "Honolulu, HI" => 96795,
    "Hagåtña, Guam" => 96910,
    "Chicago, IL" => '60611',
];

$tzInstance = new Timezone(Timezone::FORMAT_JSON);
$compressedInstance = new Timezone(Timezone::FORMAT_GZIP);
$msgpackInstance = new Timezone(Timezone::FORMAT_MSGPACK);

$testFunctions = [
    'new_object_instance_method' => null,
    'new_object_instance_method_with_compression' => null,
    'new_object_msgpack' => null,
    'reused_object_instance_method' => $tzInstance,
    'reused_object_instance_method_with_compression' => $compressedInstance,
    'reused_object_msgpack' => $msgpackInstance,
    'static_method' => null,
    'static_method_with_compression' => null,
    'static_method_with_msgpack' => null,
];

foreach ($testFunctions as $test => $object) {
    foreach ($exampleZipCodes as $name => $zip) {
        if (empty($results[$zip])) {
            $results[$zip]['name'] = $name;
        }
        $results[$zip][$test] = runTest($zip, $test, $object);
    }
}

foreach ($results as $zip => $data) {
    $name = $data['name'];
    unset($data['name']);
    $testTimes = array_column($data, 1);
    $totalTime = array_sum($testTimes);
    $minTime = min($testTimes);
    printf("%s (%s) [%f seconds total]:\n", $name, $zip, round($totalTime, 6));
    foreach ($data as $testName => $results) {
        $pctOfTotal = round(($results[1] / $totalTime) * 100, 2);
        $isFastest = ($results[1] === $minTime) ? "*FASTEST* " : null;
        printf(
            "\t[%s%s] took %f seconds (%s%% of total)\n",
            $isFastest,
            $testName,
            $results[1],
            $pctOfTotal,
        );
    }
    printf("\tResult: %s\n", $results[0]);
}

function runTest($zip, $testFunction, &$object = null)
{
    $start = \microtime(\true);
    $timezone = empty($object) ? $testFunction($zip) : $testFunction($zip, $object);
    $elapsed = \microtime(\true) - $start;
    return [$timezone, $elapsed];
}

function new_object_instance_method($zip)
{
    return (new Timezone(Timezone::FORMAT_JSON))->getForZipCode($zip);
}

function new_object_instance_method_with_compression($zip)
{
    return (new Timezone(Timezone::FORMAT_GZIP))->getForZipCode($zip);
}

function new_object_msgpack($zip)
{
    return (new Timezone(Timezone::FORMAT_MSGPACK))->getForZipCode($zip);
}

function reused_object_instance_method($zip, &$object)
{
    return $object->getForZipCode($zip);
}

function reused_object_instance_method_with_compression($zip, &$object)
{
    return $object->getForZipCode($zip);
}

function reused_object_msgpack($zip, &$object)
{
    return $object->getForZipCode($zip);
}

function static_method($zip)
{
    return TimezoneForZip::get($zip);
}

function static_method_with_compression($zip)
{
    return TimezoneForZip::get($zip, Timezone::FORMAT_GZIP);
}

function static_method_with_msgpack($zip)
{
    return TimezoneForZip::get($zip, Timezone::FORMAT_MSGPACK);
}