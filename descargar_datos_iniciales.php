<?php
if(!session_id()) session_start();
include("modelo/conectarBD.php");
$fecha = $_GET["fecha"];
$fecha = split("/", $fecha);
$fecha = "{$fecha[2]}-{$fecha[1]}-{$fecha[0]}";
$sql = "SELECT * FROM juicios_dato_inicial WHERE fecha_asignacion = '".$fecha."'";
$resultados = mysql_query($sql, $conexion) or die(mysql_error());
mysql_close($conexion);

$i=1;
$var = "Id Juicio;Rut Deudor;Nombre Deudor;Cuenta;Tipo Juicio;Asignacion;Monto Deuda;\n";
while ($reg = mysql_fetch_array($resultados))
{
   $var .= $reg["id_juicio"].";".$reg["rut"].";".$reg["nombre"].";".$reg["cuenta"].";".$reg["tipo_juicio"].";".$reg["fecha_asignacion"].";".$reg["monto"].";\n";

	$i++;
	
}

header("Content-Description: File Transfer");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=asignaciones_".$fecha.".csv");
echo $var;

?>
