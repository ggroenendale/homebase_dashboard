/**
 * This is the primary auto running function. It runs on pageload and includes interval
 * functions with which to continuously update the information.
 * @return {[type]} [description]
 */
(function(){
	console.log("beep boop... Online");

	get_time();
	get_weather();
	setInterval(function (){
		get_time();
	}, 1000);
	setInterval(function (){
		get_weather();
	}, 15000);
})();

/**
 * This function is just the ajax call to get the weather from the php API
 * @return {[type]} [description]
 */
function get_weather() {
	$.ajax({
		type: 'GET',
		data: {
			lat : '45.6811636',
			lon : '-122.6691728'
		},
		url: '/homebase/api/weather/weather.php',
		success: function(data) {
			console.log("Getting Weather...");
			console.log(data);
			ex_weather(data);
		},
		error: function(err) {
			console.log("There was an error getting weather.");
			console.log(err);
		},
		complete: function(yay) {
			console.log("Regardless, its done...")
			console.log(yay);
		}
	});
}

/**
==========================================================================================
				JUST SOME MENTAL NOTES FOR HOW WEATHER DATA ARRIVES
==========================================================================================
 * clouds: "90"
 * cond: "Mist"
 * humid: 88
 * loc: "NE Hazel Dell Ave, Vancouver, WA"
 * press: {atm: "1.01653", hpa: "1030"}
 * rise: "7:39 AM"
 * set: "5:07 PM"
 * temp: {f: "38.50", c: "3.61"}
 * wind: {ang: "280", dir: "W", mph: "3.35", mps: "1.5"}
==========================================================================================
*/


/**
 * [ex_weather description]
 * @param  {[type]} wdata [description]
 * @return {[type]}       [description]
 */
function ex_weather(wdata) {
	console.log("Exporting weather...");
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

/**
 * This function retrieves the system time and reports the date and time in a nice format.
 * @return {[type]} [description]
 */
function get_time() {
	let current = new Date();
	let dayofweek = current.getDay() + 1;
	let weekday = '';
	switch (dayofweek){
		case 1:
			weekday = "Sunday";
			break;
		case 2:
			weekday = "Monday";
			break;
		case 3:
			weekday = "Tuesday";
			break;
		case 4:
			weekday = "Wednesday";
			break;
		case 5:
			weekday = "Thursday";
			break;
		case 6:
			weekday = "Friday";
			break;
		case 7:
			weekday = "Saturday";
			break;
		default:
			weekday = "I have no idea";
	}
	let datetime = weekday + ' ' + (current.getMonth()+1) + '/'+ current.getDate() + '/' + current.getFullYear() + '  ' + current.getHours() + ':' + current.getMinutes() + ':' + current.getSeconds();

	let out = document.getElementById("time");
	out.innerHTML = datetime;
}

/**
 * This function will retrieve the temperatures of the server
 * @return {[type]} [description]
 */
function get_server_temps() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/system/servertemps.php',
		success: function(data){
			//console.log(data);
			//ex_server_temps(data);
		}
	});
}

/**
 * This function will retrieve the server temp data from the API and report it on the Dashboard
 * @param  {[type]} sdata [description]
 * @return {[type]}       [description]
 */
function ex_server_temps(sdata) {

}

/**
 * This function will grab the current calories recorded in the system
 * @return {[type]} [description]
 */
