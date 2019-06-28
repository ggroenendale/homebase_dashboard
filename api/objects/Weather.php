<?php
//State which namespace this file is a part of.
namespace Homebase\API\Objects;

//Include required libraries.
use Homebase\API\Config\Config;

class Weather {
	public $lat;
	public $long;
	public $weather;
	public $location;
	private $weather_data;
	private $location_data;
	public $goog_key;
	public $opwm_key;

	public function __construct($lat=0.0, $long=0.0) {
		$config = new Config();
		//$lat = 45.6811636
		//$long = -122.6691728

		$this->goog_key = $config->get_goog_key();
		$this->opwm_key = $config->get_weath_key();

		$this->lat = (float)$lat;
		$this->long = (float)$long;
		$this->get_location();
		$this->get_weather();
	}// Find out the local weather

	public function get_weather(){
		// Load the Weather data
		$data = file_get_contents('https://api.openweathermap.org/data/2.5/weather?lat=' . $this->lat . '&lon=' . $this->long . '&APPID=' . $this->opwm_key . '&mode=json');
		$this->weather_data = json_decode($data);
		//var_dump($this->weather_data->weather);
		$this->weather['condition'] = (string)$this->weather_data->weather[0]->description;
		$this->weather['temp_k'] = $this->weather_data->main->temp;
		$this->weather['temp_c'] = $this->weather['temp_k'] - 273.15;
		$this->weather['humidity'] = $this->weather_data->main->humidity;
		$this->weather['wind_speed'] = (string)$this->weather_data->wind->speed;
		$this->weather['wind_direc'] = isset($this->weather_data->wind->deg) ? (string)$this->weather_data->wind->deg : "Null";
		$this->weather['pressure'] = (string)$this->weather_data->main->pressure;
		$this->weather['clouds'] = (string)$this->weather_data->clouds->all;
		$this->weather['sunrise'] = (int)$this->weather_data->sys->sunrise;
		$this->weather['sunset'] = (int)$this->weather_data->sys->sunset;
		return $this;
	}

	// Get the nearest weather hub location
	public function get_location(){
		// Load the location data
		$loc_url = 'https://maps.google.com/maps/api/geocode/json?sensor=true&latlng=' . $this->lat . ',' . $this->long . '&key=' . $this->goog_key;
		$loc_url = urldecode($loc_url);
		// Send through PHP CURL
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $loc_url);
		curl_setopt($curl, CURLOPT_HTTPGET, 1);
		//curl_setopt($curl, CURLOPT_POSTFIELDS)
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$output = curl_exec($curl);
		curl_close($curl);

		$this->location_data = json_decode($output);

		if($this->location_data->status == "OK") {
			// Set the name based on the location. e.g: Portsmouth, England
			$this->location = $this->location_data->results[1]->address_components[1]->short_name . ', ' . $this->location_data->results[1]->address_components[3]->short_name . ', ' . $this->location_data->results[1]->address_components[5]->short_name;
			return $this;
		}
		else {
			print_r($this->location_data);
			return "something went wrong";
		}
		//$data = file_get_contents($loc_url);
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

	public function say_temperature(){
		//Get Celsius from API call
		$c = $this->weather['temp_c'];

		//Convert to Fahrenheit
		$f = ((($c * 9) / 5) + 32);

		//Format numbers for proper display
		$far = number_format((float)$f, 2, '.', '');
		$cel = number_format((float)$c, 2, '.', '');

		//Pack the $temps array with the temperatures
		$temps = array(
			'f' => $far,
			'c' => $cel
		);

		//Return the temperatures
		return $temps;
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
		$direction = '';
		if (is_numeric($ang)) {
			$ang_int = ((($ang) / 22.5) + 0.5) + 1;
			$direction = $dir[abs($ang_int)];
		}
		else {
			$direction = "NSEW";
		}


		//


		//
		//$wind = $milehr . ' mph ' . '(' . $ms . 'm/s) ' . $direction . ' (' . number_format((float)$ang, 2, '.', '') . '°)';
		$wind = array(
			'ang' => $ang,
			'dir' => $direction,
			'mph' => $milehr,
			'mps' => $ms
		);
		return $wind;
	}

	public function say_pressure(){
		//Value given in hectopascals (hPa)
		$hpa = $this->weather['pressure'];

		$atm = number_format((float)($hpa / 1013.25), 5, '.', '');

		$pressure = array(
			'atm' => $atm,
			'hpa' => $hpa
		);
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

/*
$w = new Weather('50.799995', '-1.065545'); // Input the Latitude and Longitude
echo $w->getLocation()->getWeather()->sayHuman();
Ouput~: Portsmouth, England, PO4 8 | Partly Cloudy 4°C, Humidity: 93%, Wind: N at 8 mph
*/
