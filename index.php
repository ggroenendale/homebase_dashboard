<?php
// For development purposes.
// error_reporting(-1);
// ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Homebase Dashboard</title>
	<link rel="stylesheet" type="text/css" href="/homebase/css/style.css">
</head>
<body>

<div id="dash-grid">
	<div class="grid-block">
		<h2>Weather:</h2>
		<div id="weather"></div>
		<h2>Stocks:</h2>
		<div id="stocks"></div>
	</div>
	<div class="grid-block">
		<p>Equipment Readouts</p>
		<p>- CPU Heat</p>
		<p>- Server System Temp:
		</p>
		<p>- CPU Temp:
		</p>
		<p>- Hard Drive Storage Stats</p>
		<p>- Memory Usage Stats</p>
	</div>
	<div class="grid-block">
		<p>Internet Status</p>
		<p>- Infiltration Statistics</p>
		<p>- Who is accessing my stuff</p>
	</div>
	<div class="grid-block">Financial Budget Info</div>
	<div class="grid-block">
		<p>Stocks</p>
	</div>
	<div class="grid-block">
		<p>Temperature Feedback</p>
		<p>- What heater is currently on</p>
		<!-- <div id="hex1" class="hexagon-wrapper">
			<div id="color1" class="hexagon"></div>
		</div> -->

		<div id="drawing"></div>

	</div>
	<?php phpinfo();?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="/homebase/js/svg.js"></script>
<script type="text/javascript" src="/homebase/js/scripts.js"></script>
</body>
</html>
