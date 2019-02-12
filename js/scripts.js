(function(){
	console.log("beep boop... Online Mother fucker");

	function get_data() {
		$.ajax({
			type: 'GET',
			url: '/homebase/api/system/serverstats.php',
			success: function(data){
				console.log(data);
				//ex_sys(data);
			}
		});
	}

	function get_weather() {
		$.ajax({
			type: 'GET',
			data: {
				lat : '45.6811636',
				lon : '-122.6691728'
			},
			url: '/homebase/api/weather/weather.php',
			success: function(data) {
				console.log(data)
				ex_weather(data);
			}
		});
	}
	get_data();
	get_weather();
	setInterval(function (){
		get_data();
		get_weather();
	}, 15000);
})();

function ex_sys(sdata) {

}

/**
 * clouds: "90"
 * cond: "Mist"
 * humid: 88
 * loc: "NE Hazel Dell Ave, Vancouver, WA"
 * press: {atm: "1.01653", hpa: "1030"}
 * rise: "7:39 AM"
 * set: "5:07 PM"
 * temp: {f: "38.50", c: "3.61"}
 * wind: {ang: "280", dir: "W", mph: "3.35", mps: "1.5"}
*/

function ex_weather(wdata) {
	//Get the Weather div for exporting information
	let wdiv = document.getElementById('weather');
	
	//Create the HTML payload
	let weather = '';
	
	//Load the HTML payload
	weather += `<p>${wdata.loc}</p>`;
	weather += `<p>${wdata.temp.f} °F (${wdata.temp.c} °C) ${wdata.cond} ${wdata.clouds}% Cloudy</p>`;
	weather += `<p>Wind: ${wdata.wind.mph} mph ${wdata.wind.dir} (${wdata.wind.mps} m/s)</p>`;
	weather += `<p>Pressure: ${wdata.press.atm} atm (${wdata.press.hpa} hPa)</p>`;
	weather += `<p>Sunrise ${wdata.rise} | Sunset ${wdata.set}</p>`;
	
	//Send the HTML payload
	wdiv.innerHTML = weather;
}

//Draw shapes
let draw = SVG('drawing');

draw.rect(100,100).move(100,50).fill('#f06');