<?php

error_reporting(-1);
ini_set('display_errors', 1);

Class System {

	private $sensor_d;
	private $hdd_d;
	public $cpu_temp;
	public $system_temp;
	public $storage_total;


	public function __construct() {
		$sensor_data = shell_exec('sensors -u');
		$this->sensor_d = $sensor_data;
	}

	private function get_sensor_data () {
		$data = shell_exec('sensors -u');
		if (\strpos($data, 'temp2_input') !== false) {
			$parsed = get_string_between($data, 'temp2_input:', 'temp2_max:');
			$parsed = trim($parsed);
			$par_c = number_format((float)$parsed, 2, '.', '');
			$par_f = number_format((float)((($par_c * 9) / 5) + 32), 2, '.', '');
			echo  $par_f . ' °F (' . $par_c . ' °C)';
		}
	}

	public function get_value(){
		return "fart";
	}
}
