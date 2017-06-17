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

$query = sprintf("SELECT indexs, %s FROM Shakemap_a;", $columns[$tmp]);

$result = mysql_query($query);
if (!$result) {
	die('Invalid query: ' . mysql_error());
}

$arr = array();

for($i=0; $i<mysql_num_rows($result); $i++) {

	$row = mysql_fetch_array($result);
	$value = (float)$row[1];
	$level = 0;

	if ( $value < 0.0 ) $level = 0; 
	else $level = 1;

	$arr[(int)$row[0] - 1] = $level;
}

mysql_free_result($result);
mysql_close($connection);

echo json_encode($arr);
?>

