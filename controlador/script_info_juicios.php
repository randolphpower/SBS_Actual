<?php

include("../modelo/conectarBD.php");
include("../modelo/consultaSQL.php");
include("script_general.php");

session_start();

function agregarespacios($cadena, $largo) { 
	if (is_null($cadena) || $cadena == "" || $cadena == "00000000" ) {
		return $cadena=str_repeat(" ",$largo);
	} else {
		$largocadena=strlen($cadena);
		$faltante=$largo-$largocadena;
		return $cadena.=str_repeat(" ", $faltante);
	}
}

// print_r($_GET);
// exit;

switch ($_GET["opcion"]) {

case "1":

  	$texto = "'%".strtoupper($_GET["tipoJuicio"])."%'";
	$hint = "";

	// SELECT `CT_TYPE`, `CT_DESC` from tipos_juicio where `CT_TYPE` like 'c%'
	$sql = $var_select."`CT_TYPE`,`CT_DESC` ".$var_from."tipos_juicio ".$var_where."(`CT_TYPE` ".$var_like.$texto." or `CT_DESC` ".$var_like.$texto.")";
	
	$datos = array();
	$datos = call_select($sql,"");

	$i=0;
	while ($result = mysql_fetch_array($datos['registros'])) {
		if ($hint == "") {
			$hint="<span style='cursor:pointer;' onclick='accionaTipoJuicio(\"".$result["CT_DESC"]."\",\"".$result["CT_TYPE"]."\")' ><b>".$result["CT_TYPE"]." - ".$result["CT_DESC"]."</b></span><br />";
        } else {
          	$hint=$hint . "<span style='cursor:pointer;' onclick='accionaTipoJuicio(\"".$result["CT_DESC"]."\",\"".$result["CT_TYPE"]."\")' >".$result["CT_TYPE"]." - ".$result["CT_DESC"]."</span><br />";
        }
	 	$i = $i + 1;
	}
	
	if ($hint == "") {
	  $response = "";
	} else {
	  $response = $hint;
	}

	echo $response;

break;

case "2":

	$texto = "'%".strtoupper($_GET["idJuzgado"])."%'";
	$hint = "";

	//SELECT `CTCRTID` , `CTNAME` FROM  `cata_juzgados` WHERE  `CTCRTID` LIKE  '%1%'

	$sql = $var_select."`CTCRTID`,`CTNAME` ".$var_from."cata_juzgados ".$var_where."(`CTCRTID` ".$var_like.$texto." or `CTNAME` ".$var_like.$texto.")";
	$datos = array();
	$datos = call_select($sql, "");

	$i=0;
	while ($result = mysql_fetch_array($datos['registros'])) {
		//$valor=$result["CTNAME"];
		if ($hint == "") {
		  $hint = "<span style='cursor:pointer;' onclick='accionaJuzgados(\"".utf8_decode($result["CTNAME"])."\",\"".$result["CTCRTID"]."\")' ><b>".($result["CTCRTID"])." - ".utf8_encode($result["CTNAME"])."</b></span><br />";
        } else {
          $hint = $hint . "<span style='cursor:pointer;' onclick='accionaJuzgados(\"".utf8_decode($result["CTNAME"])."\",\"".$result["CTCRTID"]."\")' >".$result["CTCRTID"]." - ".utf8_encode($result["CTNAME"])."</span><br />";
        }
	 	$i = $i + 1;
	}
	
	if ($hint == "") {
		$response = "";
	} else {
	  $response = $hint;
	}

	//output the response
	echo $response;

break;

case "3":

  $texto = "'%".strtoupper($_GET["idCodigoAcc"])."%'";
  $hint = "";

	//SELECT `CTCRTID` , `CTNAME` FROM  `cata_juzgados` WHERE  `CTCRTID` LIKE  '%1%'

	/****** CONSULTA *******/
	$sql_consulta=$var_select."`CODIGO`,`DESCRIPCION` ".$var_from."codigo_accion ".$var_where."(`CODIGO` ".$var_like.$texto."or `DESCRIPCION`".$var_like.$texto.")";

	$datos = array();
	$datos = call_select($sql_consulta,"");

	$i=0;
	while ($result=mysql_fetch_array($datos['registros'])){



		if ($hint == "") {

		  $hint="<span style='cursor:pointer;' onclick='accionaCodAcc(\"".utf8_encode($result["DESCRIPCION"])."\",\"".$result["CODIGO"]."\")' ><b>".($result["CODIGO"])." - ".utf8_encode($result["DESCRIPCION"])."</b></span><br />";
        } else {
          $hint=$hint . "<span style='cursor:pointer;' onclick='accionaCodAcc(\"".utf8_encode($result["DESCRIPCION"])."\",\"".$result["CODIGO"]."\")' >".$result["CODIGO"]." - ".utf8_encode($result["DESCRIPCION"])."</span><br />";
        }

	 $i=$i+1;
	}//Fin mientras

	// Set output to "no suggestion" if no hint was found
	// or to the correct values
	if ($hint == "") {
	  $response="";
	} else {
	  $response=$hint;
	}

	//output the response
	echo $response;

break;

case "4":

  	$texto = "'%".strtoupper($_GET["idCliente"])."%'";
  	$hint = "";

	/****** CONSULTA *******/

	// $sql_consulta = $var_select."a.*, c.CTNAME as NOM_CTNAME ".$var_from."relacion_cliente_juicio a, cata_juzgados c ".$var_where."(a.`ID_CLIENTE` ".$var_like.$texto.") ".$var_and."(a.CECRTID = c.CTCRTID) ".$var_limit."10";
	$sql_consulta = "SELECT a.*, c.CTNAME as NOM_CTNAME ";
	$sql_consulta .= "FROM relacion_cliente_juicio a, cata_juzgados c ";
	$sql_consulta .= "WHERE (a.`ID_CLIENTE` ".$var_like.$texto.") ".$var_and."(a.CECRTID = c.CTCRTID) ".$var_limit."10";

	// $stg_id = 1; // Identificador de la etapa -> "Ingreso de la demanda"
	// $sql = "SELECT * FROM op_eta_proce WHERE CSCASENO = '{$numjuicio}' AND CSSTGID = {$stg_id}";

	$datos = array();
	$datos = call_select($sql_consulta, "");

	/*echo $result1["CELSTACT"]." ".$result1["CELASTAC"]." ".$result1["CELASTRC"]." ?>?> ".utf8_encode($result1["DESCRIPCION"])."<>? ".utf8_encode($result2["DESCRIPCION"]);*/

	// Fecha , codigo accion, codigo resultado, descripcion codigo accion, descripcion codigo resultado
    /* acciona200($result1["CELSTACT"], $result1["CELASTAC"], $result1["CELASTRC"], utf8_encode($result1["DESCRIPCION"]), utf8_encode($result2["DESCRIPCION"]));	*/

	$i = 0;
	$hint = "";

	while ($result = mysql_fetch_array($datos['registros'])) {

		$b1 = "";
		$b2 = "";
		
		if ($i == 0) {
			$b1 = "<b>";
			$b2 = "</b>";
		}

		if ($result["CECRTID"]  == "" && $result["CEDOSSIERID"]  == "" && $result["CETYPE"]  == "") {
			$hint .= "{$b1}<span style='cursor:pointer;' onclick='accionaNumJuicio(\"".$result["NUM_JUICIO"]."\",\"".$result["ID_CLIENTE"]."\",\"".$result["CECRTID"]."\",\"".$result["CEDOSSIERID"]."\",\"".$result["CTNAME"]."\",\"".$result["TEMP_FECHA_DEM"]."\",\"".$result["CETYPE"]."\",\"".$result["TEMP_FECHA_INICIO"]."\")' >Cliente: ".$result["ID_CLIENTE"]." - Numero de Juicio".$result["NUM_JUICIO"]."</span>{$b2}<br />";
		} else {
			$hint .= "{$b1}<span style='cursor:pointer;' onclick='accionaNumJuicio(\"".$result["NUM_JUICIO"]."\",\"".$result["ID_CLIENTE"]."\",\"".$result["CECRTID"]."\",\"".$result["CEDOSSIERID"]."\",\"".$result["NOM_CTNAME"]."\",\"".$result["TEMP_FECHA_DEM"]."\",\"".$result["CETYPE"]."\",\"".$result["TEMP_FECHA_INICIO"]."\")' > Cliente: ".$result["ID_CLIENTE"]." - Numero de Juicio: ".$result["NUM_JUICIO"]."</span>{$b2}<br />";
		}

	 	$i++;

	}

	// 
	
	if ($hint == "") {
		$response = "";
	} else {
		$response = $hint;
	}

	//output the response
	echo $hint;
	
break;

case "5":

	$texto="'".strtoupper($_GET["texto"])."'";
	//SELECT  `CD_STGID` ,  `CD_DESC` FROM  `etapas_procesales` WHERE  `CD_TYPE` =  "GPREP"

	$datos=array();
	$datos=call_select($var_select."`RESULTADO`,`DESCRIPCION` ".$var_from."`relacion_ca_cr` ".$var_where."`ACCION`= ".$texto,"");

	?>

	<!--<div class="col-lg-6">-->
			<div class="form-group">
				<label>C&oacute;digo Resultado</label>
				<select class="form-control" id="idEtapa" name="">
					<option value="defecto" selected>Seleccione</option>
					<?php

						while ($result=mysql_fetch_array($datos['registros'])) {
						echo '<option value="'.$result["RESULTADO"].'">'.utf8_encode($result["DESCRIPCION"]).'</option>';
						}
					?>
				</select>
				</div><!-- /.form-group -->
	<!--</div><!-- /.col-lg-6 -->
    <?php

break; // Finaliza colocar nombre del codigo Resultado

case "6":

	$texto=$_GET["idenJui"];

	if ($texto  == "EJCPMM 5"){
	 $texto="EJCPMM+5";
	}

	$select=$var_select."CECRTID, CEDOSSIERID ".$var_from."relacion_cliente_juicio ".$var_where."ID_CLIENTE = ".$_GET["idCliente"]." and NUM_JUICIO = ".$_GET["numJuicio"];
	$datos = call_select($select,"");

	$res=mysql_fetch_array($datos['registros']);

	//echo $res["CECRTID"]." ".$res["CEDOSSIERID"];

	if ($res["CECRTID"]!=$_GET["idenJuz"] || $res["CEDOSSIERID"]!= $_GET["numExpe"] ){

		$sql_update=$var_update."relacion_cliente_juicio ".$var_set."CECRTID = '".$_GET["idenJuz"]."' , CEDOSSIERID = '".$_GET["numExpe"]."' , CETYPE = '".$texto."' ".$var_where."ID_CLIENTE = ".$_GET["idCliente"]." and NUM_JUICIO = ".$_GET["numJuicio"];
		//echo " ".$sql_update;
		call_update($sql_update,"");

	}

	if ($_GET["vacio"] ==1){
		$sql_update=$var_update."relacion_cliente_juicio ".$var_set."CECRTID = '".$_GET["idenJuz"]."' , CEDOSSIERID = '".$_GET["numExpe"]."' , CETYPE = '".$texto."' ".$var_where."ID_CLIENTE = ".$_GET["idCliente"]." and NUM_JUICIO = ".$_GET["numJuicio"];
		//echo " ".$sql_update;
		call_update($sql_update,"");
	}
	if ($_GET["vacio"] ==2){
		$sql_update=$var_update."relacion_cliente_juicio ".$var_set."CECRTID = '".$_GET["idenJuz"]."' , CEDOSSIERID = '".$_GET["numExpe"]."' , CETYPE = '".$texto."' ".$var_where."ID_CLIENTE = ".$_GET["idCliente"]." and NUM_JUICIO = ".$_GET["numJuicio"];
		//echo " ".$sql_update;
		call_update($sql_update,"");
	}

	//identificador, numJuicio, idenJuz, codigoAcc, ultFecha, fechaDem, numExp, idCliente, comCaso, ultFechaConta, idenJui, codigoRes
	$ultFecha = $_GET["ultFecha"];

	if ($ultFecha == ""){
		$ultFecha=date("Y-m-d");
	}

 	//"INSERT INTO $tabla_db1 (nombre,email,fecha) VALUES ('$nombre','$email','$fecha')";
	$sql_insert=$var_insert_into." op_info_juicios (IDENTIFICADOR, CEDOSSIERID, CNCASENO, CESSNUM, CECOMM, CECRTID, CELASTAC, CELASTRC, CELSTACT, CELSTCON, CELWSTDT, CETYPE, USUSUARIO) ".$var_values."('".$_GET["identificador"]."','".$_GET["numExpe"]."','".$_GET["numJuicio"]."','".$_GET["idCliente"]."','".$_GET["comCaso"]."','".$_GET["idenJuz"]."','".$_GET["codigoAcc"]."','".$_GET["codigoRes"]."','".$ultFecha."','".$_GET["ultFechaConta"]."','".$_GET["fechaDem"]."','".$texto."','".$_SESSION['username']."')";

	//echo $sql_insert;

	call_insert($sql_insert,"");

	?>
	<div class="alert alert-success alert-dismissible" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<strong>Bien Hecho!</strong> Su operaci&oacute;n fue almacenada y procesada.
	</div>
    <?php

break; // Finaliza ingresar el identificador del cliente y del juicio

case "7":
	
	$sql_enviar=$var_select."`IDENTIFICADOR`,  `CNCASENO`,  `CEDOSSIERID`,  `CESSNUM`, `CECOMM`, `CECRTID`, `CELASTAC` , `CELASTRC`, DATE_FORMAT(  `CELSTACT`,  '%d%m%Y' ) AS `CELSTACT`, DATE_FORMAT( `CELSTCON`, '%m%d%Y' ) AS `CELSTCON`, DATE_FORMAT( `CELWSTDT`, '%m%d%Y' ) AS `CELWSTDT`, `CETYPE`, `CESENDDT` ".$var_from."op_info_juicios ".$var_where."`CESENDDT` = 0 ";
	//echo $sql_enviar;

	$datos = array();
	$datos = call_select($sql_enviar,"");

	$hoy = date("Ymd");
	$archivo= "902_SERVICOBRANZA_".$hoy.".txt";

	if (file_exists($archivo)) {
		 unlink($archivo);
	}

	$fp = fopen($archivo, "x");

	while ($result=mysql_fetch_array($datos['registros'])){

	$cantidadNum=strlen($result["CESSNUM"]);

	if ($cantidadNum==8){
		$rut="0".$result["CESSNUM"];
	} else{
		$rut="00".$result["CESSNUM"];
	}

 	$linea="902".agregarespacios($result["CNCASENO"],25).agregarespacios($result["CEDOSSIERID"],15).agregarespacios($rut,25).agregarespacios($result["CECOMM"],200).agregarespacios($result["CECRTID"],30).$result["CELASTAC"].$result["CELASTRC"].agregarespacios($result["CELSTACT"],8).agregarespacios($result["CELSTCON"],8).agregarespacios($result["CELWSTDT"],8).agregarespacios($result["CETYPE"],20);
	 //echo $linea=$linea."-".strlen($linea);


		fputs($fp,$linea);
		fputs($fp,chr(13).chr(10));


	}
	fclose($fp) ;

	$sql_ftp=$var_select_asterisk_from."envio_ftp ".$var_where."CODIGO = 1";
	$envio = array();
	$envio = call_select($sql_ftp,"");

	while ($resultado=mysql_fetch_array($envio['registros'])){

		$ftp_server=$resultado["IP"];
		$ftp_user_name=$resultado["USUARIO"];
		$ftp_user_pass=$resultado["CLAVE"];

	}

	$remote_file = $archivo;

	$zip= new ZipArchive();
	$filename = "902_SERVICOBRANZA_".$hoy.".zip";

	if ($zip->open($filename,ZIPARCHIVE::CREATE)===true){
		$zip->addFile($archivo);
		$zip->close();
	}

	include 'Net/SFTP.php';

			     $sftp = new Net_SFTP('200.53.142.68');
			     if (!$sftp->login('servicob', '53rv1c0b_2017')) {
			        echo "error de login";
			     }else {

				 	$sftp->put($filename, $filename, NET_SFTP_LOCAL_FILE);


	$fecha=date("'Y-m-d'");
	$sql_update=$var_update."op_info_juicios ".$var_set."CESENDDT = ".$fecha." ".$var_where."CESENDDT = 0";
	//echo " ".$sql_update;
	call_update($sql_update,"");



		     }


break;

case "8":

  	//$texto="'%".strtoupper($_GET["tipoJuicio"])."%'";
  	//echo $_GET["id"];
	// Delete from `op_info_juicios` where CNCASENO='Marcelo' AND CESSNUM = ;

	/****** CONSULTA *******/
	$sql_delete=$var_delete_from."`op_info_juicios` ".$var_where."(`id`= ".$_GET["id"].")";

	//echo $sql_delete;
	call_delete($sql_delete,"");

break;

case "9":

	$texto="'".strtoupper($_GET["idTipoJui"])."'";

	if ($texto  == "'EJCPMM 5'"){
	 $texto="'EJCPMM+5'";
	}

	$datos=array();
	$datos=call_select($var_select."`CD_STGID`,`CD_DESC` ".$var_from."`etapas_procesales` ".$var_where."`CD_TYPE`= ".$texto,"");

	?>
	<!--<div class="col-lg-6">-->
	<div class="form-group">
		<label>Identificador de la Etapa</label>
		<select class="form-control" id="idEtapa" onChange="javascript: return loadDates(this);">
			<option value="sel">Seleccione</option>
			<?php
				//($result["DESCRIPCION"])
				while ($result=mysql_fetch_array($datos['registros'])) {
				echo '<option value="'.$result["CD_STGID"].'">'.$result["CD_DESC"].'</option>';
				}
			?>
		</select>
	</div><!-- /.form-group -->
	<!--</div><!-- /.col-lg-6 -->
	<?php

break;

case "10":

		$iden = $_GET["iden"];
		$idenJui = "'".strtoupper($_GET["idenJui"])."'";


		if ($idenJui  == "'EJCPMM 5'"){
			$idenJui="'EJCPMM+5'";
		}

		$sql_consulta_iden = $var_select_asterisk_from."etapas_procesales ".$var_where."CD_TYPE=".$idenJui." AND CD_STGID=".$iden;
		$datos_consulta = call_select($sql_consulta_iden,"");
		$resul_consulta = mysql_fetch_array($datos_consulta['registros']);

		echo $resul_consulta["CD_DESC"];

break;

case "11":

	$rut = $_GET["rut"] || "";
	$numJuicio = $_GET["numJuicio"];
	$idenJuz = $_GET["idenJuz"];
	$numExpe = $_GET["numExpe"];
	$fechaDem = $_GET["fechaDem"];

	$idenJuicio = $_GET["idenJui"];

	if ($idenJuicio  == "EJCPMM 5"){
		$idenJuicio="EJCPMM+5";
	}

	$idEtapa = $_GET["idEtapa"];
	$fechaInicio = $_GET["fechaInicio"];
	$fechaFin = $_GET["fechaFin"];
	$fecha = $_GET["fecha"];
	$codA = $_GET["codA"];
	$codR = $_GET["codR"];
	$comen = $_GET["comen"];
	$identificadorEtapa = $_GET["identificadorEtapa"];
	$proveedor = $_GET["proveedor"];
	$numFactura = $_GET["numFactura"];
	$montoGas = $_GET["montoGas"];
	$fechaAuto = $_GET["fechaAuto"];
	$descGasto = $_GET["descGasto"];
	$tipoGasto = $_GET["tipoGasto"];
	$subtipoGasto = $_GET["subtipoGasto"];
	$desFact = $_GET["desFact"];
	$numJuicio200 = "J".$numJuicio;
	$informacion = $_GET["informacion"];
	$procesales = $_GET["proce"];
	$gestion = $_GET["gestion"];
	$gastos = $_GET["gasto"];

	if ($informacion == 1) {

		$sql_update_relacion = $var_update."relacion_cliente_juicio ".$var_set."CECRTID='".$idenJuz."', CEDOSSIERID='".$numExpe."' ".$var_where."NUM_JUICIO=".$numJuicio;
		call_update($sql_update_relacion,"");

		$sql_insert_info = $var_insert_into."op_info_juicios (IDENTIFICADOR, CEDOSSIERID, CNCASENO, CESSNUM, CECRTID, CELWSTDT, CETYPE, USUSUARIO)".$var_values."(902, '".$numExpe."', '".$numJuicio."', '".$rut."', '".$idenJuz."', '".$fechaDem."', '".$idenJuicio."', '".$_SESSION['username']."')";
		call_insert($sql_insert_info,"");

	}

	if ($procesales == 1) {

		if ($idEtapa == 3) { // embargo de bienes

			$stg_id = 2; // notificacion de la demanda 
			$sql =  "SELECT * FROM op_eta_proce WHERE CSCASENO = '{$numJuicio}' AND CSSTGID = {$stg_id} ";
			$q = call_select($sql, "");
			
			if ($q["num_filas"] == 0) {
				echo "ask create";
				exit;
			} else {
				$sql =  "SELECT * FROM op_eta_proce WHERE CSCASENO = '{$numJuicio}' AND CSSTGID = {$stg_id} AND CSENDDT > 0";
				$q = call_select($sql, "");
				if ($q["num_filas"] == 0) {
					echo "ask date";
					exit;
				}
			}
		}

		$sql = "INSERT INTO op_eta_proce (CSCASENO, CSTYPE, CSSTGID, CSSTDT, CSENDDT, USUSUARIO) VALUES ";
		$sql .= "('".$numJuicio."', '".$idenJuicio."', '".$idEtapa."', '".cambiafechaNormal_a_MySQL($fechaInicio)."', '".cambiafechaNormal_a_MySQL($fechaFin)."', '".$_SESSION['username']."')";

		$datos_eta = call_insert($sql,"SELECT LAST_INSERT_ID() AS 'ID'");
		$result_proce = mysql_fetch_array($datos_eta["ultimo_id"]);
		$reg_fil_eta = $datos_eta['num_filas_insert'];

	}

	if ($gestion == 1) {
		$sql_insert_200 = $var_insert_into."op_200_gestiones (CONSTANTE, ACACCT, DATE, ACSEQNUM, ACACCODE, ACRCCODE, ACLCCODE, ACCIDMAN, ACCOMN, USUSUARIO) ".$var_values."(200, '".$numJuicio200."', '".cambiafechaNormal_a_MySQL($fecha)."', '".$idEtapa."', '".$codA."', '".$codR."', 'EP', 'SERVICOB', '".$comen."', '".$_SESSION['username']."')";
		$datos_200 = call_insert($sql_insert_200,"SELECT LAST_INSERT_ID() AS 'ID'");
		$result_gestion = mysql_fetch_array($datos_200["ultimo_id"]);
		$reg_fil_200 = $datos_200['num_filas_insert'];
	}

	if ($gastos == 1) {
		
		$arr = ["Notificacion Art. 44 y Requerimiento de Pago",
				"Notificacion Personal y Requerimiento de Pago",
				"Notificacion Personal, Requerimiento de Pago y Oposicion"];

		if ($identificadorEtapa == 2 && in_array($descGasto, $arr)) {
			$sql =  "SELECT * FROM op_eta_proce WHERE CSCASENO = '{$numJuicio}' AND CSSTGID = 2 AND CSSTDT > 0 AND CSENDDT > 0";
			$q = call_select($sql, "");
			if ($q["num_filas"] == 0) {
				echo 'gastos_warn';
				exit;
			}
		}

		$sql_insert_gastos = $var_insert_into."op_gastos (CSCASENO, CETYPE, EXSTGID, EXSUPPLIER, EXINVOICE, EXAGENCY, EXAMT, EXAUTDT, EXCOLLID, EXCOLSUP, EXDESC, EXIDESC, EXTYPE, EXSTYPE, USUSUARIO) ".$var_values."('".$numJuicio."', '".$idenJuicio."', '".$identificadorEtapa."', '".$proveedor."', '".$numFactura."', 'SERVICOB', '".$montoGas."', '".cambiafechaNormal_a_MySQL($fechaAuto)."', 'SBERTERO', 'SBERTERO', '".$descGasto."', '".$desFact."', '".$tipoGasto."', '".$subtipoGasto."', '".$_SESSION['username']."')";
		$datos_gastos = call_insert($sql_insert_gastos,"SELECT LAST_INSERT_ID() AS 'ID'");
		$result_gastos = mysql_fetch_array($datos_gastos["ultimo_id"]);
		$reg_fil_gastos = $datos_gastos['num_filas_insert'];
	}

	$date = date('Y-m-d');
	$sql_insert_informe = $var_insert_into."informe_datos (ID_ETA_PROCE, ID_200_GESTION, ID_GASTOS, ID_JUICIO, FECHA_INSERT) ".$var_values."('".$result_proce["ID"]."', '".$result_gestion["ID"]."', '".$result_gastos["ID"]."', '".$numJuicio."', '".$date."')";
	call_insert($sql_insert_informe,"");

	//echo $sql_insert_eta."<br>".$sql_insert_200."<br>".$sql_insert_gastos;

	/*$datos_info = call_insert($sql_insert_info,"");
	$reg_fil_info = $datos_info['num_filas_insert'];*/

	if ($reg_fil_eta == 1 || $reg_fil_200 == 1 || $reg_fil_gastos == 1){
		echo "<div align='center' class='alert alert-success alert-dismissible role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>�</span></button>Los datos han sido ingresados con éxito</div>";
	} else{
		echo '<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">�</span></button><strong>Lo sentimos</strong> no se han podido ingresar los datos, intente nuevamente.</div>';
	}


break;

case "12":

	$texto="'".strtoupper($_GET["idTipoJui"])."'";

	if ($texto  == "'EJCPMM 5'"){
	 $texto="'EJCPMM+5'";
	}

	$datos=array();
	$datos=call_select($var_select."`CD_STGID`,`CD_DESC` ".$var_from."`etapas_procesales` ".$var_where."`CD_TYPE`= ".$texto,"");

	?>

     							<!--<div class="col-lg-6">-->
     									<div class="form-group">
                                            <label>Identificador de la Etapa</label>
                                            <select class="form-control" id="identEtapa" name="identEtapa">
                                            	<option value="sel">Seleccione</option>
                                            	<?php
													//($result["DESCRIPCION"])
												  while ($result=mysql_fetch_array($datos['registros'])) {
												 	echo '<option value="'.$result["CD_STGID"].'">'.$result["CD_DESC"].'</option>';
												  }
												?>
                                            </select>
                                       </div><!-- /.form-group -->
                                <!--</div><!-- /.col-lg-6 -->

	<?php

break;

case "13":

		$tipo = $_FILES['archivo']['type'];
		$tamanio = $_FILES['archivo']['size'];
		$archivotmp = $_FILES['archivo']['tmp_name'];
		$lineas = file($archivotmp);
		$i=0;

		foreach ($lineas as $linea_num => $linea)
		{

		   if ($i != 0)
		   {
			   $datos = explode(";",$linea);
			   $numJuicio = trim($datos[0]);
			   $idCliente = trim($datos[1]);
			   $juzgado = trim(utf8_encode($datos[2]));
			   $cedossierid = trim($datos[3]);
			   $idJuicio = trim($datos[4]);
			   $nombre = trim($datos[5]);

			   echo $sql_insert = "INSERT INTO relacion_cliente_juicio (NUM_JUICIO, ID_CLIENTE, CECRTID, CEDOSSIERID, CETYPE, nombre) VALUES ('".$numJuicio."', '".$idCliente."', '".$juzgado."', '".$cedossierid."', '".$idJuicio."', '".$nombre."');";
			   //call_insert($sql_insert,"");

		   }

		   $i++;

		}

break;

case "14":

	$numJuicio = $_GET["numJuicio"];
	$idenJuz = $_GET["idenJuz"];
	$numExpe =	$_GET["numExpe"];
	$rut = $_GET["rut"];
	$idenJui = $_GET["idenJui"];
	$fechaDem = $_GET["fechaDem"];

	if ($idenJui  == "EJCPMM 5"){
		$idenJui="EJCPMM+5";
	}

	$sql_update_relacion = $var_update."relacion_cliente_juicio ".$var_set."CECRTID='".$idenJuz."', CEDOSSIERID='".$numExpe."' ".$var_where."NUM_JUICIO=".$numJuicio;
	$datos_update = call_update($sql_update_relacion,"");
	$reg_fil_update = $datos_update['nro_reg_actualizado'];

	$sql_insert_info = $var_insert_into."op_info_juicios (IDENTIFICADOR, CEDOSSIERID, CNCASENO, CESSNUM, CECRTID, CELWSTDT, CETYPE, USUSUARIO)".$var_values."(902, '".$numExpe."', '".$numJuicio."', '".$rut."', '".$idenJuz."', '".$fechaDem."', '".$idenJui."', '".$_SESSION['username']."')";
	$datos_insert = call_insert($sql_insert_info,"");
	$reg_fil_insert = $datos_insert['num_filas_insert'];

	if ($reg_fil_insert==1 && $reg_fil_update==1){

		echo "<div align='center' class='alert alert-success alert-dismissible role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>�</span></button>Los datos han sido ingresados con �xito</div>";

	}else{
		echo '<div class="alert alert-danger alert-dismissible" role="alert" align="center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">�</span></button><strong>Lo sentimos</strong> no se han podido ingresar los datos, intente nuevamente.</div>';
	}

break;

}
?>