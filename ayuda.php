<?php 
require_once("/modelo/consultaSQL.php");
require_once("/modelo/conectarBD.php");
date_default_timezone_set('UTC');

$mysqli = new mysqli($host, $usuario, $password, $basedatos);


$f=strval(date('Y-m-d H:i:s'));
$f2 = substr($f,5,2) . substr($f,8,2) . substr($f,0,4);

$sql = "SELECT COUNT(*) as total FROM servicobranza.plano200 WHERE FECHA = '{$f2}'";

$resultado = $mysqli->query($sql);
 
$data=mysqli_fetch_assoc($resultado);

echo $data['total'];

//echo $sql;
?>