<?php
session_start();

// Waterbody page (take 2)
// this is like a "place page" for a body of water
// depending on permissions, you may only be able to see the name of the waterbody

?>
<link rel='stylesheet' href='css/style.css' type="text/css" />

<script>
// CHANGEHTML function
// go find an HTML element with the id "id" and change it to "newtext"

function changeHTML(id,newtext)
{
	document.getElementById(id).innerHTML=newtext
}		
</script>

<?
include 'config.php';
include 'connect.php';
include 'header.php';

print "$header";

$waterbodyname = $_GET['name'];
$edit = $_GET['edit'];
// go get the info about the specific body of water ($waterbodyname)

$waterbodyresult = mysql_query("
SELECT * 
FROM waterbodies 
WHERE name = '$waterbodyname'
		");
		
// take the data and put it into an array
$waterbodyrow = mysql_fetch_array($waterbodyresult);

// get all the variables WE DON'T NEED THIS SINCE iT"S JUST ONE!!
//while($row = mysql_fetch_array($result))
	//{
	
// these are things we care about on water.php:
$watertype = $waterbodyrow['watertype'];
$lat = $waterbodyrow['lat'];
$lon = $waterbodyrow['lon'];
$city = $waterbodyrow['city'];
$county = $waterbodyrow['county'];
$state = $waterbodyrow['state'];
$notes = $waterbodyrow['notes'];
$owner = $waterbodyrow['owner'];
$species = $waterbodyrow['species'];
$privacy = $waterbodyrow['privacy'];
$creator = $waterbodyrow['creator'];
$id = $waterbodyrow['id'];



?>

<div id='main'>
		   <nav>nav</nav>
    <aside>
    	<box>
			
			ASIDE BOX
		</box></aside>
		
    <article>
    	
<!-- THE HEADING - waterbody name -->
<box>
  <?

// if no name sent as part of the URL, don't show anything
if (!isset($_GET['name']))
	{
		print "no waterbody selected";
		die;
	}
else
	{
	print "<h1>$waterbodyname</h1>";
	}

?>
</box>

<!-- THE SUBHEADING - waterbody basic info -->
<box>
	<? 
	print "$city, $state<br>
	<pre>privacy is $privacy</pre>";
	?>	
</box>

<!-- MAP -->
	<!-- GOOGLE MAPS API map -->
<script src="http://maps.google.com/maps?file=api&amp;v=3&amp;key=AIzaSyCKgYN-19kduAW-dvjZADLJ48VEOV2PxP4"
      type="text/javascript"></script>
<box>      
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        margin: 0;
        padding: 0;
        height: 100%;
      }
    </style>

<?php 
$currentuser = $_SESSION['myusername'];
// kml file= waterID_userID.kml
$kmlfile = $id . "_" . $currentuser . "." . "kml";
print "kml file: $kmlfile";
$kmlurl = "http://cb.hopto.org/log/kml/waterbodies/" . $kmlfile;
print "kml URL: $kmlurl";

?>    
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
    
  
var map;
function initialize() {
  var chicago = new google.maps.LatLng(41.875,-87.624)
    // the LAT and LON of this particular waterbody
   var waterbodyLatLng = new google.maps.LatLng(<?php echo $lat ?>,<?php echo $lon ?>)
  var mapOptions = {
    zoom: 13,
    center: waterbodyLatLng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);
      
      
  var myLatlng = new google.maps.LatLng(-25.363882,131.044922)
 
   
  // the marker for the particular LAT and LON of this waterbody
   var marker = new google.maps.Marker({
      position: waterbodyLatLng,
      map: map,
      title: 'Hello World!'
   });
   
  
   // BUCK CREEK layer variable 
   // kml file= waterID_userID.kml
   // buck creek = 70_1.kml
    
  var buckLayer = new google.maps.KmlLayer({
    url: '<?php echo $kmlurl; ?>'	// apparently this has to be a full URL, not a filepath
  });
  
  buckLayer.setMap(map);
  
  
      
  
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>

<div id="map-canvas"></div>

</box>
<!-- NOTES - notes about the waterbody -->
<box>
	<? 
	print "<h3>Notes</h3>";

if(isset($_SESSION['myusername']) && $edit ==1 )
// if editing is turned on...
{
// put the notes in an input box
?> <script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea"
 });
</script>
<form action="editwaterbodynotes.php" method="get" target="_blank">
<textarea style="width: 55%; height: 100px" name="notes" value="notes"><?php echo $notes ?></textarea>
editing waterbody ID: <input type="text" class='input' name="waterbodyid" value="<? echo $id ?>" readonly>
<input type="submit" value="save" name="submit">
</form>

<?
}
else
{
print "<p id='notes'>$notes</p>";
print "<a href='waternew.php?name=$waterbodyname&edit=1'><i>edit</i></a>";
}
?>

	</box>

<!-- SPECIES CAUGHT THERE -->
<box>
	<?
	print "<h3>Species</h3>";
	print $species;
	
	// IT WOULD BE COOL to search past trips and see what
	// fish were ACTUALLY caught there (i.e. "confirmed" species?)
	
	// get information about trips
	// that happened there
	$fishcaughtquery = 
	"SELECT * 
	FROM trips 
	WHERE waterbody like '%$waterbodyname%'
	AND watertype = '$watertype'
	AND state = '$state'";
	
	$fishcaughtthereresults = mysql_query($fishcaughtquery);
	
	print "<br>looking for <pre>$fishcaughtquery</pre>";
			
	$fishcaughtthere = mysql_fetch_array($fishcaughtthereresults);
	while($fishcaughtthere = mysql_fetch_array($fishcaughtthereresults))
	{
	// $tripdate = $fishcaughtthere['date'];
	
		}
	
?>
</box>

<!-- WHO FISHED HERE?-->
<box>
<?
print "<h3>Who's fished here?</h3>";
	// get information about trips
	// that happened there
	$whofishedherequery = 
	"SELECT DISTINCT username
	FROM trips 
	WHERE waterbody like '%$waterbodyname%'
	AND watertype = '$watertype'
	AND state = '$state'";
	
	$whofishedresults = mysql_query($whofishedherequery);
	
	print "looking for <pre>$whofishedherequery</pre>";
			
	//$whofishedthere = mysql_fetch_array($whofishedresults);
	while($whofishedthere = mysql_fetch_array($whofishedresults))
	{
	$username = $whofishedthere['username'];
	print "<a href='user.php?username=$username'>$username</a>" . " ";
	
		}

?>
</box>
</article>



