<?php
/*
前面显示频率，中间只显示一行ipqam，

*/
 ?>


<?php
header ( 'Content-Type: text/html; charset="utf8"' ); //设置页面编码
include("./include/authcss.php");
include("./include/top_header.php");
include ("./css/include/conn.php");

$sql_ipqams = "select * from ipqams";
$result_ipqams = mysql_query ( $sql_ipqams );

echo "<table class='css_table_tr' style='width: 996px;'>
		<tr bgcolor='#00438C'>
			<td colspan='3' class='textHeaderDark'  style='font-size: 12px; font-weight: bold;'>IPQAM查询</td>
		</tr>
		<tr bgcolor='#6d88ad' class='textSubHeaderDark' style='font-size: 12px;'>
		<th class='css_table'>频点 </th>	
		<th class='css_table'> IPQAM</th>
			<th class='css_table'>vip</th>
		</tr>";
//		频点 	IPQAM
$mantissa = 0;
while ( $row_ipqams = mysql_fetch_array ( $result_ipqams ) ) {
	$mantissa = $mantissa % 2;
	
	//输出频点
	$sql_channels = "SELECT * FROM channels 
						WHERE ipqam_id 
						in (SELECT id FROM ipqams where ip='$row_ipqams[ip]')";
	$result_channels = mysql_query ( $sql_channels );
	echo "<tr>";
	echo "<td class=" . "css_table" . "$mantissa" . ">";
	echo "<table>";
	while ( $row_channels = mysql_fetch_array ( $result_channels ) ) {
		echo "<tr>";
		echo "<td class=" . "css_table" . "$mantissa" . ">" . $row_channels ['frequence'] . "</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "</td>";
	
	echo "<td class=" . "css_table" . "$mantissa" . ">" . $row_ipqams ['ip'] . "</td>";
	


	$result_channel = mysql_query ( $sql_channels );
	echo "<td class=" . "css_table" . "$mantissa" . ">";
	echo "<table>";	
	while ( $row_channel= mysql_fetch_array ( $result_channel ) ) {
	$sql_current = "select vip 
						   from current_clients
						   where access_point_id 
						   in (SELECT id FROM access_points 
						   where ipqam_id='$row_ipqams[id]' and channel_id= '$row_channel[id]')";
	$result_current = mysql_query ( $sql_current );
	$row_current = mysql_fetch_array ( $result_current ); 
	if(empty($row_current)){
		echo "<tr>";
		echo "<td class=" . "css_table" . "$mantissa" . ">"  ."&nbsp;"."</td>";	
		echo "</tr>";
	}else{
		$result_current = mysql_query ( $sql_current );
		while ($row_current = mysql_fetch_array ( $result_current ) ) {
			echo "<tr>";
			echo "<td class=" . "css_table" . "$mantissa" . ">" . $row_current ['vip'] ."</td>";	
			echo "</tr>";
		}
	}
	}
	
	echo "</table>";
	echo "</td>";
	echo "</tr>";
	$mantissa = $mantissa + 1;
}
echo "</table>";
?>

<?php

include("./include/bottom_footer.php");

?>
