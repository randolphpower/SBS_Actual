<?php 
 session_start();
 require_once("/modelo/consultaSQL.php");
 require_once("/modelo/conectarBD.php");



$accion = $_POST['accion'];

$mysqli = new mysqli($host, $usuario, $password, $basedatos);
$sql = "select distinct nom_respuesta,cod_respuesta from servicobranza.vcdials where cod_accion = '".$accion."' ";
$resultado = $mysqli->query($sql);


while($row  = $resultado->fetch_assoc()){ 
    $result .="<option value=".$row['cod_respuesta'].">".$row['nom_respuesta']."</option>";
    
 } 
echo '<option value="" selected>-- SELECCIONE -- </option>'.$result;

?>