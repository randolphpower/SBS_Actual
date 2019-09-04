<?php
if(!session_id()) session_start();
include("modelo/conectarBD.php");

$sql = "SELECT * FROM relacion_cliente_juicio";
$resultados = mysql_query($sql, $conexion) or die(mysql_error());
mysql_close($conexion);

$i=1;
while ($reg = mysql_fetch_array($resultados))
{
   $var .= $reg["NUM_JUICIO"].";".$reg["ID_CLIENTE"].";".$reg["CECRTID"].";".$reg["CEDOSSIERID"].";".$reg["CETYPE"].";".$reg["nombre"]."\n";

	$i++;
	
}

header("Content-Description: File Transfer");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=export_".date("d_m_Y").".csv");
echo $var;

?>
