<?php
session_start();
// UPDATE WATERBODY

?>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<?

include 'config.php';
include 'connect.php';
include 'header.php';

print $header;
///////////////////// RESULTS //////////////////////
print "<br><br><br><br>";
// get the current time (for "last updated" field in DB)
$now = date("Y-m-d H:i:s");		// set the $now in UTC (for "last update" column)


///////////////////// GET THE CURRENT DATETIME //////////////////////
$now = date("Y-m-d");
print "now is $now<br>";

////////////// GET STUFF ////////////////
$waterbodyname = $_GET['waterbodyname'];
print "the waterbody name is: $waterbodyname<br>";
$notes = $_GET['notes'];
$lures = $_GET['lures'];
$watertype = $_GET['watertype'];
$lat = $_GET['lat'];
$lon = $_GET['lon'];
$latlon = $lat . "," . $lon;
$username = $_SESSION['myusername'];
$privacy = $_GET['privacy'];

//////////////////// FIX APOSTROPHES /////////////////////////////////////////
// make it so apostrophes work
$notes = mysql_real_escape_string($_GET[notes]);
$waterbodyname = mysql_real_escape_string($_GET[waterbodyname]);
$lures = mysql_real_escape_string($_GET[lures]);
$species = mysql_real_escape_string($_GET[species]);

print "the notes are $notes<br>";
print "the name is: $name and the watertype is: $watertype <br>";

///////////////////// GET THE CITY/STATE/ZIP //////////////////////
	// this takes LAT and LON and gives us the city, state, county, and zip
include 'findcity.php';
	// $state, $city, $county, $zip

//////////////////// FIND THE NEAREST STREAMFLOW GAUGE //////////////////////
	// this takes the waterbody name and city and tries to find the closest USGS gauge
include 'findgauge.php';
	// $site_no
	
	
//////////////////// SEND TO THE DATABASE! whoohoooo //////////////////////
$sql="UPDATE waterbodies SET 
	name = '$waterbodyname',
	watertype = '$watertype',
	city = '$city',
	county = '$county',
	state = '$state',
	zip = '$zip',
	species = '$species',
	lures = '$lures',
	notes = '$notes',
	lat = '$lat',
	lon = '$lon',
	latlon = '$latlon',
	creator = '$username',
	date_created = '$now',
	gauge_id = '$site_no',
	privacy = '$privacy'
	WHERE name = '$waterbodyname'
	AND state = '$state'";
	
// show us the query
print "<br> -------- THE SQL QUERY --------- <br><pre> $sql </pre>";

// Tell us the results
if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());							// error message
	}

mysql_close($con);
print "THIS IS AFTER THE CONNECTION CLOSES";
?>
	



