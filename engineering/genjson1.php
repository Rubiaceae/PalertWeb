<?php
require("sql_dbinfo.php");

$term = $_GET['term'];

$connection = mysql_connect('localhost', $username, $password);
if (!$connection) {
	die('Not connected : ' . mysql_error());
}

mysql_query("SET NAMES 'UTF8'");

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
	die ('Can\'t use db : ' . mysql_error());
}

$query = sprintf("SELECT station, area, locname, indexs FROM PalertList" . 
		" WHERE station LIKE '%s%%' OR serial LIKE '%s%%' OR locname LIKE '%s%%' LIMIT 15;",
		mysql_real_escape_string($term),
		mysql_real_escape_string($term),
		mysql_real_escape_string($term));

$result = mysql_query($query);
if (!$result) {
	die('Invalid query: ' . mysql_error());
}

$arr = array();

for($i=0; $i<mysql_num_rows($result); $i++) {
	$row = mysql_fetch_array($result);
	$label = $row["locname"].".".$row["station"].".".$row["area"];
	$value = (int)$row["indexs"] - 1;
	$sta = Array("label"=>$label, "value"=>$value);
	$arr[] = $sta;
}

mysql_free_result($result);
mysql_close($connection);

echo json_encode($arr);
?>

