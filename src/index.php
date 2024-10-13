<?php

use lib\TmhDocumentFactory;
use lib\TmhElementFactory;
use lib\TmhJson;
use lib\TmhNodeFactory;
use lib\TmhNodeTransformer;
use lib\TmhSamsara;

require_once('lib/TmhDocumentFactory.php');
require_once('lib/TmhElementFactory.php');
require_once('lib/TmhJson.php');
require_once('lib/TmhNodeFactory.php');
require_once('lib/TmhNodeTransformer.php');
require_once('lib/TmhSamsara.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//echo 'samsara';


$json = new TmhJson();
$nodeFactory = new TmhNodeFactory();
$elementFactory = new TmhElementFactory($nodeFactory);
$nodeTransformer = new TmhNodeTransformer();
$documentFactory = new TmhDocumentFactory($elementFactory);
$samsara = new TmhSamsara($documentFactory, $json, $nodeTransformer);
echo $samsara->toHtml();
