<?php 
session_start();
 require_once("/modelo/consultaSQL.php");
 require_once("/modelo/conectarBD.php");

$mysqli = new mysqli($host, $usuario, $password, $basedatos);


 $sql = "SELECT * FROM  servicobranza.plano200 as sp inner join servicobranza.vcdials as vc ON sp.VCDIAL = vc.cod_vcdial WHERE ";

 if (trim($_GET['min']) != "") {
 
     $arr = explode("/", $_GET['min']);
     $min = $arr[2]."-".$arr[1]."-".$arr[0];
 
     $sql .= "DATE(FECHINGRESO) >= '{$min}' ";
 }
 
 if (trim($_GET['max']) != "") {
     $arr = explode("/", $_GET['max']);
     $max = $arr[2]."-".$arr[1]."-".$arr[0];
     $sql .= "AND DATE(FECHINGRESO) <= '{$max}' ";
 }
 
 if($_GET['accion'] != ""){
     $sql .= "AND CODIGOACCION = '{$_GET['accion']}' ";
 }
 
 if($_GET['respuesta'] != ""){
     $sql .= "AND RESULTADO = '{$_GET['respuesta']}' ";
 }
$resultado = $mysqli->query($sql);
 
$r = mysqli_num_rows($resultado);

if($r <= 1){
    echo $r;
}else{
    echo $r;
}


 
 