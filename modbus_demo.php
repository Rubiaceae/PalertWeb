<?php
require("modbus_new.php");

$ip = $_GET['ip'];
$level = $_GET['level'];

if ($ip) {
	$modbus = new ModbusMaster($ip, "TCP");
} else {
	return null;
}

if ($level > 0) {
	$data = array($level, 0x0005);
} else {
	$data = array(0x0003, 0x0005);
}

$datatype = array("BYTE", "BYTE");

if (!($modbus->fc16(1, 0x00C5, $data, $datatype))) return null;

echo "Demo starting!";
?>
