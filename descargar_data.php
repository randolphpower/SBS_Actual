<?php
if(!session_id()) session_start();
include("modelo/conectarBD.php");

$sql = "SELECT * FROM op_info_juicios";
$resultados = mysql_query($sql, $conexion) or die(mysql_error());
mysql_close($conexion);

$i=1;
while ($reg = mysql_fetch_array($resultados))
{
   $var .= $reg["CNCASENO"].";".$reg["CESSNUM"].";".$reg["CEDOSSIERID"].";".$reg["CECRTID"].";".$reg["CETYPE"].";".$reg["CESENDDT"]."\n";

	$i++;
	
}

header("Content-Description: File Transfer");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=export_".date("d_m_Y").".csv");
echo $var;

?>
