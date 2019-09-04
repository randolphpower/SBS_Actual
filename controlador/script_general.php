<?php 

function cambiafechaMySQL_a_normal($fecha){ 
   	ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
   	$lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]; 
   	return $lafecha; 
}//Fin funcion 

function cambiafechaNormal_a_MySQL($fecha){
	if(($fecha=="") or ($fecha=="0000/00/00") or (is_null($fecha))){
		$lafecha="0000-00-00";
	}else{
		$lafecha=substr($fecha,6,4)."-".substr($fecha,3,2)."-".substr($fecha,0,2);
	}
	//ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
   	//$lafecha=$mifecha[1]."-".$mifecha[2]."-".$mifecha[3]; 
	return $lafecha; 
}//Fin funcion 

//Funcion exclusiva para colocarla en los nombres de archivos
function fecha_actual_con_piso(){    	
	$fecha_hoy=date("d_m_Y H_i_s",mktime((date("H")),date("i"),date("s"),date("m"),date("d"),date("Y")));
	return $fecha_hoy; 
}//Fin funcion 

function fecha_actual(){
	date_default_timezone_set("America/Santiago");    	
	$fecha_hoy=date("Y-m-d H:i:s",mktime((date("H")+$_SESSION['diferencia_horaria']),date("i"),date("s"),date("m"),date("d"),date("Y")));
	return $fecha_hoy; 
}//Fin funcion 

//Funcion que da formato de RUT. Ejm: 76.234.510-4
function formato_rut( $rut ) {
    return number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen($rut) -1 , 1 );
}

function fecha_convierte_a_normal($mysql){
	
$f=split(" ",$mysql);
ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $f[0], $mifecha);
$dias=$mifecha[3];
$mes=$mifecha[2];
$ano=$mifecha[1];
$hora=$f[1];
	
	$var_fecha=$dias." de ";
	
switch ($mes){
		case 1:
			$var_fecha=$var_fecha."Enero ";break;
		case 2:
			$var_fecha=$var_fecha."Febrero ";break;			
		case 3:
			$var_fecha=$var_fecha."Marzo ";break;
		case 4:
			$var_fecha=$var_fecha."Abril ";break;
		case 5:
			$var_fecha=$var_fecha."Mayo ";break;
		case 6:
			$var_fecha=$var_fecha."Junio ";break;
		case 7:
			$var_fecha=$var_fecha."Julio ";break;
		case 8:
			$var_fecha=$var_fecha."Agosto ";break;
		case 9:
			$var_fecha=$var_fecha."Septiembre ";break;
		case 10:
			$var_fecha=$var_fecha."Octubre ";break;
		case 11:
			$var_fecha=$var_fecha."Noviembre ";break;
		case 12:
			$var_fecha=$var_fecha."Diciembre ";break;	
	}//FIN SWITCH
	$hora_1=split(":",$hora);
	if ($hora_1[0]<13){
		if ($hora_1[0]==12){
			$f_hora="a las $hora_1[0]:$hora_1[1] pm";
		}else if($hora_1[0]=="00"){
			$f_hora="a las 12:$hora_1[1] am";
		}else{
			$f_hora="a las $hora_1[0]:$hora_1[1] am";
		}
	}else{
	
			switch ($hora_1[0]){
				case 13:
					$f_hora="a las 1:$hora_1[1] pm";break;
				case 14:
					$f_hora="a las 2:$hora_1[1] pm";break;
				case 15:
					$f_hora="a las 3:$hora_1[1] pm";break;
				case 16:
					$f_hora="a las 4:$hora_1[1] pm";break;
				case 17:
					$f_hora="a las 5:$hora_1[1] pm";break;
				case 18:
					$f_hora="a las 6:$hora_1[1] pm";break;
				case 19:
					$f_hora="a las 7:$hora_1[1] pm";break;
				case 20:
					$f_hora="a las 8:$hora_1[1] pm";break;
				case 21:
					$f_hora="a las 9:$hora_1[1] pm";break;
				case 22:
					$f_hora="a las 10:$hora_1[1] pm";break;
				case 23:
					$f_hora="a las 11:$hora_1[1] pm";break;
				case 0:
					$f_hora="a las 12:$hora_1[1] am";break;
			}//FIN SWITCH
	}
	//echo "var=".$var_fecha."-";
	//echo $var_fecha.date("Y",time());
	
	return $fecha_convert=$var_fecha." ".$ano.", ".$f_hora;
}// fin funcion


?>