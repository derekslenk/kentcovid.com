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

foreach ($data as $datum) {
    $submitted[] = $datum["submitted"];
    $negative[] = $datum["negative"];
    $positive[] = $datum["positive"];
    $pending[] = $datum["pending"];
    $created_at[] = $datum["created_at"];
    $deaths[] = $datum["deaths"];
}

$options =  array('extension' => '.html');

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/views'),
    'entity_flags' => ENT_QUOTES,
));

echo $m->render('beta', array('negative' => $negative, 'positive' => $positive, 'pending' => $pending, 'deaths' => $deaths, 'created_at' => $created_at));
