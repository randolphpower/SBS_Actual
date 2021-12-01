<?php
include("../modelo/conectarBD.php");
include("../modelo/consultaSQL.php");
session_start();

function agregarespacios($cadena, $largo)
	{
		if(is_null($cadena) || $cadena=="" || $cadena=="00000000" ){
			return $cadena=str_repeat(" ",$largo);
		}
		else{
			$largocadena=strlen($cadena);
			$faltante=$largo-$largocadena;
			return $cadena.=str_repeat(" ", $faltante);
		}
	}

switch ($_GET["opcion"]) {

case "1":

  $texto="'%".strtoupper($_GET["desCodA"])."%'";


  $hint="";

	//SELECT `CT_TYPE`, `CT_DESC` from tipos_juicio where `CT_TYPE` like 'c%'


	/****** CONSULTA *******/
	$sql_consulta=$var_select."`CODIGO`,`DESCRIPCION` ".$var_from."codigo_accion ".$var_where."(`CODIGO` ".$var_like.$texto." or `DESCRIPCION` ".$var_like.$texto.")";

	$datos = array();
	$datos = call_select($sql_consulta,"");

	$i=0;
	while($result=mysql_fetch_array($datos['registros'])){


		if ($hint=="") {

		  $hint="<span style='cursor:pointer;' onclick='accionaCodA(\"".$result["DESCRIPCION"]."\",\"".$result["CODIGO"]."\")' ><b>".$result["CODIGO"]." - ".$result["DESCRIPCION"]."</b></span><br />";
        } else {
          $hint=$hint . "<span style='cursor:pointer;' onclick='accionaCodA(\"".$result["DESCRIPCION"]."\",\"".$result["CODIGO"]."\")' >".$result["CODIGO"]." - ".$result["DESCRIPCION"]."</span><br />";
        }

	 $i=$i+1;
	}//Fin mientras

	// Set output to "no suggestion" if no hint was found
	// or to the correct values
	if ($hint=="") {
	  $response="";
	} else {
	  $response=$hint;
	}

	//output the response
	echo $response;

break;

case "2":


	$texto="'".strtoupper($_GET["texto"])."'";
	$texto2=$_GET["texto2"];

	//SELECT  `CD_STGID` ,  `CD_DESC` FROM  `etapas_procesales` WHERE  `CD_TYPE` =  "GPREP"

	$datos=array();
	$datos=call_select($var_select."`RESULTADO`,`DESCRIPCION` ".$var_from."`relacion_ca_cr` ".$var_where."`ACCION`= ".$texto,"");

	?>

     							<!--<div class="col-lg-6">-->

                                            <label>C&oacute;digo Resultado</label>
                                            <select class="form-control" id="codR" name="" onChange="pasarCodR()">
                                            	<option value="defecto">Seleccione</option>
                                            	<?php
													$bool=false;
											 	 	$selected="";

												  while ($result=mysql_fetch_array($datos['registros'])) {

													if (($texto2==$result["RESULTADO"]) and ($bool==false)){
																$selected="selected";
																$bool=true;
													}
												 	echo '<option '.$selected.' value="'.$result["RESULTADO"].'">'.$result["DESCRIPCION"].'</option>';
													$selected="";
												  }
												?>
                                            </select>

                                <!--</div><!-- /.col-lg-6 -->

    <?php

break;//Finaliza colocar nombres de los codigos Resultado


case "3":


//identificador, fecha, idenCodigoA, codCarta, comentario, extension, idJuicio, numSecuencia, idAgente, telefono, codigoRes
	$fecha = $_GET["fecha"];


 	//"INSERT INTO $tabla_db1 (nombre,email,fecha) VALUES ('$nombre','$email','$fecha')";
	$sql_insert=$var_insert_into." op_200_gestiones (CONSTANTE, ACACCT, DATE, ACSEQNUM, ACACCODE, ACRCCODE, ACLCCODE, ACCIDMAN, ACCOMN, ACPHONE, ACEXT, USUSUARIO)".$var_values."('".$_GET["identificador"]."','".$_GET["idCliente"]."','".$fecha."','".$_GET["numSecuencia"]."','".$_GET["idenCodigoA"]."','".$_GET["codigoRes"]."','".$_GET["codCarta"]."','".$_GET["idAgente"]."','".$_GET["comentario"]."','".$_GET["telefono"]."','".$_GET["extension"]."','".$_SESSION['username']."')";

	//echo $sql_insert;

	call_insert($sql_insert,"");

	?>


<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Bien Hecho!</strong> Su operaci&oacute;n fue almacenada y procesada.
</div>

    <?php

break;//Finaliza ingresar el identificador del cliente y del juicio


case "4":
	//echo "llegue ";
	$datos = array();
	$sql_enviar=$var_select."CONSTANTE, ACACCT, DATE_FORMAT(DATE, '%m%d%Y') AS DATE, ACSEQNUM, ACACCODE, ACRCCODE, ACLCCODE, ACCIDMAN, ACCOMN, ACPHONE, ACEXT, ACSNDATE ".$var_from."op_200_gestiones ".$var_where."ACSNDATE = 0";
	//echo $sql_enviar;

	$datos = call_select($sql_enviar,"");

	$hoy = date("Ymd");
	$archivo= "200J_SERVICOBRANZA_".$hoy.".txt";

	if (file_exists($archivo)) {
		 unlink($archivo);
	}



	$fp=fopen($archivo,"x");

	while($result=mysql_fetch_array($datos['registros'])){

	 $linea="200".agregarespacios($result["ACACCT"],26).agregarespacios($result["DATE"],8)." 00:00:00001".$result["ACACCODE"].$result["ACRCCODE"]."  SERVICOB".agregarespacios(utf8_decode($result["ACCOMN"]),56).agregarespacios($result["ACPHONE"],13).agregarespacios($result["ACEXT"],8);

		fputs($fp,$linea);
		fputs($fp,chr(13).chr(10));


	}
	fclose($fp) ;

	$sql_ftp=$var_select_asterisk_from."envio_ftp ".$var_where."CODIGO = 1";
	$envio = array();
	$envio = call_select($sql_ftp,"");

	while($resultado=mysql_fetch_array($envio['registros'])){

		$ftp_server=$resultado["IP"];
		$ftp_user_name=$resultado["USUARIO"];
		$ftp_user_pass=$resultado["CLAVE"];

	}

	$remote_file = $archivo;

	$zip= new ZipArchive();
	$filename = "200J_SERVICOBRANZA_".$hoy.".zip";

	if($zip->open($filename,ZIPARCHIVE::CREATE)===true){
		$zip->addFile($archivo);
		$zip->close();
	}

	include 'Net/SFTP.php';

     $sftp = new Net_SFTP('200.53.142.68');
     if (!$sftp->login('servicob', '53rv1c0b_2017')) {
        echo "error de login";
     }else {

	 	$sftp->put($filename, $filename, NET_SFTP_LOCAL_FILE);


	 	$fecha=date("'Y-m-d' ");
	 	$sql_update=$var_update."op_200_gestiones ".$var_set."ACSNDATE = ".$fecha." ".$var_where."ACSNDATE = 0";

		call_update($sql_update,"");


     }

	 $sftp = new Net_SFTP('jupiter.onvision.cl/Entrada');
     if (!$sftp->login('sftpclaservbr', 'sftpclaservbr.2021')) {
        echo "error de login";
     }else {
	 	$sftp->put($filename, $filename, NET_SFTP_LOCAL_FILE);
     }

break;


case "5":

  $texto="'%".strtoupper($_GET["idCliente"])."%'";


  $hint="";

	//SELECT `CTCRTID` , `CTNAME` FROM  `cata_juzgados` WHERE  `CTCRTID` LIKE  '%1%'

	/****** CONSULTA *******/
	$sql_consulta=$var_select."a.`NUM_JUICIO`,a.`ID_CLIENTE`, b.`CELSTACT`, b.`CELASTAC`, b.`CELASTRC`, c.`DESCRIPCION` ".$var_from."relacion_cliente_juicio a, op_info_juicios b, codigo_accion c ".$var_where."(`ID_CLIENTE` ".$var_like.$texto.") ".$var_and."(a.`ID_CLIENTE`= b.`CESSNUM`) ".$var_and."(b.`CELASTAC`=c.`CODIGO`)";

	$datos = array();
	$datos = call_select($sql_consulta,"");

	$i=0;
	while($result=mysql_fetch_array($datos['registros'])){

		//$valor=$result["CTNAME"];

		if ($hint=="") {

		  $hint="<span style='cursor:pointer;' onclick='accionaNumJuicio(\"".utf8_encode($result["NUM_JUICIO"])."\",\"".$result["ID_CLIENTE"]."\",\"".$result["CELSTACT"]."\",\"".$result["CELASTAC"]."\",\"".$result["CELASTRC"]."\",\"".utf8_encode($result["DESCRIPCION"])."\")' ><b> Cliente: ".($result["ID_CLIENTE"])." - Numero del Juicio: ".utf8_encode($result["NUM_JUICIO"])."</b></span><br />";
        } else {
          $hint=$hint . "<span style='cursor:pointer;' onclick='accionaNumJuicio(\"".utf8_encode($result["NUM_JUICIO"])."\",\"".$result["ID_CLIENTE"]."\",\"".$result["CELSTACT"]."\",\"".$result["CELASTAC"]."\",\"".$result["CELASTRC"]."\",\"".utf8_encode($result["DESCRIPCION"])."\")' > Cliente: ".$result["ID_CLIENTE"]." - Numero del Juicio: ".utf8_encode($result["NUM_JUICIO"])."</span><br />";
        }

	 $i=$i+1;
	}//Fin mientras

	// Set output to "no suggestion" if no hint was found
	// or to the correct values
	if ($hint=="") {
	  $response="No se encontro ningun Rut asociado a un Juicio. Intente nuevamente";
	} else {
	  $response=$hint;
	}

	//output the response
	echo $response;

break;

case "6":

  	//echo $_GET["id"];
	//echo $texto= "'".trim($_GET["nJuicio"])."'";
	// Delete from `op_info_juicios` where CNCASENO='Marcelo' AND CESSNUM = ;

	/****** CONSULTA *******/
	$sql_delete=$var_delete_from."`op_200_gestiones` ".$var_where."(`id`= ".$_GET["id"].")";

	//echo $sql_delete;
	call_delete($sql_delete,"");

break;

case "7":

	$codR = $_GET["codR"];

	$sql_consulta = $var_select_asterisk_from."codigo_result ".$var_where."(CODIGO='".$codR."')";
$datos = array();
	$datos = call_select($sql_consulta, "");
	$result = mysql_fetch_array($datos['registros']);

	echo $result['DESCRIPCION'];

break;

}
?>