<?php
require("sql_dbinfo.php");

$indexs = (int)$_GET['indexs'] + 1;

$columns = array( "even_sec", "odd_sec" );
$columnslp = array( "even_sec_l", "odd_sec_l" );
$columnssp = array( "even_sec_s", "odd_sec_s" );

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

/* PGA part */
$query = sprintf("SELECT %s FROM Shakemap_a WHERE indexs=%d LIMIT 1;", $columns[$tmp], $indexs);
$result = mysql_query($query);
if (!$result) {
	die('Invalid query: ' . mysql_error());
}
$row = mysql_fetch_array($result);
$pga = (float)$row[0];
mysql_free_result($result);

/* Sa part */
$query = sprintf("SELECT %s, %s FROM Shakemap_sa WHERE indexs=%d LIMIT 1;", $columnslp[$tmp], $columnssp[$tmp], $indexs);
$result = mysql_query($query);
if (!$result) {
	die('Invalid query: ' . mysql_error());
}
$row = mysql_fetch_array($result);
$sa1 = (float)$row[0];
$sas = (float)$row[1];
mysql_free_result($result);

$html = sprintf("<table>" .
				"<tr><td>Acc:</td><td>%4.2f gal</td></tr>" .
				"<tr><td>Vel:</td><td>N/A</td></tr>" .
				"<tr><td>Dis:</td><td>N/A</td></tr>" .
				"<tr><td>Sa1:</td><td>%4.2f gal</td></tr>" .
				"<tr><td>Sas:</td><td>%4.2f gal</td></tr>" .
				"</table>", $pga, $sa1, $sas);

mysql_close($connection);
echo $html;
?>

