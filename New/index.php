<?php
require_once "inc/Polyline.php";
include "Cache/cache_functions.php";
include "inc/functions.php";

// Coventry: lat = 52.40681980, long =-1.51970930
?>
<!DOCTYPE html>
<html ng-app>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        margin: 0;
        padding: 0;
        height: 100%;
      }
    </style>
    
    
    
    
    
    
    
    <!-- temporary! assigns the four bottom icons. Should take input from routes -->
    <script>
    	var r1safe = 0.6;
    	var r2safe = 0.4;
    	var r1time = 15;
    	var r2time = 25;
    </script>
    
    
    
    
    
    
    <script src="inc/style.css"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
	<script src="ratchet/ratchet.js"></script>
	<script src="inc/jquery-2.0.3.js"></script>
	<!-- <script src="inc/angular.js"></script>  -->
	<!-- <script src="inc/main.js"></script> -->
	<link rel="stylesheet" type="text/css" href="ratchet/ratchet.css" />
    <script>
	// Create an object containing LatLng, population.
	var citymap = {};
		<?php
		foreach($current_lat as $count => $current_lat_counted)
		{
			echo "citymap[" . $count . "] = {\n";
			echo "center: new google.maps.LatLng(" . $current_lat[$count] . ", " . $current_long[$count] . "),\n";
			echo "population: " . $current_crime[$count] . "\n";
			echo "};\n\n";
		}
		?>
	var cityCircle;

	function initialize() {
	  var mapOptions = {
		zoom: 6,
		center: new google.maps.LatLng(53.1142, 2.5771),
		mapTypeId: google.maps.MapTypeId.TERRAIN
	  };

	  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

	  var flightPlanCoordinates = [
	  <?php
		$count = 1; // 1=lat, 2=long
		$inc = 0; // 
	  
		foreach($current_polyline_array as $b)
		{
			foreach($b as $current_polyline_array_foreach)
			{
				if($count == 1) 
				{
					$current_polyline_lat[$inc] = $current_polyline_array_foreach;
					$count = 2;
				}
				elseif($count == 2)
				{
					$current_polyline_long[$inc] = $current_polyline_array_foreach;
					$count = 1;
					$inc++;
				}
				//var_dump($current_polyline_array_foreach);
			}
		}
		
		for($a = 0; $a < $inc; $a++)
		{
			echo "new google.maps.LatLng(" . $current_polyline_lat[$a] . ", " . $current_polyline_long[$a] . ")";
			if($a != $inc - 1) echo ",";
			echo "\n";
			//echo $current_polyline_lat[$a];
			//new google.maps.LatLng(37.772323, -122.214897),
		}
	  ?>
      
  ];
  var flightPath = new google.maps.Polyline({
    path: flightPlanCoordinates,
    strokeColor: '#000000',
    strokeOpacity: 1.0,
    strokeWeight: 2
  });

  flightPath.setMap(map);

	  for (var city in citymap) {
		// Construct the circle for each value in citymap. We scale population by 20.
		var populationOptions = {
		  strokeColor: '#FF0000',
		  strokeOpacity: 0.8,
		  strokeWeight: 2,
		  fillColor: '#FF0000',
		  fillOpacity: 0.1,
		  map: map,
		  center: citymap[city].center,
		  radius:  3000
		};
		cityCircle = new google.maps.Circle(populationOptions);
	  }
	}

	google.maps.event.addDomListener(window, 'load', initialize);
    </script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
  </head>
  <body style="margin-top: 43px;">
  	<div id="bar-title">
  		<header class="bar-title">
  			
  			
  			
  			
  			
  			
  			
  			
  			<h1 class="title" id="target">SafeRoute</h1>
  			<div id="target">
  				<script type="text/javascript">
  					$( "#target" ).click(function() {   //when title is clicked, ask for stuff
        				document.startLoc = prompt("Please enter your start location:","Start");
        				document.endLoc = prompt("Please enter your end location:","End");
        				document.transport = prompt("Please enter your mode of transport:","Walk,bike or car");
        				if(document.transport.toLowerCase != "car"){
        					if(document.transport.toLowerCase != "bike") {
        						if(document.transport.toLowerCase != "walk") {
        							alert("Mode of transport is incorrect. Try again with walk, bike or car.");
        						}
        					}
        				}
    				});
    				//take these values in and output a link of some kind.
  				</script>
  			</div>
  			<!--
  			bottom bar with four counters, change val based on crime
  			-->
  			<center>
  		  		<nav class="bar-tab">
  		  			<div ng-controller="RouteFind">
  		  				<span class="count-main">Time→</span>
  						<span class="count"><script>document.write(r1time);</script></span>
  						<span class="count-positive"><script>document.write(r2time);</script></span>
  						<span class="count"><script>document.write(r1safe);</script></span>
  						<span class="count-positive"><script>document.write(r2safe);</script></span>
  						<span class="count-negative">←Safety</span>
  					</div>
  				</nav>
  			</center>
  			
  			
  			
  			
  			
  			
  			
  			
  			
  		</header>
  	</div>
    <div id="map-canvas"></div>
  </body>
</html>