<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use lib\TmhDatabase;
use lib\TmhJson;
use lib\TmhSamsara;

require_once(__DIR__ . '/defines.php');
require_once(__DIR__ . '/lib/TmhDatabase.php');
require_once(__DIR__ . '/lib/TmhJson.php');
require_once(__DIR__ . '/lib/TmhSamsara.php');

$json = new TmhJson();
$database = new TmhDatabase($json);

//echo 'samsara';
$samsara = new TmhSamsara($database, $json);
echo $samsara->toHtml();
