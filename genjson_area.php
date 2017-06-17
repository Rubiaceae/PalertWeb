<?php
require("sql_dbinfo.php");

$term = $_GET['area'];

$connection = mysql_connect('localhost', $username, $password);
if (!$connection) {
	die('Not connected : ' . mysql_error());
}

mysql_query("SET NAMES 'UTF8'");

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
	die ('Can\'t use db : ' . mysql_error());
}

$query = sprintf("SELECT indexs FROM PalertList" . 
		" WHERE area='%s';",
		mysql_real_escape_string($term));

$result = mysql_query($query);
if (!$result) {
	die('Invalid query: ' . mysql_error());
}

$arr = Array();

for($i=0; $i<mysql_num_rows($result); $i++) {
	$row = mysql_fetch_array($result);
	$value = (int)$row["indexs"] - 1;
	$sta = Array("indexs"=>$value);
	$arr[] = $sta;
}

mysql_free_result($result);
mysql_close($connection);

echo json_encode($arr);
?>

