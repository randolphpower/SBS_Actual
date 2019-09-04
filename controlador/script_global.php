<?php
session_start();
require_once("../modelo/conectarBD.php");
require_once("../modelo/consultaSQL.php");


switch ($_GET["opcion"]) {
		
case "1":
	
	$username = strtoupper($_GET['user']);
	$password = md5($_GET['password']);	
		
	$datos=array();
	$datos=call_select($var_select_asterisk_from."usuarios ".$var_where."(UPPER(US_USUARIO)='".$username."') ".$var_and."US_ACTIVO = '1' ".$var_limit."1","");
	$result=mysql_fetch_array($datos['registros']);	
		
	if($datos['num_filas']==1){
		
		if ($password == $result['US_CLAVE']) {
			
			$datos_time=array();
			$datos_time=call_select($var_select_asterisk_from."grin_informacion","");
			$result_tiempo=mysql_fetch_array($datos_time['registros']);
			
			$_SESSION['loggedin'] = true;
    		$_SESSION['username'] = $username;
			$_SESSION['name'] = $result['US_NOMBRE']." ".$result['US_APELLIDOS'];
			$_SESSION['segundos'] = $result_tiempo['GRIN_TIEMPO_SESSION'];
			$_SESSION['diferencia_horaria'] = $result_tiempo['GRIN_DIFERENCIA_HORARIA'];
			$_SESSION['rol'] = $result['rol'];
			
			
			date_default_timezone_set("America/Santiago");
			$fecha_actual_y_hora = date("Y-m-d H:i:s",mktime((date("H")+$result_tiempo['GRIN_DIFERENCIA_HORARIA']),date("i"),date("s"),date("m"),date("d"),date("Y")));
			$_SESSION['start'] = $fecha_actual_y_hora;
			$_SESSION['tiempo'] = $fecha_actual_y_hora;
			
			
	//************************************************** CARGA DE ROLES ********************************************//
		$roles=array();
		$roles=call_select($var_select_asterisk_from."roles_usuario ".$var_where." (ID_USUARIO='".$result['US_RUT']."') ","");
		$numf=0;
		$rolesusuaio=array();	
		while($resultroles=mysql_fetch_array($roles['registros'])){
			$rolesusuaio[$numf]=$resultroles['ID_ROL'];
			$numf=$numf+1;
		}//Fin mientras
		$_SESSION['roles']=$rolesusuaio;
	//************************************************** FIN CARGA DE ROLES ********************************************//
		
		$sql_update=$var_update." usuarios ".$var_set."US_FECHA_INI='".$_SESSION['start']."' ".$var_where." (US_USUARIO='".$username."')";
		call_update($sql_update);	
			
			
			echo 1;
			
		}else{
			
			 ?>
			<div class="alert alert-danger" role="alert">
			  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			  <span class="sr-only">Error:</span>
			  Verifique los datos ingresados sean correctos!
			</div>
			<?php
			
		} 
		
	}else{
		
		?>
		<div class="alert alert-danger" role="alert">
		  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		  <span class="sr-only">Error:</span>
		  Ingrese un nombre de Usuario v&aacute;lido!
		</div>
		<?php
		
	}//FIN SI	

	break;//Finaliza la ejecución de la estructura control seleccionada		
		
}

?>

 
