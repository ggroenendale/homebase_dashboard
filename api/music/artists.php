<?php
//State the namespace this file is a part of.
namespace Homebase\API\Music;

//Require vendor autoload file.
require_once __DIR__ . '/../../vendor/autoload.php';

//Include required libraries.
use Homebase\API\Objects\Artists;

//Set error reporting to on in development mode.
error_reporting(-1);
ini_set('display_errors', 1);

//$artists = new Artists();

//$set = $artists->get_data();
$sheeturl   = "https://sheets.googleapis.com/v4/spreadsheets/";
$sheeturl  .= "1hFvUid-Ui050zwW8ZVShC1zAHz_qRCYjEjALIvMU4B0";
$sheeturl  .= "/values/Sheet1!A:D?";
$sheeturl  .= "&key=AIzaSyDazENEzlkPN-QAdwYEW9rw_oNjdm1Abm0";

$spreadsheet_id = "1hFvUid-Ui050zwW8ZVShC1zAHz_qRCYjEjALIvMU4B0";
$spreadsheet_range = "Sheet1!A:D";

$service_account_file = __DIR__ . '/sheetsservicer.json';

//echo $service_account_file;
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $service_account_file);
$client = new \Google_Client();
$client->useApplicationDefaultCredentials();
$client->addScope(\Google_Service_Sheets::SPREADSHEETS);
$service = new \Google_Service_Sheets($client);

$result = $service->spreadsheets_values->get($spreadsheet_id, $spreadsheet_range);

$values = $result->getValues();
//print_r($values);
for($i = 0; $i < count($values); $i++) {
	//Retrieve values from the spreadsheet and separate into columns
	$song_date 		= $values[$i][0];
	$song_name 		= $values[$i][1];
	$song_artist 	= $values[$i][2];
	$song_album 	= $values[$i][3];

	print("Beginning of entry <br>");
	print_r($values[$i]);
	print('<br>');
	print("End of Entry <br>");
}

//print_r($values);

//$curl = curl_init();
//curl_setopt($curl, CURLOPT_URL, $sheeturl);

//echo json_encode(
//	array(
//		"filename" => $sheeturl
//	)
//);
