<?php

namespace Homebase\API\Config;

Class Config {

	private $var;
	private $goog_api;
	private $opwm_api;
	private $homebase_db_name;
	private $homebase_db_user;
	private $homebase_db_pass;

	public function __construct(){
		$this->goog_api = get_cfg_var('homebase.goog_KEY');
		$this->opwm_api = get_cfg_var('homebase.opwm_KEY');
		//$this->homebase_db_name = get_cfg_var('homebase.rdb_DB_NAME');
		//$this->homebase_db_user = get_cfg_var('homebase.rdb_DB_USER');
		//$this->homebase_db_pass = get_cfg_var('homebase.rdb_DB_PASS');
	}

	public function get_goog_key(){
		return $this->goog_api;
	}

	public function get_opwm_key(){
		return $this->opwm_api;
	}


}
