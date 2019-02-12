<?php
//State the namespace this file is a part of.
namespace Homebase\API\Utils;


// PHP Functions file for various tasks and database loads.
function get_string_between($str, $start, $end) {
	$str = '' . $str;
	$ini = strpos($str, $start);
	if ($ini == 0){
		return '';
	}
	$ini += strlen($start);
	$len = strpos($str, $end, $ini) - $ini;
	return substr($str, $ini, $len);
}

/**
 *
 */
class Weather {
	public $lat;
	public $long;
	public $weather;
	public $location;
	private $weather_data;
	private $location_data;
	public $goog_key = 'AIzaSyBNFVZFZZkC4H9FubZFg_lYC2P5sePBoKk';
	public $owm_key = '4d45ca53f5fa801074bffcf2811a0702';

	public function __construct($lat=0.0, $long=0.0) {
		$this->lat = (float) $lat;
		$this->long = (float) $long;
		$this->get_location();
		$this->get_weather();
	}// Find out the local weather

	public function get_weather(){
		// Load the Weather data
		$data = file_get_contents('https://api.openweathermap.org/data/2.5/weather?lat=' . $this->lat . '&lon=' . $this->long . '&APPID=' . $this->owm_key . '&mode=json');
		$this->weather_data = json_decode($data);
		//var_dump($this->weather_data->weather);
		$this->weather['condition'] = (string)$this->weather_data->weather[0]->description;
		$this->weather['temp_k'] = $this->weather_data->main->temp;
		$this->weather['temp_c'] = $this->weather['temp_k'] - 273.15;
		$this->weather['humidity'] = $this->weather_data->main->humidity;
		$this->weather['wind_speed'] = (string)$this->weather_data->wind->speed;
		$this->weather['wind_direc'] = (string)$this->weather_data->wind->deg;
		$this->weather['pressure'] = (string)$this->weather_data->main->pressure;
		$this->weather['clouds'] = (string)$this->weather_data->clouds->all;
		$this->weather['sunrise'] = (int)$this->weather_data->sys->sunrise;
		$this->weather['sunset'] = (int)$this->weather_data->sys->sunset;
		return $this;
	}

	// Get the nearest weather hub location
	public function get_location(){
		// Load the location data
		$data = file_get_contents('https://maps.google.com/maps/api/geocode/json?sensor=true&latlng=' . $this->lat . ',' . $this->long . '&key=' . $this->goog_key);
		$this->location_data = json_decode($data);

		// Set the name based on the location. e.g: Portsmouth, England
		$this->location = $this->location_data->results[1]->address_components[1]->short_name . ', ' . $this->location_data->results[1]->address_components[3]->short_name . ', ' . $this->location_data->results[1]->address_components[5]->short_name;
		return $this;
	}

	public function say_location(){
		return $this->location;
	}

	/**
	 * Say the current condition of the weather.
	 * @return [type] [description]
	 */
	public function say_condition(){
		$condition = $this->weather['condition'];
		$cond = ucwords($condition);
		return $cond;
	}

	public function say_temperature($opt){
		$c = $this->weather['temp_c'];
		if ($opt === 'f'){
			$f = ((($c * 9) / 5) + 32);
			return number_format((float)$f, 2, '.', '');
		}
		elseif ($opt === 'c') {
			return number_format((float)$c, 2, '.', '');
		}
	}

	public function say_humidity(){
		return $this->weather['humidity'];
	}

	public function say_wind(){
		//wind speed is in meters/sec
		$ms = $this->weather['wind_speed'];
		$num = (((($ms / 1000) / 1.61) * 60) *60);
		$milehr = number_format((float)$num, 2, '.', '');
		$ang = $this->weather['wind_direc'];
		$ang_int = ((($ang) / 22.5) + 0.5) + 1;
		$dir = array(
			'1' => 'N',
			'2' => 'NNE',
			'3' => 'NE',
			'4' => 'ENE',
			'5' => 'E',
			'6' => 'ESE',
			'7' => 'SE',
			'8' => 'SSE',
			'9' => 'S',
			'10' => 'SSW',
			'11' => 'SW',
			'12' => 'WSW',
			'13' => 'W',
			'14' => 'WNW',
			'15' => 'NW',
			'16' => 'NNW',
			'17' => 'N'
		);

		$direction = $dir[abs($ang_int)];

		$wind = $milehr . ' mph ' . '(' . $ms . 'm/s) ' . $direction . ' (' . number_format((float)$ang, 2, '.', '') . '°)';
		return $wind;
	}

	public function say_pressure(){
		//Value given in hectopascals (hPa)
		$hpa = $this->weather['pressure'];

		$atm = $hpa / 1013.25;

		$pressure = number_format((float)$atm, 2, '.', '') . 'atm' . ' (' . $hpa . ' hPa)';
		return $pressure;
	}

	public function say_clouds(){
		return $this->weather['clouds'];
	}

	public function say_sunrise(){
		$unix = $this->weather['sunrise'];
		$time =	date('g:i A', $unix);
		return $time;
	}

	public function say_sunset(){
		$unix = $this->weather['sunset'];
		$time =	date('g:i A', $unix);
		return $time;
	}
}

/**Example
$w = new Weather('50.799995', '-1.065545'); // Input the Latitude and Longitude
echo $w->getLocation()->getWeather()->sayHuman();
// Ouput~: Portsmouth, England, PO4 8 | Partly Cloudy 4°C, Humidity: 93%, Wind: N at 8 mph
*/
