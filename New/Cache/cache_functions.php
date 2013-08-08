<?php
function FormatCacheLink($start, $end, $method)
{
	return $start . "-" . $end . "-" . $method . ".json";
}
function GetIfCrimeRateCached($start, $end, $method)
{
	$cache_file_name = FormatCacheLink($start, $end, $method);
	if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/Cache/" . $cache_file_name)) return true;
	else return false;
}
function MakeNewCacheFile($start, $end, $method, $json_pass)
{
	$cache_file_name = FormatCacheLink($start, $end, $method);
	$file_open = fopen($_SERVER['DOCUMENT_ROOT'] . "/Cache/" . $cache_file_name, "w+");
	fwrite($file_open, $json_pass);
	fclose($file_open);
}
function ReadCacheFile($start, $end, $method)
{
	$cache_file_name = FormatCacheLink($start, $end, $method);
	
	$file_open = fopen($_SERVER['DOCUMENT_ROOT'] . "/Cache/" . $cache_file_name, "r");
	$count = fread($file_open, 7);
	fclose($file_open);
	
	return $count;
}
?>