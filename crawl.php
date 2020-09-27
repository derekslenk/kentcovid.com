<?php

require __DIR__ . '/vendor/autoload.php';

include './datum.php';


$BASE_URL = 'https://micovid-data.s3.us-east-2.amazonaws.com/';
// $DOWNLOAD_URL = 'http://micovid-data.s3-website.us-east-2.amazonaws.com/';



$xml = simplexml_load_file($BASE_URL) or die("Error: Cannot create object");

$final_data = array();
$fileList = array();

foreach ($xml->children() as $files) {
    if ($files->Key) {
        if (preg_match('/kent\/\d{4}\-\d{2}\-\d{2}/', $files->Key[0])) {
            $fileList[] = $files->Key[0];
        }
    }
}

// print_r($fileList);

system('aws s3 cp s3://micovid-data/kent/ kent --recursive');

foreach ($fileList as $file) {
    $data = file_get_contents($file, false);
    $json_data = json_decode($data, true);
    // print_r($json_data);
    $datum = new Datum($json_data["submitted"], $json_data["negative"], $json_data["positive"], $json_data["pending"], $json_data["deaths"], $json_data["created_at"]);
    $final_data[$json_data["created_at"]] = $datum;
    #    sleep(1);
}

$fh = fopen('/var/www/html/data.json', 'w') or die("Error opening output file");
fwrite($fh, json_encode($final_data, JSON_UNESCAPED_UNICODE));
fclose($fh);

print("Crawl finished");
