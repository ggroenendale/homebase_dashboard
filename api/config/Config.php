<?php
//State the namespace this file is a part of.
namespace Homebase\API\Config;

//Require vendor autoload file.
require_once __DIR__ . '/../../vendor/autoload.php';

Class Config {

	private $var;
	private $goog_api;
	private $opwm_api;
	private $sheet_id;
	private $homebase_db_name;
	private $homebase_db_user;
	private $homebase_db_pass;
	private $homebase_db_host;

	public function __construct(){
		$this->goog_api = get_cfg_var('homebase.goog_KEY');
		$this->opwm_api = get_cfg_var('homebase.opwm_KEY');
		$this->sheet_id = get_cfg_var('homebase.sheet_ID');
		$this->homebase_db_name = get_cfg_var('homebase.rdb_DB_NAME');
		$this->homebase_db_user = get_cfg_var('homebase.rdb_DB_USER');
		$this->homebase_db_pass = get_cfg_var('homebase.rdb_DB_PASS');
		$this->homebase_db_host = get_cfg_var('homebase.rdb_DB_HOST');
	}

	public function get_goog_key(){
		return $this->goog_api;
	}

	public function get_weath_key(){
		return $this->opwm_api;
	}

	public function get_sheet_id(){
		return $this->sheet_id;
	}

	public function get_sheets_client(){
		$client = new \Google_Client();
    	$client->setApplicationName('Google Sheets API PHP Quickstart');
    	$client->setScopes(\Google_Service_Sheets::SPREADSHEETS_READONLY);
    	$client->setAuthConfig(__DIR__ . '/sheetscredentials.json');
    	$client->setAccessType('offline');
    	$client->setPrompt('select_account consent');

		// Load previously authorized token from a file, if it exists.
	   // The file token.json stores the user's access and refresh tokens, and is
	   // created automatically when the authorization flow completes for the first
	   // time.
	   $tokenPath = 'token.json';
	   if (file_exists($tokenPath)) {
		   $accessToken = json_decode(file_get_contents($tokenPath), true);
		   $client->setAccessToken($accessToken);
	   }

	   // If there is no previous token or it's expired.
	   if ($client->isAccessTokenExpired()) {
		   // Refresh the token if possible, else fetch a new one.
		   if ($client->getRefreshToken()) {
		       $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		   }
		   else {
			   // Request authorization from the user.
			   $authUrl = $client->createAuthUrl();
			   printf("Open the following link in your browser:\n%s\n", $authUrl);
			   print 'Enter verification code: ';
			   $authCode = trim(fgets(STDIN));
			   // Exchange authorization code for an access token.
			   $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
			   $client->setAccessToken($accessToken);

			   // Check to see if there was an error.
			   if (array_key_exists('error', $accessToken)) {
				   throw new Exception(join(', ', $accessToken));
			   }
		   }
		   // Save the token to a file.
		   if (!file_exists(dirname($tokenPath))) {
			   mkdir(dirname($tokenPath), 0700, true);
		   }
		   file_put_contents($tokenPath, json_encode($client->getAccessToken()));
	   }
	   return $client;
	}

	public function get_mysql_client() {
		$client = mysqli_connect($this->homebase_db_host, $this->homebase_db_user, $this->homebase_db_pass, $this->homebase_db_name);

		return $client;
	}
}