function get_current_calories() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/diet/calories',
		success: function(data){
			console.log(data);
			ex_current_calories(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will export the current calorie information received and report it to the dashboard
 * @param  {[type]} cdata [description]
 * @return {[type]}       [description]
 */
function ex_current_calories(cdata) {

}

/**
 * This function will retrieve the current meal plan that is recorded in the system. This should be a meal
 * for breakfast lunch and dinner.
 * @return {[type]} [description]
 */
function get_current_mealplan() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/diet/mealplan',
		success: function(data){
			console.log(data);
			ex_current_mealplan(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function should export the meal plan information to the dashboard. This should be a breakfast lunch and dinner export
 * @param  {[type]} mdata [description]
 * @return {[type]}       [description]
 */
function ex_current_mealplan(mdata) {

}

/**
 * This function should retrieve schedule information. This includes important events as well as normal schedule. So birthdays, holidays,
 * meetings and the like. It should also show like gym times and sleep times maybe other suggested time blocks.
 * @return {[type]} [description]
 */
function get_schedule() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/schedule',
		success: function(data){
			console.log(data);
			ex_schedule(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 *
 * @return {[type]} [description]
 */
function ex_schedule(sdata) {

}

/**
 * This function should get a list of Artists with numbers that show how much they have been listened to.
 * i.e. something to the idea of:
 * 		Trivium: 3482,
 * 		Amon Amarth: 1244,
 * 		Eminem: 1
 * @return {[type]} [description]
 */
function get_artists() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/music/artists.php',
		success: function(data){
			console.log(data);
			ex_artists(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will take the provided artists and frequency numbers, then crunch the total and percentages and
 * report these figures in a cool way on the dashboard.
 * @param  {[type]} adata [description]
 * @return {[type]}       [description]
 */
function ex_artists(adata) {

}

/**
 * This function will retrieve the budget goals in the database. In other words this will show ideal utlities values and ideal
 * gas expenses and fixed monthly costs.
 * @return {[type]} [description]
 */
function get_budget() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/finances/budget.php',
		success: function(data){
			console.log(data);
			ex_budget(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will take the retrieved budgetary data and report it in a table or some other neat way of reporting it.
 * @return {[type]} [description]
 */
function ex_budget(bdata) {

}

/**
 * This function will get incomes received from various streams.
 * @return {[type]} [description]
 */
function get_incomes() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/finances/incomes.php',
		success: function(data){
			console.log(data);
			ex_incomes(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	})
}

/**
 * This function will report incomes next to budget totals
 * @param  {[type]} idata [description]
 * @return {[type]}       [description]
 */
function ex_incomes(idata) {

}

/**
 * This function will get various expenses
 * @return {[type]} [description]
 */
function get_expenses() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/financials/expenses',
		success: function(data){
			console.log(data);
			ex_expenses(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will report expenses next to budget totals
 * @param  {[type]} edata [description]
 * @return {[type]}       [description]
 */
function ex_expenses(edata) {

}

/**
 * This function will retrieve stock information on watched stocks.
 * @return {[type]} [description]
 */
function get_stocks(){
	$.ajax({
		type: 'GET',
		url: '/homebase/api/financials/stocks.php',
		success: function(data){
			console.log(data);
			ex_stocks(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will take the retrieved stock data and report it to the dashboard.
 * @param  {[type]} sdata [description]
 * @return {[type]}       [description]
 */
function ex_stocks(sdata) {

}

/**
 * This function will report current positions on stocks held. This would be number of shares, price when bought, how long held...
 * @return {[type]} [description]
 */
function get_stock_positions() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/fincancials/stock_positions.php',
		success: function(data){
			console.log(data);
			ex_stock_positions(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will take the stock position data and report it to the dashboard.
 * @param  {[type]} spdata [description]
 * @return {[type]}        [description]
 */
function ex_stock_positions(spdata){

}

/**
 * This function will retrieve current hard drive storage statistics of the server.
 * @return {[type]} [description]
 */
function get_server_storage() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/system/server_storage.php',
		success: function(data){
			console.log(data);
			ex_server_storage(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will take the server storage data and report it to the dashboard.
 * @param  {[type]} ssdata [description]
 * @return {[type]}        [description]
 */
function ex_server_storage(ssdata) {

}

/**
 * This function will get server RAM usage
 * @return {[type]} [description]
 */
function get_server_memory() {
	$.ajax({
		type: 'GET',
		url:	'/homebase/api/system/server_memory.php',
		success: function(data){
			console.log(data);
			ex_server_memory(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will take the server memory data and report it to the dashboard.
 * @param  {[type]} memdata [description]
 * @return {[type]}         [description]
 */
function ex_server_memory(memdata) {

}

/**
 * This function will get the current house electricity usage.
 * @return {[type]} [description]
 */
function get_electricity_usage() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/house/electricity_usage.php',
		success: function(data){
			console.log(data);
			ex_electricity_usage(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will take the current electricity usage and report it to the dashboard.
 * @return {[type]} [description]
 */
function ex_electricity_usage(eldata) {

}

/**
 * This function will get the current water usage information.
 * @return {[type]} [description]
 */
function get_water_usage() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/house/water_usage.php',
		success: function(data){
			console.log(data);
			ex_water_usage(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will take the current water usage data and export it to the dashboard.
 * @param  {[type]} wdata [description]
 * @return {[type]}       [description]
 */
function ex_water_usage(wdata) {

}

/**
 * This function will get the current internet data usage from comcast account.
 * @return {[type]} [description]
 */
function get_internet_usage() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/house/internet_usage.php',
		success: function(data){
			console.log(data);
			ex_internet_usage(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will take the retrieved internet usage and report it to the dashboard.
 * @param  {[type]} intdata [description]
 * @return {[type]}         [description]
 */
function ex_internet_usage(intdata) {

}

/**
 * This function will return the current weight.
 * @return {[type]} [description]
 */
function get_weight() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/health/weight.php',
		success: function(data){
			console.log(data);
			ex_weight(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will take the returned weight and report it in a cool graph.
 * @param  {[type]} wedata [description]
 * @return {[type]}        [description]
 */
function ex_weight(wedata) {

}

/**
 * This function will grab the current BMI.
 * @return {[type]} [description]
 */
function get_bmi() {
	$.ajax({
		type: 'GET',
		url: '/homebase/api/health/bmi.php',
		success: function(data){
			console.log(data);
			ex_bmi(data);
		},
		error: function(err){
			console.log(err);
		},
		complete: function(done){
			console.log(done);
		}
	});
}

/**
 * This function will take the current BMI and report it to the dashboard.
 * @param  {[type]} bmidata [description]
 * @return {[type]}         [description]
 */
function ex_bmi(bmidata) {

}

/**
 * [gotocalories description]
 * @return {[type]} [description]
 */
function gotocalories() {
	window.location.href = "/homebase/pages/calorieform.php";
	//console.log("this function will run");
}


/**
 ==========================================================================================
 DRAWING STUUUUUUFFFFFFF
 ==========================================================================================
 ==========================================================================================
 */

//Draw shapes
let draw = SVG('drawing');

draw.rect(100,100).move(100,50).fill('#f06');
