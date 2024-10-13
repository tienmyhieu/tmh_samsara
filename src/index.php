<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//echo 'samsara';

use lib\Component\TmhComponentFactory;
use lib\HtmlComponent\TmhHtmlComponentFactory;
use lib\TmhDatabase;
use lib\TmhDocumentFactory;
use lib\TmhElementFactory;
use lib\TmhEntity;
use lib\TmhEntityMetadata;
use lib\TmhJson;
use lib\TmhNodeFactory;
use lib\TmhNodeTransformer;
use lib\TmhRoute;
use lib\TmhSamsara;
use lib\TmhStructure;
use lib\TmhTranslation;

require_once('lib/Component/TmhComponentFactory.php');
require_once('lib/HtmlComponent/TmhHtmlComponentFactory.php');
require_once('lib/TmhDatabase.php');
require_once('lib/TmhDocumentFactory.php');
require_once('lib/TmhElementFactory.php');
require_once('lib/TmhEntity.php');
require_once('lib/TmhEntityMetadata.php');
require_once('lib/TmhJson.php');
require_once('lib/TmhNodeFactory.php');
require_once('lib/TmhNodeTransformer.php');
require_once('lib/TmhRoute.php');
require_once('lib/TmhSamsara.php');
require_once('lib/TmhStructure.php');
require_once('lib/TmhTranslation.php');

$json = new TmhJson();
$translation = new TmhTranslation($json);
$database = new TmhDatabase($json, $translation);
$route = new TmhRoute($json, $translation);
$structure = new TmhStructure($json, $translation);
$nodeFactory = new TmhNodeFactory();
$elementFactory = new TmhElementFactory($nodeFactory);
$nodeTransformer = new TmhNodeTransformer();
$htmlComponentFactory = new TmhHtmlComponentFactory($elementFactory);
$documentFactory = new TmhDocumentFactory($elementFactory, $htmlComponentFactory, $nodeTransformer);
$componentFactory = new TmhComponentFactory($database, $structure, $route);
$metadata = new TmhEntityMetadata($route);
$entity = new TmhEntity($componentFactory, $database, $metadata, $structure);
$samsara = new TmhSamsara($documentFactory, $entity);
echo $samsara->toHtml();
