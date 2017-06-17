<?php
require("sql_dbinfo.php");

$area = $_GET['area'];

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

$query = sprintf("SELECT indexs FROM PalertList" . 
		" WHERE area='%s';",
		mysql_real_escape_string($area));

$result = mysql_query($query);
if (!$result) {
	die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");


while ($row = @mysql_fetch_assoc($result)){
	$node = $dom->createElement("marker");
	$newnode = $parnode->appendChild($node);
	$newnode->setAttribute("indexs", (int)$row['indexs'] - 1);
}

mysql_free_result($result);
mysql_close($connection);

echo $dom->saveXML();
?>

