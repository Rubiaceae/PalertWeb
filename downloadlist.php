<?php
require("sql_dbinfo.php");


$connection = mysql_connect('localhost', $username, $password);
if (!$connection) {
	die('Not connected : ' . mysql_error());
}

mysql_query("SET NAMES 'UTF8'");

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
	die ('Can\'t use db : ' . mysql_error());
}

$query = sprintf("SELECT station, longitude, latitude, elevation, floor, gain_z, gain_n, gain_e FROM PalertList");

$result = mysql_query($query);
if (!$result) {
	die('Invalid query: ' . mysql_error());
}

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=palertlist.csv");

$outputBuffer = fopen("php://output", 'w');
$outputformat = sprintf("station,longitude,latitude,elevation,floor,gain_z,gain_n,gain_e\n");
fwrite($outputBuffer, $outputformat);

for($i=0; $i<mysql_num_rows($result); $i++) {
	$row = mysql_fetch_array($result);
	$outputformat = sprintf("%s,%s,%s,%s,%s,%s,%s,%s\n", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
    fwrite($outputBuffer, $outputformat);
}

mysql_free_result($result);
mysql_close($connection);

fclose($outputBuffer);
?>

