<?php
require("sql_dbinfo.php");

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

$connection = mysql_connect ('localhost', $username, $password);
if (!$connection) {
	die('Not connected : ' . mysql_error());
}

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
	die ('Can\'t use db : ' . mysql_error());
}

mysql_query("SET NAMES 'UTF8'");

$query = "SELECT station, serial, area, locname, ROUND(latitude,6) as latitude, ROUND(longitude,6) as longitude, floor, ip, indexs FROM PalertList";
$result = mysql_query($query);
if (!$result) {
	die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");


while ($row = @mysql_fetch_assoc($result)){
	$node = $dom->createElement("marker");
	$newnode = $parnode->appendChild($node);
	$newnode->setAttribute("station",$row['station']);
	$newnode->setAttribute("serial", $row['serial']);
	$newnode->setAttribute("area", $row['area']);
	$newnode->setAttribute("locname", $row['locname']);
	$newnode->setAttribute("lat", $row['latitude']);
	$newnode->setAttribute("lng", $row['longitude']);
	$newnode->setAttribute("floor", $row['floor']);
	$newnode->setAttribute("ip", $row['ip']);
	$newnode->setAttribute("indexs", $row['indexs']);
}

mysql_free_result($result);
mysql_close($connection);

echo $dom->saveXML();
?>
