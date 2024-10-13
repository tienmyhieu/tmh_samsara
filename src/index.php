<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//echo 'samsara';

use lib\Factory;

require_once('lib/Factory.php');

$factory = new Factory();
$controller = $factory->controller();
echo $controller->toHtml();
