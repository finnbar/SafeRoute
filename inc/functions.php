<?php
set_time_limit(0);
$start_location = $_GET['start'];
$end_location = $_GET['end'];
$transport_method = $_GET['method'];

function GetCrimeRate($lat, $long, $method)
{
	// Method, 0=walk, 1=car, 2=bike
	$total_crimes = 0;
	$total_walking_crimes = 0;
	$total_car_crimes = 0;
	$total_bike_crimes = 0;
	
	$list_walking_crimes = array("all-crime","anti-social-behaviour","criminal-damage-arson","drugs","other-theft","possession-of-weapons","public-order","robbery","shoplifting","theft-from-the-person","violent-crime","other-crime");
	$list_bike_crimes = array("all-crime","other-theft","bicycle-theft","possession-of-weapons","public-order","robbery","shoplifting","theft-from-the-person","violent-crime","other-crime");
	$list_car_crimes = array("all-crime","burglary","other-theft","possession-of-weapons","public-order","robbery","vehicle-crime","other-crime");
	
	$date_year = 2013;
	$date_month = 06;
	
	if(GetIfCrimeRateCached($lat, $long, $method) == false)
	{
		$json_link = "http://data.police.uk/api/crimes-street/all-crime?lat=" . $lat . "&lng=" . $long . "&date=" . $date_year . "-" . $date_month;
		$json_string = file_get_contents($json_link);
		$json = json_decode($json_string, true);
		
		$count = 0;
		foreach($json as $json_get)
		{
			$current_category = $json_get['category'];
			
			if($method == 0) // Walk
			{
				if(in_array($current_category, $list_walking_crimes))
				{
					$count++;
				}
			}
			elseif($method == 1) // Car
			{
				if(in_array($current_category, $list_car_crimes))
				{
					$count++;
				}
			}
			elseif($method == 2) // Bike
			{
				if(in_array($current_category, $list_bike_crimes))
				{
					$count++;
				}
			}
			else
			{
				echo "method takes 0(Walk), 1(Car) or 2(Bike) in GetCrimeRate(double lat, double long, char method)\n";
				exit;
			}
		}
		
		MakeNewCacheFile($lat, $long, $method, $count);
	}
	else
	{
		$count = ReadCacheFile($lat, $long, $method);
	}
	
	return $count;
}

// Get data from JSON file
$json_query = "http://maps.googleapis.com/maps/api/directions/json?region=uk&origin=" . $start_location . "&destination=" . $end_location . "&sensor=false&alternatives=true";
$json_string = file_get_contents($json_query);
$json = json_decode($json_string, true);

$routes = 0;

$start_lat = $json['routes'][$routes]['legs'][0]['start_location']['lat'];
$start_long = $json['routes'][$routes]['legs'][0]['start_location']['lng'];
$end_lat = $json['routes'][$routes]['legs'][0]['end_location']['lat'];
$end_long = $json['routes'][$routes]['legs'][0]['end_location']['lng'];

// Handle all data from JSON file

// Each step of the journey
$count = -1;
foreach($json['routes'][$routes]['legs'][0]['steps'] as $i => $json_get)
{
	$count++;
	$current_lat[$i] = $json_get['end_location']['lat']; // END LAT
	$current_long[$i] = $json_get['end_location']['lng']; // END LONG
	$current_crime[$i] = GetCrimeRate($json_get['end_location']['lat'], $json_get['end_location']['lng'], 0);
	$current_polyline[$i] = $json_get['polyline']['points'];
	$current_polyline_array[$i] = Polyline::Decode($current_polyline[$i]);
	
}
?>