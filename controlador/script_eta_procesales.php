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

case "2":

	$texto="'".strtoupper($_GET["texto"])."'";

	if($texto =="'EJCPMM 5'"){
	 $texto="'EJCPMM+5'";
	}

	//SELECT  `CD_STGID` ,  `CD_DESC` FROM  `etapas_procesales` WHERE  `CD_TYPE` =  "GPREP"

	$datos=array();
	$datos=call_select($var_select."`CD_STGID`,`CD_DESC` ".$var_from."`etapas_procesales` ".$var_where."`CD_TYPE`= ".$texto,"");

	?>

     							<!--<div class="col-lg-6">-->
     									<div class="form-group">
                                            <label>Identificador de la Etapa</label>
                                            <select onChange="blocFechaIni()" class="form-control" id="idEtapa" name="">
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

break;//Finaliza colocar nombre de la etapa procesal


case "3":

  $texto="'%".strtoupper($_GET["idCliente"])."%'";


  $hint="";

	//SELECT a.*, (SELECT b.CT_DESC FROM tipos_juicio b Where a.`CETYPE`= b.CT_TYPE) as NOM_CT_TYPE FROM relacion_cliente_juicio a WHERE (`ID_CLIENTE` like '%1%')
	$sql_consulta1=$var_select." a.*, (SELECT b.CT_DESC FROM tipos_juicio b where a.CETYPE = b.CT_TYPE) AS NOM_CT_TYPE ".$var_from."relacion_cliente_juicio a ".$var_where."`ID_CLIENTE` ".$var_like.$texto;

	$datos = array();
	$datos = call_select($sql_consulta1,"");

	$i=0;
	while($result=mysql_fetch_array($datos['registros'])){

		if($result["CETYPE"] ==""){

			 accionaNumJuicio($result["CETYPE"]);
				
			
		}else{
			
			 accionaNumJuicio($result["CETYPE"]);			
		}

	 $i=$i+1;
	}//Fin mientras

break;


case "4":

    $texto=$_GET["tipoCaso"];

	if($texto =="EJCPMM 5"){
	 $texto="EJCPMM+5";
	}

	if($_GET["vacio"] ==1){
		$sql_update=$var_update."relacion_cliente_juicio ".$var_set."CETYPE = '".$texto."' ".$var_where."NUM_JUICIO = ".$_GET["idCliente"];
		//echo " ".$sql_update;
		call_update($sql_update,"");
	}

//echo $_GET["vacio"];
$fechaIni = $_GET["fechaIni"];
$fechaFin = $_GET["fechaFin"];		
 
	
		$sql_insert=$var_insert_into." op_eta_proce (CSCASENO, CSTYPE, CSSTGID, CSSTDT, CSENDDT, USUSUARIO) ".$var_values."('".$_GET["idCliente"]."','".$texto."','".$_GET["casoEtapa"]."','".$fechaIni."','".$fechaFin."','".$_SESSION['username']."')";
	
	//echo $sql_insert;

	call_insert($sql_insert,"");

	?>


<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Bien Hecho!</strong> Su operaci&oacute;n fue almacenada y procesada.
</div>

    <?php

break;//Finaliza ingresar datos etapas procesales

case "5":
	//echo "llegue ";

	$sql_enviar=$var_select."`CSCASENO`,  `CSTYPE`,  `CSSTGID`, DATE_FORMAT( `CSSTDT`, '%m%d%Y' ) AS `CSSTDT`, DATE_FORMAT( `CSENDDT`, '%m%d%Y' ) AS `CSENDDT`, `CSSNDT` ".$var_from."op_eta_proce ".$var_where."`CSSNDT` = 0";
	//echo $sql_enviar;

	$datos = array();
	$datos = call_select($sql_enviar,"");


	$hoy = date("Ymd");
	$archivo= "etapas_SERVICOBRANZA_".$hoy.".txt";

	if (file_exists($archivo)) {
		 unlink($archivo);
	}



	$fp=fopen($archivo,"x");

	while($result=mysql_fetch_array($datos['registros'])){

	 $linea=agregarespacios($result["CSCASENO"],25).agregarespacios($result["CSTYPE"],20)."00".$result["CSSTGID"].agregarespacios($result["CSSTDT"],8).agregarespacios($result["CSENDDT"],8);

		fputs($fp,$linea);
		fputs($fp,chr(13).chr(10));


	}
	fclose($fp);
	

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
	$filename = "etapas_SERVICOBRANZA_".$hoy.".zip";

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
		$sql_update=$var_update."op_eta_proce ".$var_set."CSSNDT = ".$fecha." ".$var_where."CSSNDT = 0";
		//echo " ".$sql_update;
	call_update($sql_update,"");


     }

	 $sftp = new Net_SFTP('jupiter.onvision.cl');
     if (!$sftp->login('sftpclaservbr', 'sftpclaservbr.2021')) {
        echo "error de login";
     }else {
	 	$sftp->put("/ftp/Entrada/".$filename, $filename, NET_SFTP_LOCAL_FILE);
     }

break;

case "6":

  	//echo $_GET["id"];
	// Delete from `op_info_juicios` where CNCASENO='Marcelo' AND CESSNUM = ;

	/****** CONSULTA *******/
	$sql_delete=$var_delete_from."`op_eta_proce` ".$var_where."(`id`= ".$_GET["id"].")";

	//echo $sql_delete;
	call_delete($sql_delete,"");


break;

}
?>