<?php

error_reporting(-1);
ini_set('display_errors', 1);

/**
 * System will get all of the current info of the system
 */

require __DIR__ . '/objects/System.php';

$system = new System();

//$system->get_values();
//$value = $system->get_value();

echo json_encode(
	array(
		"temp" => "1000000"
	)
);
