<?php
require("sql_dbinfo.php");

$columns = array( "even_sec", "odd_sec" );

$connection = mysql_connect('localhost', $username, $password);
if (!$connection) {
	die('Not connected : ' . mysql_error());
}

mysql_query("SET NAMES 'UTF8'");

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
	die ('Can\'t use db : ' . mysql_error());
}

$tmp = time() % 2;

$query = sprintf("SELECT station, %s FROM Shakemap_a;", $columns[$tmp]);

$result = mysql_query($query);
if (!$result) {
	die('Invalid query: ' . mysql_error());
}

$arr = array();

for($i=0; $i<mysql_num_rows($result); $i++) {

	$row = mysql_fetch_array($result);
	$station = $row["station"];
	$value = (float)$row[1];
	$sta = Array("station"=>$station, "value"=>$value);
	$arr[] = $sta;
}

mysql_free_result($result);
mysql_close($connection);

echo json_encode($arr);
?>

