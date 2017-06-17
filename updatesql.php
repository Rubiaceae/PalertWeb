<?php
require("sql_dbinfo.php");

$locname = $_GET['locname'];
$area = $_GET['area'];
$station = $_GET['station'];
$serial = $_GET['serial'];
$floor = $_GET['floor'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$ip = $_GET['ip'];

$connection = mysql_connect('localhost', $username, $password);
if (!$connection) {
	die('Not connected : ' . mysql_error());
}

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
	die('Can\'t use db : ' . mysql_error());
}

mysql_query("SET NAMES 'UTF8'");

$query = sprintf("UPDATE PalertListStatic SET" .
		" locname='%s', area='%s', serial=%s, floor=%s, longitude=%s, latitude=%s, ip='%s'" .
		" WHERE station='%s' LIMIT 1;",
		mysql_real_escape_string($locname),
		mysql_real_escape_string($area),
		mysql_real_escape_string($serial),
		mysql_real_escape_string($floor),
		mysql_real_escape_string($lng),
		mysql_real_escape_string($lat),
		mysql_real_escape_string($ip),
		mysql_real_escape_string($station));

$result = mysql_query($query);

if (!$result) {
	die('Invalid query: ' . mysql_error());
}

$query = sprintf("UPDATE PalertList target," .
		" (SELECT * FROM PalertListStatic WHERE station='%s' LIMIT 1) source" .
		" SET target.locname=source.locname, target.area=source.area, target.serial=source.serial," .
		" target.floor=source.floor, target.longitude=source.longitude, target.latitude=source.latitude, target.ip=source.ip" .
		" WHERE target.station=source.station;",
		mysql_real_escape_string($station));

$result = mysql_query($query);

if (!$result) {
	die('Invalid query: ' . mysql_error());
}

mysql_free_result($result);
mysql_close($connection);
?>

