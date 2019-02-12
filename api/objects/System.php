<?php
//State the namespace this file is a part of.
namespace Homebase\API\Objects;


/**
 * System values to report:
 * CPU Temp
 * System Temp
 * Hard drive total space
 * Hard drive used space
 * memory total space
 * memory used space
 *
 */



Class System {
	private $data;
	public $cpu_temp;
	public $system_temp;

	public $hdd_total;
	public $hdd_used;

	public $mem_total;
	public $mem_used;


	public function __construct() {

	}

	private function get_sensor_data() {
		$data = shell_exec('sensors -u');
		if (\strpos($data, 'temp2_input') !== false) {
			$parsed = get_string_between($data, 'temp2_input:', 'temp2_max:');
			$parsed = trim($parsed);
			$par_c = number_format((float)$parsed, 2, '.', '');
			$par_f = number_format((float)((($par_c * 9) / 5) + 32), 2, '.', '');
			echo  $par_f . ' °F (' . $par_c . ' °C)';
		}
	}

	public function get_temp(){
		return 'testing get temp';
	}
}
	// if (\strpos($data, 'temp2_input') !== false) {
	// 	$parsed = get_string_between($data, 'temp2_input:', 'temp2_max:');
	// 	$parsed = trim($parsed);
	// 	$par_c = number_format((float)$parsed, 2, '.', '');
	// 	$par_f = number_format((float)((($par_c * 9) / 5) + 32), 2, '.', '');
	// 	echo  $par_f . ' °F (' . $par_c . ' °C)';
	// }
