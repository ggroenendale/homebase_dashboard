<?php
//State the namespace this file is a part of.
namespace Homebase\API\System;

//Require vendor autoload file.
require_once __DIR__ . '/../../vendor/autoload.php';

//Include required libraries
use Homebase\API\Config\Config;
use Homebase\API\Objects\System;

//Set error reporting to on in development mode.
error_reporting(-1);
ini_set('display_errors', 1);

//System will get all of the current info of the system
$system = new System();

//
$value = $system->get_temp();

//$system->get_values();
//$value = $system->get_value();

$file = $value;

echo json_encode(
	array(
		"filename" => $file
	)
);
