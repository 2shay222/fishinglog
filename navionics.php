<?
session_start();
?>
<html>
 <head>
 <script type="text/javascript" src="http://webviewer-api.navionics.com/static/js/proj4js/lib/proj4js-compressed.js"></script>
 <script type="text/javascript" src="http://webviewer-api.navionics.com/static/js/OpenLayers/OpenLayers.js"></script>
 <script type="text/javascript" src="http://webviewer-api.navionics.com/static/js/navionics/apimaps.js"></script>
 </head>
 
 <?
 
 include 'config/config.php';
 include 'config/connect.php';
 include 'header.php';
 print "</div>";
 
 print "<link rel='stylesheet' href='$stylesheet' type='text/css' />";
 
 ?>

   <?
 print $header;
 
 // SET THE STARTING LOCATION BASED ON THE USER'S GPS
 	$username = $_SESSION['myusername'];
	
	$userhomequery = mysql_query("
	SELECT gps FROM users WHERE USERNAME = '$username';
		");
	// put into an array	
	$userhomearray = mysql_fetch_array($userhomequery);
	
	// save that into a variable
	$userhome = $userhomearray['gps'];
	
	//print "user home is $userhome";
	$userlatlon = explode(",", $userhome);
	$userlat = $userlatlon[0];
	$userlon = $userlatlon[1];
	
	// if there is no user logged in, show something
	//		near gun lake, MI: 42.56935918843573, -85.44960021972656
	if (!isset($_SESSION['myusername']))
		{
			$userlat = '42.56935918843573';
			$userlon = '-85.44960021972656';
		}
		
	

	
 ?>
 <!--body onload="startmap()" -->
 <body onload="init()" >
 <div id="mymap" class="NavionicsMap" style="width:100%; height:100%; margin-top: 52px; position: fixed;"> </div>
 <script>
 // use body onload="startmap()" to starts all maps on load
 key = 'Navionics_webapi_00250'
 function init(){
 var mapOptions = {
 center: {
 lon: <? echo $userlon; ?>,
 lat: <? echo $userlat; ?>
 },
 zoom: 8
 } 
 map = new NavionicsMap("mymap", key, mapOptions); 
 /*
* You can also set the center and the zoom level manually just calling these methods
 map.setCenter(10, 43);
 map.setZoom(4);
 */
 return map
 }
 </script>
 
 </body>
</html> 