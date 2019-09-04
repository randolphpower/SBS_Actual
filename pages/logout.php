<?php
error_reporting(E_ERROR);
session_start();
require_once("../modelo/consultaSQL.php");
date_default_timezone_set("America/Santiago");

$ahora=date("Y-m-d H:i:s",mktime((date("H")+$_SESSION['diferencia_horaria']),date("i"),date("s"),date("m"),date("d"),date("Y")));
	
	
	
	
	$sql_update=$var_update."usuarios ".$var_set."US_FECHA_FIN='".$ahora."' ".$var_where." (US_USUARIO='".$_SESSION['username']."')";
	call_update($sql_update);	
		
	session_destroy();

	header('Location: login.php');
	exit();

?>