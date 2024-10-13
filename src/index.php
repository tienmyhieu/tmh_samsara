<?php

use lib\TmhJson;
use lib\TmhSamsara;

require_once('lib/TmhJson.php');
require_once('lib/TmhSamsara.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//echo 'samsara';

$json = new TmhJson();
//$locales = $json->translations();
//$routes = $json->routes();
$samsara = new TmhSamsara($json);;
//echo "<pre>";
//echo $samsara->translateEntity('metal', 'bb') . PHP_EOL;
//$emperorCoins = $samsara->filterEntity('emperor_coin', ['emperor' => 'mm', 'metal' => 'bb']);
//print_r($routes);
//print_r($locales);
//print_r($samsara->translateRoutes($samsara->currentLocale()));
//echo $samsara->currentLocale() . PHP_EOL;
//print_r($samsara->currentRoutes());
//echo $samsara->translateEntity('region', 'asia') . PHP_EOL;
//print_r($samsara->currentEntity());
//echo $samsara->memoryUsage() . PHP_EOL;
//echo "</pre>";
echo $samsara->toHtml();