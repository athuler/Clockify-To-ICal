<?php

// Initialization
header("Content-Type: text/calendar;charset=utf-8");
//echo get_include_path() . "\n";
//echo("\n");

$path = realpath("icalendar/zapcallib.php");
//set_include_path(realpath("icalendar"));
//echo $path;
require $path ;
$curl = curl_init();
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
require_once("secret.php");

if(!isset($_GET["key"])) {
	exit("ERROR - Missing API Key");
}
$apiKey = $_GET["key"];

$numItems = 50;
if(isset($_GET["num_items"]) and is_int(intval($_GET["num_items"]))) {
	$numItems = intval($_GET["num_items"]);
}

// Create calendar


$icalobj = new ZCiCal();

//echo("IMPORTED CALENDAR");

// Track Usage
curl_setopt_array($curl, [
	CURLOPT_URL => "https://in.logs.betterstack.com/",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_HTTPHEADER => [
			"Content-Type: application/json",
			"Authorization: " . $BETTERSTACK_API_TOKEN
	],
	CURLOPT_POSTFIELDS => json_encode(array(
		"IP" => $_SERVER['REMOTE_ADDR'],
		"User Agent" => $_SERVER['HTTP_USER_AGENT']
	)),
]);
$response = curl_exec($curl);
$err = curl_error($curl);
//echo var_dump($response);


// Get User's ID and Workspace ID
$url = "https://api.clockify.me/api/v1/user";
curl_setopt_array($curl, [
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
			"X-Api-Key: $apiKey"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);


if ($err) {
	exit("ERROR (cURL) - " . $err);
}

if (curl_getinfo($curl)['http_code'] != 200 and curl_getinfo($curl)['http_code'] != 202) {
	exit("ERROR - LOGGING");
}

$res = json_decode($response, true);
//echo var_dump($res);

if (!isset($res["defaultWorkspace"]) or !isset($res["id"])) {
	exit("ERROR - Wrong Clockify response! they lied to us:(");
}

$workspaceID = $res["defaultWorkspace"];
$userID = $res["id"];

// Get projects list

$url = "https://api.clockify.me/api/v1/workspaces/$workspaceID/projects?page-size=1000";
curl_setopt_array($curl, [
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
			"X-Api-Key: $apiKey"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
if ($err) {
	exit("ERROR (cURL) - " . $err);
}

if (curl_getinfo($curl)['http_code'] != 200) {
	exit("ERROR - No Projects!");
}

$res = json_decode($response, true);
$projects = array();
foreach ($res as $project) {
	$projects[$project["id"]] = $project["name"];
}
//var_dump($projects);




// Get time entries


$url = "https://api.clockify.me/api/v1/workspaces/$workspaceID/user/$userID/time-entries?page-size=$numItems&in-progress=False";

curl_setopt_array($curl, [
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
			"X-Api-Key: $apiKey"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);




if ($err) {
	exit("ERROR (cURL) - " . $err);
}

if (curl_getinfo($curl)['http_code'] != 200) {
	exit("ERROR - Something weird went wrong");
}

$res = json_decode($response, true);
//echo var_dump($res);
foreach ($res as $time_entry) {
	
	/*echo "Title: " . $projects[$time_entry["projectId"]] . "<br/>";
	echo "Description: " . $time_entry["description"] . "<br/>";
	echo "Start: " . $time_entry["timeInterval"]["start"] . "<br/>";
	echo "End: " . $time_entry["timeInterval"]["end"] . "<br/><br/>";*/
	
	$eventobj = new ZCiCalNode("VEVENT", $icalobj->curnode);
	$eventobj->addNode(new ZCiCalDataNode("SUMMARY:" . $projects[$time_entry["projectId"]]));
	$eventobj->addNode(new ZCiCalDataNode("DESCRIPTION:" . $time_entry["description"]));
	$eventobj->addNode(new ZCiCalDataNode("DTSTART:" . ZCiCal::fromSqlDateTime($time_entry["timeInterval"]["start"])));
	$eventobj->addNode(new ZCiCalDataNode("DTEND:" . ZCiCal::fromSqlDateTime($time_entry["timeInterval"]["end"])));
	$eventobj->addNode(new ZCiCalDataNode("DTSTAMP:" . ZCiCal::fromSqlDateTime()));
	$uid = date('Y-m-d-H-i-s') . random_int(111111,1000000000000) . "@clockifytoical.com";
	$eventobj->addNode(new ZCiCalDataNode("UID:" . $uid));
}


// Close
curl_close($curl);
echo $icalobj->export();
?>