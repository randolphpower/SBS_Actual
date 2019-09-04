<?php
require_once("../modelo/conectarBD.php");
require_once("../modelo/consultaSQL.php");

switch ($_GET["opcion"]) {
	
	case "1":
		
	$rut = strtoupper($_GET["rut"]);
	$nom = $_GET["nombre"];
	$ape = $_GET["apellido"];
	$cor = strtolower($_GET["email"]);
	$pas = $_GET["contrasena"];
	$cel = $_GET["celular"];
	$act = $_GET["activo"];
	$rol = $_GET["rol"];	
		
	$pas = md5($pas);
		
	$usu = strtoupper($nom[0]).strtoupper($ape);
		
	$sql_select_us=$var_select_asterisk_from."usuarios ".$var_where." (US_RUT='".$rut."') ";
	$datos_select = call_select($sql_select_us,"");
	$reg_fil_select = $datos_select['num_filas'];
		
	if($reg_fil_select==0){
		
		$sql_insert_usuario = $var_insert_into."usuarios (US_RUT, US_NOMBRE, US_APELLIDOS, US_USUARIO, US_CLAVE, US_ACTIVO, US_CELULAR, US_EMAIL, rol) ".$var_values."('".$rut."', '".$nom."', '".$ape."', '".$usu."', '".$pas."', '".$act."', '".$cel."', '".$cor."', '".$rol."')";
		$datos = array();
		$datos = call_insert($sql_insert_usuario,"");
		
		echo "inserto";
		exit();
		
	}else{
		
		echo "existe";
		exit();
		
	}	
		
	break;
		
	case "2":
		
	$rut = strtoupper($_GET["rut"]);
	$nom = $_GET["nombre"];
	$ape = $_GET["apellido"];
	$cor = $_GET["email"];
	$pas = $_GET["contrasena"];
	$cel = $_GET["celular"];
	$act = $_GET["activo"];
	$rol = $_GET["rol"];	
		
	if($pas==""){
		$sql_update_usuario = $var_update."usuarios ".$var_set."US_NOMBRE='".$nom."', US_APELLIDOS='".$ape."', US_ACTIVO='".$act."', US_CELULAR='".$cel."', US_EMAIL='".$cor."', rol='".$rol."' ".$var_where." UPPER(US_RUT)=".$rut;	
	}else{
		$pas = md5($pas);	
		$sql_update_usuario = $var_update."usuarios ".$var_set."US_NOMBRE='".$nom."', US_APELLIDOS='".$ape."', US_CLAVE='".$pas."', US_ACTIVO='".$act."', US_CELULAR='".$cel."', US_EMAIL='".$cor."', rol='".$rol."' ".$var_where." UPPER(US_RUT)=".$rut;
	}
	
	$datos_update_usu=call_update($sql_update_usuario,"");
	$reg_fil_up = $datos_update_usu['nro_reg_actualizado'];	
		
	if($reg_fil_up == 1){
		echo "actualizo";
	}else{
		echo "fallo";
	}	
		
	break;	
		
}

?>