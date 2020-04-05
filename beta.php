<?php

require __DIR__ . '/vendor/autoload.php';

$str_data = file_get_contents("data.json");

$data = json_decode($str_data, true);

$submitted = array();
$negative = array();
$positive = array();
$pending = array();
$deaths = array();
$created_at = array();
$submitted_delta = array();
$negative_delta = array();
$positive_delta = array();
$pending_delta = array();
$deaths_delta = array();

$prev_submitted = 0;
$prev_negative = 0;
$prev_positive = 0;
$prev_pending = 0;
$prev_deaths = 0;

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
    $delta_builder["x"] = $datum["created_at"];
    $delta_builder["y"] = $datum["positive"] - $prev_positive;
    $positive_delta[] = $delta_builder;

    $prev_positive = $datum["positive"];
}


$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views'),
    'entity_flags' => ENT_QUOTES,
));

echo $m->render('beta', array(
    'negative' => $negative,
    'positive' => $positive,
    'pending' => $pending, 
    'deaths' => $deaths, 
    'created_at' => $created_at,
    'positive_delta' => $positive_delta,
));
