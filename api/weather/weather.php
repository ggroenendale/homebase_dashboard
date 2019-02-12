<?php
//State which namespace this file is a part of.
namespace Homebase\API\Weather;

//Require vendor autoload file.
require_once __DIR__ . '/../../vendor/autoload.php';

//Include required libraries.
use Homebase\API\Config\Config;
use Homebase\API\Objects\Weather;

//Error testing.
error_reporting(-1);
ini_set('display_errors', 1);

//Required Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

//Get data from API call
$lat = $_GET['lat'];
$lon = $_GET['lon'];

//Instantiate Config object
$config = new Config();
$weath_api = $config->get_weath_key();

//Instantiate Weather object
$weather = new Weather($lat, $lon);

//Get
$loc = $weather->say_location();
$cond = $weather->say_condition();
$temp = $weather->say_temperature();
$humid = $weather->say_humidity();
$rise = $weather->say_sunrise();
$set = $weather->say_sunset();
$clouds = $weather->say_clouds();
$wind = $weather->say_wind();
$press = $weather->say_pressure();

//
echo json_encode(
	array(
		'loc' => $loc,
		'cond' => $cond,
		'temp' => $temp,
		'humid' => $humid,
		'rise' => $rise,
		'set' => $set,
		'clouds' => $clouds,
		'wind' => $wind,
		'press' => $press
	)
);
