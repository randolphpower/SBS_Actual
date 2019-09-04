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

  $texto="'%".strtoupper($_GET["numJuicio"])."%'";


  $hint="";

	/****** CONSULTA *******/
	$sql_consulta=$var_select." a.*, (SELECT b.CT_DESC FROM tipos_juicio b where a.CETYPE = b.CT_TYPE) AS NOM_CT_TYPE ".$var_from."relacion_cliente_juicio a ".$var_where."`ID_CLIENTE` ".$var_like.$texto;

	$datos = array();
	$datos = call_select($sql_consulta,"");

	$i=0;
	while($result=mysql_fetch_array($datos['registros'])){

		if($result["CETYPE"]==""){
			if ($hint=="") {

			  $hint="<span style='cursor:pointer;' onclick='accionaNumJuicio(\"".$result["NUM_JUICIO"]."\",\"".$result["ID_CLIENTE"]."\",\"".$result["CETYPE"]."\",\"".$result["CT_DESC"]."\")' ><b> Cliente: ".($result["ID_CLIENTE"])." - Numero del Juicio: ".$result["NUM_JUICIO"]."</b></span><br />";
			} else {
			  $hint=$hint . "<span style='cursor:pointer;' onclick='accionaNumJuicio(\"".$result["NUM_JUICIO"]."\",\"".$result["ID_CLIENTE"]."\",\"".$result["CETYPE"]."\",\"".$result["CT_DESC"]."\")' > Cliente: ".$result["ID_CLIENTE"]." - Numero del Juicio: ".$result["NUM_JUICIO"]."</span><br />";
			}
		}else{
			if ($hint=="") {

			  $hint="<span style='cursor:pointer;' onclick='accionaNumJuicio(\"".$result["NUM_JUICIO"]."\",\"".$result["ID_CLIENTE"]."\",\"".$result["CETYPE"]."\",\"".$result["NOM_CT_TYPE"]."\")' ><b> Cliente: ".($result["ID_CLIENTE"])." - Numero del Juicio: ".$result["NUM_JUICIO"]."</b></span><br />";
			} else {
			  $hint=$hint . "<span style='cursor:pointer;' onclick='accionaNumJuicio(\"".$result["NUM_JUICIO"]."\",\"".$result["ID_CLIENTE"]."\",\"".$result["CETYPE"]."\",\"".$result["NOM_CT_TYPE"]."\")' > Cliente: ".$result["ID_CLIENTE"]." - Numero del Juicio: ".$result["NUM_JUICIO"]."</span><br />";
			}
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

  $texto="'%".strtoupper($_GET["tipoJuicio"])."%'";

  $hint="";

	//SELECT `CT_TYPE`, `CT_DESC` from tipos_juicio where `CT_TYPE` like 'c%'
	/****** CONSULTA *******/
	$sql_consulta=$var_select."`CT_TYPE`,`CT_DESC` ".$var_from."tipos_juicio ".$var_where."(`CT_TYPE` ".$var_like.$texto." or `CT_DESC` ".$var_like.$texto.")";

	$datos = array();
	$datos = call_select($sql_consulta,"");

	$i=0;
	while($result=mysql_fetch_array($datos['registros'])){


		if ($hint=="") {

		  $hint="<span style='cursor:pointer;' onclick='accionaTipoJuicio(\"".$result["CT_DESC"]."\",\"".$result["CT_TYPE"]."\")' ><b>".$result["CT_TYPE"]." - ".$result["CT_DESC"]."</b></span><br />";
        } else {
          $hint=$hint . "<span style='cursor:pointer;' onclick='accionaTipoJuicio(\"".$result["CT_DESC"]."\",\"".$result["CT_TYPE"]."\")' >".$result["CT_TYPE"]." - ".$result["CT_DESC"]."</span><br />";
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


case "3":


	$texto="'".strtoupper($_GET["texto"])."'";

	if($texto =="'EJCPMM 5'"){
	 $texto="'EJCPMM+5'";
	}

	//SELECT  `CD_STGID` ,  `CD_DESC` FROM  `etapas_procesales` WHERE  `CD_TYPE` =  "GPREP"
	//echo $texto;
	$datos=array();
	$datos=call_select($var_select."`CD_STGID`,`CD_DESC` ".$var_from."`etapas_procesales` ".$var_where."`CD_TYPE`= ".$texto,"");

	?>

     							<!--<div class="col-lg-6">-->
     									<div class="form-group">
                                            <label>Identificador de la Etapa</label>
                                            <select class="form-control" id="idEtapa" name="">
                                            	<option value="defecto">Seleccione</option>
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

break;//Finaliza colocar nombre de la etapa procesal


case "4":

  $texto="'%".strtoupper($_GET["descGasto"])."%'";


  $hint="";

	//SELECT  `LE_ID` ,  `LE_CODE` ,  `LE_DESCRIPTION` FROM  `gastos_judiciales` WHERE  `LE_DESCRIPTION` LIKE  '%E%'

	/****** CONSULTA *******/
	$sql_consulta=$var_select."`LE_ID`,`LE_CODE`, `LE_DESCRIPTION`, `LE_MONTO` ".$var_from."gastos_judiciales ".$var_where."(`LE_DESCRIPTION` ".$var_like.$texto.")";

	$datos = array();
	$datos = call_select($sql_consulta,"");

	$i=0;
	while($result=mysql_fetch_array($datos['registros'])){



		if ($hint=="") {

		  $hint="<span style='cursor:pointer;' onclick='accionaDesGastos(\"".$result["LE_ID"]."\",\"".$result["LE_CODE"]."\",\"".trim($result["LE_DESCRIPTION"])."\",\"".$result["LE_MONTO"]."\")' ><b>".$result["LE_DESCRIPTION"]."</b></span><br />";
        } else {
          $hint=$hint . "<span style='cursor:pointer;' onclick='accionaDesGastos(\"".$result["LE_ID"]."\",\"".$result["LE_CODE"]."\",\"".trim($result["LE_DESCRIPTION"])."\",\"".$result["LE_MONTO"]."\")' >".$result["LE_DESCRIPTION"]."</span><br />";
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


case "5":

	$texto=$_GET["idenJui"];

	if($texto =="EJCPMM 5"){
	 $texto="EJCPMM+5";
	}

	//echo $_GET["vacio"];
	if($_GET["vacio"] ==1){
			$sql_update=$var_update."relacion_cliente_juicio ".$var_set."CETYPE = '".$texto."' ".$var_where."NUM_JUICIO = ".$_GET["numJuicio"];
			//echo " ".$sql_update;
			call_update($sql_update,"");
	}

//numJuicio, numfactura, montoGasto, idAbogado, descGasto, tipoGasto, idenJui, idProveedor, idDespacho, fechaAuto, idAbogGasto, descFactura, subTipoGasto, etapasPro
$fechaAuto = $_GET["fechaAuto"];

 	//"INSERT INTO $tabla_db1 (nombre,email,fecha) VALUES ('$nombre','$email','$fecha')";
	$sql_insert=$var_insert_into." op_gastos (CSCASENO, CETYPE, EXSTGID, EXSUPPLIER, EXINVOICE, EXAGENCY, EXAMT, EXAUTDT, EXCOLLID, EXCOLSUP, EXDESC, EXIDESC, EXTYPE, EXSTYPE, USUSUARIO) ".$var_values."('".$_GET["numJuicio"]."','".$texto."','".$_GET["etapasPro"]."','".$_GET["idProveedor"]."','".$_GET["numFactura"]."','".$_GET["idDespacho"]."','".$_GET["montoGasto"]."','".$fechaAuto."','".$_GET["idAbogado"]."','".$_GET["idAbogGasto"]."','".utf8_decode($_GET["descGasto"])."','".$_GET["descFactura"]."','".$_GET["tipoGasto"]."','".$_GET["subtipoGasto"]."','".$_SESSION['username']."')";
	
	//echo $sql_insert;

	call_insert($sql_insert,"");

	?>


<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Bien Hecho!</strong> Su operaci&oacute;n fue almacenada y procesada.
</div>

    <?php

break;//Finaliza ingresar el identificador del cliente y del juicio


case "6":
	//echo "llegue ";
	$datos = array();
	$sql_enviar=$var_select."CSCASENO, CETYPE, EXSTGID, EXSUPPLIER, EXINVOICE, EXAGENCY, LPAD(EXAMT,15,'0') AS EXAMT, DATE_FORMAT(EXAUTDT, '%m%d%Y') AS EXAUTDT, EXCOLLID, EXCOLSUP, EXDESC, EXIDESC, EXTYPE, EXSTYPE, EXSENDDT ".$var_from."op_gastos ".$var_where."EXSENDDT = 0 ";
	//echo $sql_enviar;

	$datos = call_select($sql_enviar,"");

	$hoy = date("Ymd");
	$archivo= "gastos_SERVICOBRANZA_".$hoy.".txt";

	if (file_exists($archivo)) {
		 unlink($archivo);
	}



	$fp=fopen($archivo,"x");

	while($result=mysql_fetch_array($datos['registros'])){

	 $linea=agregarespacios($result["CSCASENO"],25).agregarespacios($result["CETYPE"],20)."00".$result["EXSTGID"].agregarespacios($result["EXSUPPLIER"],80).agregarespacios($result["EXINVOICE"],20).$result["EXAGENCY"].agregarespacios($result["EXAMT"],15).agregarespacios($result["EXAUTDT"],8).$result["EXCOLLID"].$result["EXCOLSUP"].agregarespacios($result["EXDESC"],200).agregarespacios($result["EXIDESC"],40).agregarespacios($result["EXTYPE"],20).agregarespacios($result["EXSTYPE"],20);
	 //echo $linea=$linea."-".strlen($linea);

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
	$filename = "gastos_SERVICOBRANZA_".$hoy.".zip";

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
				$sql_update=$var_update."op_gastos ".$var_set."EXSENDDT = ".$fecha." ".$var_where."EXSENDDT = 0";
				//echo " ".$sql_update;
				call_update($sql_update,"");



	     }


break;

case "7":

  	//echo $_GET["id"];
	// Delete from `op_info_juicios` where CNCASENO='Marcelo' AND CESSNUM = ;

	/****** CONSULTA *******/
	$sql_delete=$var_delete_from."`op_gastos` ".$var_where."(`id`= ".$_GET["id"].")";

	//echo $sql_delete;
	call_delete($sql_delete,"");



break;


}
?>