<?php
/**
 * Main index file for KentCovid.com
 * 
 * PHP Version 7.2
 * 
 * @category Website
 * @package  KentCovid
 * @author   Derek Slenk <github@slenk.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://kentcovid.com
 */
require __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('America/Detroit');

$str_data = file_get_contents("data.json");

$data = json_decode($str_data, true);

// Create blank arrays for all the needed processing.
// I bet I could one line this somehow
$submitted = $negative = $positive = $pending = $deaths = $created_at = $submitted_delta = $negative_delta = $positive_delta = $pending_delta = $deaths_delta = array();
$prev_submitted = $prev_negative = $prev_positive = $prev_pending = $prev_deaths = 0;

$delta_builder = array();

foreach ($data as $datum) {
    $submitted[] = $datum["submitted"];
    $negative[] = $datum["negative"];
    $positive[] = $datum["positive"];
    $pending[] = $datum["pending"];
    $created_at[] = $datum["created_at"];
    $deaths[] = $datum["deaths"];

    $submitted_delta[] = $datum["submitted"] - $prev_submitted;
    $negative_delta[] = $datum["negative"] - $prev_negative;
    $positive_delta[] = $datum["positive"] - $prev_positive;
    $deaths_delta[] = $datum["deaths"] - $prev_deaths;

    $prev_positive = $datum["positive"];
    $prev_deaths = $datum["deaths"];
}


$m = new Mustache_Engine(
    array(
        'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views'),
        'entity_flags' => ENT_QUOTES,
    )
);

$lastModifiedTimestamp = filemtime("data.json");
$lastModifiedDatetime = date("d M Y H:i:s T", $lastModifiedTimestamp);

echo $m->render(
    'index',
    array(
        'positive' => $positive,
        'deaths' => $deaths,
        'created_at' => $created_at,
        'positive_delta' => $positive_delta,
        'deaths_delta' => $deaths_delta,
        'lastmod' => $lastModifiedDatetime
    )
);
