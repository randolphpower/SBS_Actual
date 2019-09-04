<?php

//error_reporting(E_ALL);

if (!session_id()) session_start();

date_default_timezone_set("America/Santiago");

$ret = array();
$last_id = 0;

//Obtener el valor de Auto_Incrementador de una determinada Tabla
$sql_valor_auto_increment="Select AUTO_INCREMENT from information_schema.TABLES where TABLE_SCHEMA='nemesisnc' and TABLE_NAME=";
$var_valor_autoincrement=0;

//Listado de palabras reservadas para consultas SQL
/****** Caso Consultas SELECT **********/
$var_select_asterisk_from="Select * from ";
$var_select="Select ";
$var_from="from ";
$var_order_by="Order By ";
$var_asc="ASC ";
$var_desc="Desc ";
$var_where="Where ";
$var_limit="Limit ";
$var_group_by="Group By ";
$var_max="MAX";
$var_like="Like ";
$var_or="Or ";
$var_and="And ";
$var_between="Between ";
/****** Caso UPDATE **********/
$var_update="Update ";
$var_set="SET ";

/****** Caso INSERT **********/
$var_insert_into="Insert into ";
$var_values="Values ";

/****** Caso DELETE **********/
$var_delete_from="Delete from ";
$var_delete="Delete ";


//functions

function get_select($sql, $csql){

	global $ret;
	global $last_id;
	global $sql_valor_auto_increment;
	global $var_valor_autoincrement;	

	include("conectarBD.php");
	
	$page_size = 10;
	$start = 0;
	$p = 1;
	if (isset($_GET['p'])) {
		$p = $_GET['p'];
		$start = round(($p-1)*$page_size);
	}
		
	$q = mysql_query($csql, $conexion) or die(mysql_error());
	$r = mysql_fetch_array($q);
	$records = $r[0];

	$sql .= " LIMIT {$start}, {$page_size}";

	$q = mysql_query($sql, $conexion) or die(mysql_error());
	// $records = mysql_num_rows($q);
	$pages = floor($records/$page_size);

	$ret = array('registros' => $q, 
				 'page_size' => $page_size,
				 'pages' => $pages,
				 'page' => $p,
				 //'valor_autoincremt' => $var_valor_autoincrement,
				 'records' => $records );
	
	mysql_close($conexion);
	
	return $ret;
		
}


//Funcion para consultar elementos en una Base de Datos
function call_select($parametro_sql, $parametro_tabla){
	
	include("conectarBD.php");
	
	global $ret;
	global $last_id;
	global $sql_valor_auto_increment;
	global $var_valor_autoincrement;	
	
	$parametro_sql = mysql_query($parametro_sql,$conexion) or die(mysql_error());
	$nro_filas_afectadas = mysql_affected_rows($conexion);
	$nro_reg_tabla = mysql_num_rows($parametro_sql);
	
	if ($parametro_tabla != "") {

		$valor_autoincrement_temp = mysql_query($sql_valor_auto_increment."'".$parametro_tabla."'",$conexion) or die(mysql_error());
		$valor_autoincrement_temp = mysql_fetch_assoc($valor_autoincrement_temp);

		if (is_null($valor_autoincrement_temp['AUTO_INCREMENT']))
			$var_valor_autoincrement = -1;
		else
			$var_valor_autoincrement = $valor_autoincrement_temp['AUTO_INCREMENT'];
	}

	mysql_close($conexion);
	
	$ret = array('registros' => $parametro_sql,
					     'num_filas' => $nro_filas_afectadas,
					     'valor_autoincremt' => $var_valor_autoincrement,
						 'num_registros' => $nro_reg_tabla,
						 'por_asignar2' => '',
						 'por_asignar3' => '',
						 'por_asignar4' => ''
					     );
	
	return $ret;
		
}


//Funcion para actualizar elementos en una Base de Datos
function call_update($update_sql){
	
	include ("conectarBD.php");
	global $ret;
	$nro_filas_afectadas = 0;
	
	$parametro_sql=mysql_query($update_sql,$conexion) or die(mysql_error());
	$nro_filas_afectadas = mysql_affected_rows($conexion);
	//$nro_reg_tabla = mysql_num_rows($parametro_sql);
	
	$ret = array('nro_reg_actualizado' => $nro_filas_afectadas,
					     'num_registros' => $nro_filas_afectadas,
						 'por_asignar2' => '',
						 'por_asignar3' => '',
						 'por_asignar4' => ''
					     );
	
	mysql_close($conexion);
	return $ret;
	
}//Fin funcion update


//Funcion para insertar elementos en una Base de Datos
function call_insert($insert_sql, $parametro_condicional){
	include ("conectarBD.php");
	
	global $ret;
	$nro_filas_afectadas=0;
	
	mysql_query($insert_sql,$conexion) or die(mysql_error());
	$nro_filas_afectadas = mysql_affected_rows($conexion);
		
	if($parametro_condicional!="")
		$parametro_sql=mysql_query($parametro_condicional,$conexion) or die(mysql_error());
	else
		$parametro_sql=0;	
	
	$ret = array('ultimo_id' => $parametro_sql,
					     'num_filas_insert' => $nro_filas_afectadas,
						 'por_asignar2' => '',
						 'por_asignar3' => '',
						 'por_asignar4' => '',
					     );
	
	mysql_close($conexion);
	return $ret;
}//Fin funcion insert


//Funcion para eliminar elementos en una Base de Datos
function call_delete($delete_sql){
	include ("conectarBD.php");
	global $ret;
	$nro_filas_afectadas = 0;
	
	mysql_query($delete_sql,$conexion) or die(mysql_error());
	$nro_filas_afectadas = mysql_affected_rows($conexion);
	
	$ret = array('nro_reg_borrado' => $nro_filas_afectadas,
					     'por_asignar1' => '',
						 'por_asignar2' => '',
						 'por_asignar3' => '',
						 'por_asignar4' => '',
					     );
	
	mysql_close($conexion);
	return $ret;
	
}//Fin funcion delete

?>