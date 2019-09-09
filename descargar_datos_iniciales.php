<?php
if(!session_id()) session_start();
include("modelo/conectarBD.php");

$sql = "SELECT * FROM juicios_dato_inicial";
$resultados = mysql_query($sql, $conexion) or die(mysql_error());
mysql_close($conexion);

$i=1;
while ($reg = mysql_fetch_array($resultados))
{
   $var .= $reg["id_juicio"].";".$reg["tipo_juicio"].";".$reg["rut"].";\n";

	$i++;
	
}

header("Content-Description: File Transfer");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=datos_iniciales_".date("d_m_Y").".csv");
echo $var;

?>
