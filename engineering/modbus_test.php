<?php
require("modbus_new.php");

function genIP_row($row_num, $status, $ip_0, $ip_1, $ip_2, $ip_3) {
	$r_ip = sprintf("%d.%d.%d.%d", $ip_0, $ip_1, $ip_2, $ip_3);
	$inputbox = sprintf("<input type='number' name='ip0' value='%d' max='255' min='0' style='width:40px;'>." .
						"<input type='number' name='ip1' value='%d' max='255' min='0' style='width:40px;'>." .
						"<input type='number' name='ip2' value='%d' max='255' min='0' style='width:40px;'>." .
						"<input type='number' name='ip3' value='%d' max='255' min='0' style='width:40px;'>",
						$ip_0, $ip_1, $ip_2, $ip_3);

	$label = 'TCP1';

	switch($row_num) {
		case 1:
			break;
		case 2:
			$label = 'TCP2';
			break;
		case 3:
			$label = 'NTP';
			break;
		default:
			break;
	}

	$row = sprintf("<tr id='row_%d'><td><div class='%s'></div></td><td>%s:</td><td>%s</td>" .
					"<td><input type='button' class='contentbutton' value='Edit' onclick='switchUtility(%d);'/></td></tr>",
					$row_num, $status ? "indicator":"indicator_not", $label, $r_ip, $row_num);

	$row .= sprintf("<tr id='row_%d_e' style='display:none;'><td><div class='%s'></div></td><td>%s:</td><td>%s</td>" .
					"<td><input type='button' class='contentbutton' value='Save' onclick='writeUtility(%d)'/></td></tr>",
					$row_num, $status ? "indicator":"indicator_not", $label, $inputbox, $row_num);

	return $row;
}

$ip = $_GET['ip'];

if ($ip) {
	$modbus = new ModbusMaster($ip, "TCP");
} else {
	return null;
}

if (!($data = $modbus->fc3(1, 0x0063, 100))) return null;

$ntp_s = $data[1] & 0x01;
$tcp0_s = $data[1]>>1 & 0x01;
$tcp1_s = $data[1]>>2 & 0x01;
$fw_ver = (int)(($data[198]<<8) + $data[199]);

$r_tcp0 = genIP_row(1, $tcp0_s, $data[152], $data[153], $data[154], $data[155]); 
$r_tcp1 = genIP_row(2, $tcp1_s,  $data[156], $data[157], $data[158], $data[159]);
$r_ntp = genIP_row(3, $ntp_s,  $data[143], $data[145], $data[147], $data[149]);
$r_fw = sprintf("<tr id='row_4'><td></td><td>F/W:</td><td>%d</td>" .
				"<td><input type='button' class='contentbutton' value='Upd' onclick='writeUtility(4)'/></td></tr>",
				$fw_ver);

$html = sprintf("<table>" .
				"%s%s%s%s" .
				"<tr style='display:none;'><td><input type='text' id='b_ip' value='%s'/></td></tr>" .
				"</table>", $r_tcp0, $r_tcp1, $r_ntp, $r_fw, $ip);

echo $html;
?>
