<?php
set_time_limit(0);
date_default_timezone_set("America/Santiago");
$host="localhost";
$usuario="root";
$basedatos="servicobranza";
$password="12345678";
$conexion=mysql_connect($host,$usuario,$password) or die(header("Location: pages/index.php?mensaje=la conexion a la base de datos no se pudo establecer, intentelo mas tarde"));
mysql_select_db($basedatos,$conexion) or die(header("Location: pages/index.php?mensaje=la conexion a la base de datos no se pudo establecer, intentelo mas tarde"));
?>