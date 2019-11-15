<?php
session_start();
require_once("../modelo/conectarBD.php");
require_once("../modelo/consultaSQL.php");
require_once("script_general.php");


switch ($_GET["opcion"]) {
		
case "1":

//comprobamos que sea una petici�n ajax
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
	$archivo = $_FILES['archivo']['name'];
	$empresa= $_GET["empresa"];
	
	$raiz="../temp";
	
	$url=$raiz."/".$archivo;
	
	$bool=false;
	
	//echo $archivo;
	
	if(!is_dir($raiz)){
			mkdir($raiz."/", 0777);
			$bool=true;
		
	}else if(is_dir($raiz)){
		$bool=true;   
	}
	
	if($bool==true){	
		//comprobamos si el archivo ha subido
		if ($archivo && move_uploaded_file($_FILES['archivo']['tmp_name'],$url))
		{
			require_once '../PHPExcel.php';
			$inputFileType = PHPExcel_IOFactory::identify($url);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($url);
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();
			$datos=array();
			$nodo=array();
			$contador=0;
			
			for ($row = 2; $row <= $highestRow; $row++){ 
					
					
					
					$nodo["ID"]=0;
					//$num_pagare1=trim($sheet->getCell("B".$row)->getValue());
					//$num_pagare2=str_replace("-", "", $sheet->getCell("B".$row)->getValue());
					//$num_pagare=str_replace(".", "CON", $num_pagare2);				
					
					$nodo["NRO_PAGARE_ORIGINAL"]=$sheet->getCell("A".$row)->getValue();
					//$nodo["NRO_PAGARE_ALTERADO"]=$num_pagare;
					$nodo["RUT_COMPLETO"]=$sheet->getCell("B".$row)->getValue().$sheet->getCell("C".$row)->getValue();
					$nodo["RUT_SIN_DV"]=$sheet->getCell("B".$row)->getValue();
					$nodo["DV_RUT"]=$sheet->getCell("C".$row)->getValue();
					$nodo["NOMBRE"]=$sheet->getCell("D".$row)->getValue();
					$nodo["DIRECCION"]="";
					$nodo["COMUNA"]="";
					$nodo["DISTRITO"]="";
					$contador++;
					/*echo $sheet->getCell("A".$row)->getValue()." | ";
					echo $sheet->getCell("B".$row)->getValue()." | ";
					echo $sheet->getCell("C".$row)->getValue()." | ";
					echo $sheet->getCell("D".$row)->getValue()." | ";
					echo $sheet->getCell("E".$row)->getValue()." | ";
					echo $sheet->getCell("F".$row)->getValue()." | ";
					echo $sheet->getCell("G".$row)->getValue()." | ";
					echo $sheet->getCell("H".$row)->getValue()." | ";
					echo "<br>";*/
					$datos[]=$nodo;
					unset($nodo);
			}
			
			if($contador>0){
				$sql_insert_eta = $var_insert_into."custodia_up_info (ESTADO, ID_EMPRESA, CANTIDAD_REGISTROS, FECHA) ".$var_values."('0', '".$empresa."', '".$contador."', '".fecha_actual()."')";

				$datos_eta = call_insert($sql_insert_eta,"SELECT LAST_INSERT_ID() AS 'ID'");
				$result_proce = mysql_fetch_array($datos_eta["ultimo_id"]);
				$id_insert = $result_proce['ID'];
				
				
				$sql_insert=$var_insert_into." custodia_up (ID_CUSTODIA_INFO, ID_REFERENCIA, NRO_PAGARE_ORIGINAL, NRO_PAGARE_ALTERADO, RUT_COMPLETO, RUT_SIN_DV, DV_RUT, NOMBRE, DIRECCION, COMUNA, DISTRITO, ESTADO) ".$var_values;
				$sql_insert_datos="";
				
				$i=0;
				while($i<count($datos)){
					
					$sql_insert_datos=$sql_insert_datos." ('".$id_insert."','".$datos[$i]["ID"]."','".$datos[$i]["NRO_PAGARE_ORIGINAL"]."','".$datos[$i]["NRO_PAGARE_ALTERADO"]."','".$datos[$i]["RUT_COMPLETO"]."','".$datos[$i]["RUT_SIN_DV"]."','".$datos[$i]["DV_RUT"]."','".utf8_encode($datos[$i]["NOMBRE"])."','".utf8_encode($datos[$i]["DIRECCION"])."','".utf8_encode($datos[$i]["COMUNA"])."','".utf8_encode($datos[$i]["DISTRITO"])."','0'),";
					
					$i++;
				}
				
				$sql_insert_datos=substr($sql_insert_datos,1,-1);
				$sql_insert=$sql_insert.$sql_insert_datos.";";
				call_insert($sql_insert, "");
				
				echo $contador;
				
			}
			
			//var_dump($datos);
			
			chmod($url, 0755);
			unlink($url);
			sleep(2);
		}
	}//Fin mover archivo
	
}

break;
		
case "2":

$direc="documentos/".$_GET["direc"];
$num_carga=$_GET["num_carga"];
$archivos=$_GET["archivos"];
echo count($_FILES['upload']['name']);
$sql_consulta=$var_select_asterisk_from."custodia_up ".$var_where."(ID_CUSTODIA_INFO='".$num_carga."')";
$datos = array();
$datos = call_select($sql_consulta,"");
$num_reg=$datos["num_registros"];
/*
$ult_caracter = substr($direc, -1);    
		
if($ult_caracter=="/"){
	$raiz="../".$direc;
	$raiz2=$direc;
	$total_arch = count(glob($raiz.'{*.pdf}',GLOB_BRACE));
}else{
	$raiz="../".$direc."/";
	$raiz2=$direc."/";
	$total_arch = count(glob($raiz.'{*.pdf}',GLOB_BRACE));
}*/

$raiz="../".$direc."/";
$raiz2=$direc."/";
$total_arch = count(glob($raiz.'{*.pdf}',GLOB_BRACE));

/*if($num_reg!=$total_arch){
	echo $num_reg.$total_arch;
	exit();
}*/
		
$sql_consulta=$var_select_asterisk_from."custodia_up ".$var_where."(ID_CUSTODIA_INFO='".$num_carga."')";
$datos = array();
$datos = call_select($sql_consulta,"");
$num_reg=$datos["num_registros"];
$disable="";
?>


<div class="card">
	<div class="card-header bg-info">Resultado de vinculaci&oacute;n</div>
	<div class="card-block">
		<div class="row">
			<div class="col-sm-12">
				
				<table class="table table-hover table-striped small">
				   <thead>
					<tr>
						<th>Nro.</th>
						<th>ID</th>
						<th>Nro. de Pagare</th>
						<th>Rut</th>
						<th>dv</th>
						<th>Nombre</th>
						<th>PDF</th>
					</tr>
				   </thead>   
				   <tbody>
				   <?php
					$i=1;
					$bool_existe_pdf_null=false;
					$ficheros = scandir($raiz);
				   	while($result=mysql_fetch_array($datos['registros'])){

					   	$bool_url=false;
					   
					   	if($result['ESTADO']==0){
						   	$bool_existe_pdf_null=true;
						   	$url=$raiz2.$result['RUT_SIN_DV'].'.pdf';
						   	//$encontrado=count(glob($raiz.$result['RUT_SIN_DV']."*.pdf",GLOB_BRACE));												   
						   	foreach ($ficheros as $item){
								$pos = strpos($item, $result['RUT_SIN_DV']);
								if ($pos === false) {
									$encontrado=0;
								} else {
									$encontrado=1;
								}
								if($encontrado==1){
									$bool_url=true;
									$sql_consulta=$var_update."custodia_up ".$var_set." ESTADO=1, URL='".$raiz2.$item."' ".$var_where."(ID='".$result['ID']."')";
									call_update($sql_consulta);
								}
	    				   	}						
					   }else{
						   $bool_url=true;
						   $url=$result['URL'];
					   }


				   ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $result['ID_REFERENCIA']; ?></td>
						<td><?php echo $result['NRO_PAGARE_ORIGINAL']; ?></td>
						<td><?php echo $result['RUT_SIN_DV']; ?></td>
						<td><?php echo $result['DV_RUT']; ?></td>
						<td><?php echo $result['NOMBRE']; ?></td>
						<td align="center">
						<?php
						   if($bool_url==true){

						?>
						<a target="_blank" onclick="window.open('<?php echo $url;?>', '<?php echo "Archivo: ".$result['NRO_PAGARE_ALTERADO']; ?>', 'directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=600, height=800')" style="cursor:pointer;"><i class="zmdi zmdi-collection-pdf zmdi-hc-2x"></i></a></td>
						<?php
						   }else{
							   $disable="disabled=disabled";
						?>
						<a target="_blank" title="Registro <?php echo $result['NRO_PAGARE_ORIGINAL']; ?> no pudo ser vinculado al PDF" ><i class="zmdi zmdi-alert-triangle zmdi-hc-2x"></i></a></td>
						<?php
						   }			
						?>

					</tr>
					<?php
						   $i++;

				   }//Fin mientras
					?>
				   </tbody>
				</table>
				
			</div>
		</div>
	</div>
</div>
<br>
<?php 
		if($bool_existe_pdf_null){
?>
<div class="card">
	<div class="card-header bg-danger">Archivos NO Vinculados</div>
	<div class="card-block">
		<div class="row">
			<div class="col-sm-12">
				<?php
				
					//`ID_CUSTODIA_INFO`, `ID_REFERENCIA`, `NRO_PAGARE_ORIGINAL`, `NRO_PAGARE_ALTERADO`, `RUT_COMPLETO`, `RUT_SIN_DV`, `DV_RUT`, `NOMBRE`, `DIRECCION`, `COMUNA`, `DISTRITO`, `ESTADO`, `URL`
					$direc_real=$raiz;
					$arreglo_pend1 = array();
					$directorio = opendir($direc_real); //ruta actual
					while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
					{
						if ((!is_dir($archivo)) && (pathinfo( strtolower($archivo), PATHINFO_EXTENSION )=="pdf"))//verificamos si es o no un directorio
						{							
							$cadena = explode(' ',trim($archivo))[0];
							$sql_consulta=$var_select_asterisk_from."custodia_up ".$var_where."(ID_CUSTODIA_INFO='".$num_carga."') ".$var_and."(RUT_SIN_DV='".$cadena."')";
							$datosx = array();
							$datosx = call_select($sql_consulta,"");

							if($datosx["num_registros"]!=1){
								$nodo["nombre"]=$archivo;
								$nodo["ruta"]=$direc."/".$archivo;
								$arreglo_pend1[]=$nodo;
								unset($nodo);
							}		
							unset($datosx);

						}
					}
		
		
					?>
					<table class="table table-hover table-striped">
				   <thead>
					<tr>
						<th>Nro.</th>
						<th>Nombre de Archivo</th>
						<th>Ruta</th>
						<th style="text-align: center">Asignar</th>
					</tr>
				   </thead>   
				   <tbody>
				   <?php
					$i=0;

				   while($i<count($arreglo_pend1)){

				   ?>
					<tr>
						<td><?php echo $i+1; ?></td>
						<td><?php echo $arreglo_pend1[$i]["nombre"]; ?></td>
						<td><?php echo $arreglo_pend1[$i]["ruta"]; ?></td>
						<td align="center"><a data-toggle="modal" data-target="#exampleModal" data-whatever="<?php echo $num_carga; ?>|<?php echo $arreglo_pend1[$i]["ruta"]; ?>|<?php echo $arreglo_pend1[$i]["nombre"]; ?>" style="cursor: pointer;"><i class="zmdi zmdi-alert-triangle zmdi-hc-2x"></i></a></td>
					</tr>
					<?php
						   $i++;

				   }//Fin mientras
					?>
				   </tbody>
				</table>
				
			</div>
		</div>
	</div>
</div>
<?php 
 	}
?>
<div align="right"><button type="button" <?php echo $disable ?> onClick="almacenar_carga_final(<?php echo $num_carga ?>)" class="btn btn-success boton_guardar" >Almacenar Registros <i class="zmdi zmdi-folder zmdi-hc-lg"></i></button></div>

		
<?php
/*
echo $total_arch."<br>";
echo $_GET["num_carga"]."<br>";
echo $_GET["direc"]."<br>";
*/
		
break;
		
case "2d":// DUPLICA ANTES DE MODIFICAR

$direc=$_GET["direc"];
$num_carga=$_GET["num_carga"];
$direc=$_GET["direc"];

$sql_consulta=$var_select_asterisk_from."custodia_up ".$var_where."(ID_CUSTODIA_INFO='".$num_carga."')";
$datos = array();
$datos = call_select($sql_consulta,"");
$num_reg=$datos["num_registros"];

$ult_caracter = substr($_GET["direc"], -1);    
		
if($ult_caracter=="/"){
	$raiz="../".$direc;
	$raiz2=$direc;
	$total_arch = count(glob($raiz.'{*.pdf}',GLOB_BRACE));
}else{
	$raiz="../".$direc."/";
	$raiz2=$direc."/";
	$total_arch = count(glob($raiz.'{*.pdf}',GLOB_BRACE));
}

if($num_reg!=$total_arch){
	echo 1;
	exit();
}
		
$sql_consulta=$var_select_asterisk_from."custodia_up ".$var_where."(ID_CUSTODIA_INFO='".$num_carga."')";
$datos = array();
$datos = call_select($sql_consulta,"");
$num_reg=$datos["num_registros"];
$disable="";
?>
<table class="table table-hover table-striped small">
   <thead>
    <tr>
    	<th>Nro.</th>
        <th>ID</th>
        <th>Nro. de Pagare</th>
        <th>Rut</th>
        <th>dv</th>
        <th>Nombre</th>
        <th>Direcci&oacute;n</th>
        <th>Comuna</th>
        <th>Distrito</th>
        <th>PDF</th>
    </tr>
   </thead>   
   <tbody>
   <?php
	$i=1;
	
   while($result=mysql_fetch_array($datos['registros'])){
	   
	   $bool_url=false;
	   
	   if($result['ESTADO']==0){
		   $url=$raiz2.$result['NRO_PAGARE_ALTERADO'].'.pdf';
		   $encontrado=count(glob($raiz.$result['NRO_PAGARE_ALTERADO'].'.pdf',GLOB_BRACE));
		   
		   
		   if($encontrado==1){
			   $bool_url=true;
			   $sql_consulta=$var_update."custodia_up ".$var_set." ESTADO=1, URL='".$url."' ".$var_where."(ID='".$result['ID']."')";
			   call_update($sql_consulta);
		   }
		   
	   }else{
		   $bool_url=true;
		   $url=$result['URL'];
	   }
	   
   ?>
    <tr>
 	   	<td><?php echo $i; ?></td>
 	   	<td><?php echo $result['ID_REFERENCIA']; ?></td>
    	<td><?php echo $result['NRO_PAGARE_ORIGINAL']; ?></td>
        <td><?php echo $result['RUT_SIN_DV']; ?></td>
        <td><?php echo $result['DV_RUT']; ?></td>
        <td><?php echo $result['NOMBRE']; ?></td>
        <td><?php echo $result['DIRECCION']; ?></td>
        <td><?php echo $result['COMUNA']; ?></td>
        <td><?php echo $result['DISTRITO']; ?></td>
        <td align="center">
        <?php
		   if($bool_url==true){
			
	   	?>
        <a target="_blank" onclick="window.open('<?php echo $url;?>', '<?php echo "Archivo: ".$result['NRO_PAGARE_ALTERADO']; ?>', 'directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=600, height=800')" style="cursor:pointer;"><i class="zmdi zmdi-collection-pdf zmdi-hc-2x"></i></a></td>
        <?php
		   }else{
			   $disable="disabled=disabled";
	   	?>
       	<a target="_blank" onclick="window.open('x.php?id_custodia=<?php echo $result['ID']; ?>&direc=<?php echo $raiz; ?>', '<?php echo "Vincular registro con archivo "; ?>', 'directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=auto, height=auto')" style="cursor:pointer;"><i class="zmdi zmdi-alert-triangle zmdi-hc-2x"></i></a></td>
        <?php
		   }			
	   	?>
	   	
    </tr>
    <?php
	   $i++;
   }//Fin mientras
	?>
  	<tr>
 	   	<td colspan="10" align="right"><button type="button" <?php echo $disable ?> onClick="almacenar_carga_final(<?php echo $num_carga ?>)" class="btn btn-success boton_guardar" >Almacenar Registros <i class="zmdi zmdi-folder zmdi-hc-lg"></i></button></td>
    </tr>
   </tbody>
</table>		
<?php
/*
echo $total_arch."<br>";
echo $_GET["num_carga"]."<br>";
echo $_GET["direc"]."<br>";
*/
		
break;
		
case "3":

$selector=$_GET["selector"];
$nombre_sin_extension=substr($_GET["nombre_arch"],0,-4);   
$id_custodia=$_GET["id"];
$direc_url=$_GET["direc_url"];

$sql_consulta=$var_update."custodia_up ".$var_set." NRO_PAGARE_ALTERADO='".$nombre_sin_extension."', ESTADO=1, URL='".$direc_url.$nombre."' ".$var_where."(ID='".$selector."')";
call_update($sql_consulta);
		
echo 1;

break;
		
case "4":
	
	$id_custodia=$_GET["num_carga"];
		
	$sql_consulta=$var_select."b.ID_EMPRESA, a.* ".$var_from."custodia_up a, custodia_up_info b ".$var_where." (a.ID_CUSTODIA_INFO=b.ID) ".$var_and."(b.ID='".$id_custodia."')";
	$datos = array();
	$datos = call_select($sql_consulta,"");
	$num_reg=$datos["num_registros"];

	$sql_insert=$var_insert_into." registros_custodia (ID_CUSTODIA_INFO, ID_EMPRESA, ID_REFERENCIA, NRO_PAGARE_ORIGINAL, NRO_PAGARE_ALTERADO, RUT_COMPLETO, RUT_SIN_DV, DV_RUT, NOMBRE, DIRECCION, COMUNA, DISTRITO, URL, ID_ESTADO) ".$var_values;
	$sql_insert_datos="";
		
	$raiz="../archivos/".fecha_actual_con_piso()."/";
	/*
	$bool=false;
	
	if(!is_dir($raiz)){
			mkdir($raiz, 0777);
			$bool=true;
		
	}else if(is_dir($raiz)){
		$bool=true;   
	}else{
		echo 2;
		exit();
	}
	*/
	while($result=mysql_fetch_array($datos['registros'])){				
		$url="../".$result["URL"];
		/*$destino=$raiz.$result["NRO_PAGARE_ALTERADO"].'.pdf';			
		if($bool==true){	
			//comprobamos si el archivo ha subido
			if (copy($url,$destino))
			{
				//chmod($url, 0755);
				//unlink($url);
			}
		}//Fin mover archivo			*/
		$sql_insert_datos=$sql_insert_datos." ('".$result["ID_CUSTODIA_INFO"]."','".$result["ID_EMPRESA"]."','".$result["ID_REFERENCIA"]."','".$result["NRO_PAGARE_ORIGINAL"]."','".$result["NRO_PAGARE_ALTERADO"]."','".$result["RUT_COMPLETO"]."','".$result["RUT_SIN_DV"]."','".$result["DV_RUT"]."','".utf8_encode($result["NOMBRE"])."','".utf8_encode($result["DIRECCION"])."','".utf8_encode($result["COMUNA"])."','".utf8_encode($result["DISTRITO"])."','".$url."',1),";
	}

	$sql_insert_datos=substr($sql_insert_datos,1,-1);
	$sql_insert=$sql_insert.$sql_insert_datos.";";
	call_insert($sql_insert, "");
		
	$sql_consulta=$var_update."custodia_up_info ".$var_set." ESTADO=1 ".$var_where."(ID='".$id_custodia."')";
	call_update($sql_consulta);

echo 1;
		
break;
		
case "4n":
	
	$id_custodia=$_GET["num_carga"];
		
	$sql_consulta=$var_select."c.NOMBRE_CARPETA ".$var_from."custodia_up_info b, empresas_afiliadas c ".$var_where." (b.ID='".$id_custodia."') ".$var_and."(b.ID_EMPRESA=c.ID)";
	$datos_empresa = array();
	$datos_empresa = call_select($sql_consulta,"");
	$result_empresa=mysql_fetch_assoc($datos_empresa['registros']);	
	$raiz="../archivos/".$result_empresa["NOMBRE_CARPETA"]."/";
		
	$bool=false;
	$estatus=1;
	
	if(!is_dir($raiz)){
			mkdir($raiz, 0777);
			$bool=true;
		
	}else if(is_dir($raiz)){
		$bool=true;   
	}else{
		$estatus=2;
		echo $estatus;
		exit();
	}
	
	$sql_consulta=$var_select."b.ID_EMPRESA, a.* ".$var_from."custodia_up a, custodia_up_info b ".$var_where." (a.ID_CUSTODIA_INFO=b.ID) ".$var_and."(b.ID='".$id_custodia."')";
	$datos = array();
	$datos = call_select($sql_consulta,"");
	$num_reg=$datos["num_registros"];
	$fecha_actual=fecha_actual();
	while($result=mysql_fetch_assoc($datos['registros'])){

		$sql=$var_select_asterisk_from."registros_custodia ".$var_where." (NRO_PAGARE_ORIGINAL='".$result["NRO_PAGARE_ORIGINAL"]."') ".$var_and."(ID_EMPRESA='".$result["ID_EMPRESA"]."') ";
		$datos_consulta = array();
		$datos_consulta = call_select($sql,"");
		$result_sql=mysql_fetch_assoc($datos_consulta['registros']);
		
		$url="../".$result["URL"];
		$bool_copiar=false;
		$num_pagare2=str_replace("-", "", $result["NRO_PAGARE_ORIGINAL"]);
		$num_pagare=str_replace(".", "CON", $num_pagare2);		
		
		$destino=$raiz.$num_pagare.'.pdf';
		
		if($datos_consulta["num_registros"]==1){
			
			if($result_sql["ID_ESTADO"]==1){
				
				$sql_consulta=$var_update."registros_custodia ".$var_set." ID_REFERENCIA='".$result["ID_REFERENCIA"]."', RUT_COMPLETO='".$result["RUT_COMPLETO"]."', RUT_SIN_DV='".$result["RUT_SIN_DV"]."', DV_RUT='".$result["DV_RUT"]."', NOMBRE='".utf8_encode($result["NOMBRE"])."', DIRECCION='".utf8_encode($result["DIRECCION"])."', COMUNA='".utf8_encode($result["COMUNA"])."', DISTRITO='".utf8_encode($result["DISTRITO"])."', NRO_PAGARE_ALTERADO='".$num_pagare."', URL='".substr($destino,3)."' ".$var_where."(ID_EMPRESA='".$result["ID_EMPRESA"]."') ".$var_and."(NRO_PAGARE_ORIGINAL='".$result["NRO_PAGARE_ORIGINAL"]."') ";
				call_update($sql_consulta);
				$bool_copiar=true;
			
			}else{
				
				$sql_consulta=$var_update."registros_custodia ".$var_set." NRO_PAGARE_ALTERADO='".$num_pagare."', URL='".substr($destino,3)."' ".$var_where."(ID_EMPRESA='".$result["ID_EMPRESA"]."') ".$var_and."(NRO_PAGARE_ORIGINAL='".$result["NRO_PAGARE_ORIGINAL"]."') ";
				call_update($sql_consulta);
				$bool_copiar=true;
			}
			
		}else if($datos_consulta["num_registros"]==0){
			
			$sql_insert=$var_insert_into." registros_custodia (ID_CUSTODIA_INFO, ID_EMPRESA, ID_REFERENCIA, NRO_PAGARE_ORIGINAL, NRO_PAGARE_ALTERADO, RUT_COMPLETO, RUT_SIN_DV, DV_RUT, NOMBRE, DIRECCION, COMUNA, DISTRITO, URL, ID_ESTADO) ".$var_values."('".$result["ID_CUSTODIA_INFO"]."','".$result["ID_EMPRESA"]."','".$result["ID_REFERENCIA"]."','".$result["NRO_PAGARE_ORIGINAL"]."','".$num_pagare."','".$result["RUT_COMPLETO"]."','".$result["RUT_SIN_DV"]."','".$result["DV_RUT"]."','".utf8_encode($result["NOMBRE"])."','".utf8_encode($result["DIRECCION"])."','".utf8_encode($result["COMUNA"])."','".utf8_encode($result["DISTRITO"])."','".substr($destino,3)."',1)";
			//call_insert($sql_insert, "");
			$datos_eta = call_insert($sql_insert,"SELECT LAST_INSERT_ID() AS 'ID'");
			$result_proce = mysql_fetch_array($datos_eta["ultimo_id"]);	
			$id_insert = $result_proce['ID'];

			$sql_insert=$var_insert_into." pagare_historial_custodia (id_registros_custodia, id_estado_custodia, fecha_estado) ".$var_values."(".$id_insert.",1,'".$fecha_actual."')";
			call_insert($sql_insert, "");

			$bool_copiar=true;
			
		}
		
		if($bool==true and $bool_copiar==true){	
			//comprobamos si el archivo ha subido
			if (copy($url,$destino))
			{
				//chmod($url, 0755);
				//unlink($url);
			}
		}//Fin mover archivo
		
		
	}
		
	$sql_consulta=$var_update."custodia_up_info ".$var_set." ESTADO=1 ".$var_where."(ID='".$id_custodia."')";
	call_update($sql_consulta);
	
echo $estatus;
		
break;
		
case "5":
		
//echo $_GET["direc"];
$id_custodia=$_GET["id_custodia"];
$ruta=$_GET["direc"];
$nombre_arch=$_GET["nombre_arch"];

$sql_consulta=$var_select_asterisk_from."custodia_up ".$var_where." (ID_CUSTODIA_INFO='".$id_custodia."') ".$var_and."(URL='')";
$datos = array();
$datos = call_select($sql_consulta,"");
		
?>
<input type="hidden" id="direc_url" value="<?php echo $ruta ?>">
<input type="hidden" id="nombre_arch" value="<?php echo $nombre_arch ?>">
<div class="row">
	<div class="form-group col-sm-9">
		<label>PDF</label>
		<embed class="form-control" src="<?php echo $ruta ?>#toolbar=0" type="application/pdf" width="450" height="400"></embed>
<br>
	</div>

	<div class="form-group col-sm-3">
		<label class="form-check-label">Seleccione el num. de pagare correspondiente</label>
		<select class="form-control" id="selector" name="selector">
			 <option value="0">----Seleccione----</option>
			 <?php 
			 while($result=mysql_fetch_array($datos['registros'])){ 
				echo "<option value='".$result['ID']."' >".$result['NRO_PAGARE_ORIGINAL']."</option>";
			 } 
			 ?>
		</select>
	</div>
</div>

<?php
break;
		
case "6": //Formulario para actualizacion de estado
		
$id_custodia=$_GET["id_custodia"];

$sql_consulta = $var_select." b.NOMBRE_EMPRESA, a.*, c.NOMBRE_ESTADO,dias_en_custodia(a.id) AS dias ".$var_from."registros_custodia a, empresas_afiliadas b, estado_custodia c "
.$var_where."(a.ID_EMPRESA=b.ID) ".$var_and."(a.ID_ESTADO=c.ID_ESTADO)".$var_and." (a.ID='".$id_custodia."')";

$datos = array();
$datos = call_select($sql_consulta,"");
$result=mysql_fetch_array($datos['registros']);

$sql_estados_historia = "SELECT estado_custodia.NOMBRE_ESTADO,pagare_historial_custodia.fecha_estado,pagare_historial_custodia.observacion,pagare_historial_custodia.usuario ";
$sql_estados_historia .= "FROM pagare_historial_custodia INNER JOIN estado_custodia ON pagare_historial_custodia.id_estado_custodia = estado_custodia.ID_ESTADO ";
$sql_estados_historia .= "WHERE (pagare_historial_custodia.id_registros_custodia=".$id_custodia.") ORDER BY pagare_historial_custodia.fecha_estado DESC LIMIT 5;";
$datos_historia = call_select($sql_estados_historia,"");

$sql_estados = "SELECT * FROM estado_custodia;";
$datos_estado = call_select($sql_estados,"");

?>
<h5>Datos</h5>
<input value="<?php echo $id_custodia ?>" type="hidden" id="idCustodia">
<div class="row">
	<div class="form-group col-sm-2">
		<label>No. Pagare</label>
		<input value="<?php echo $result['NRO_PAGARE_ORIGINAL'] ?>" disabled="disabled" class="form-control">
	</div>
	<div class="form-group col-sm-1">
		<label >Rut</label>
		<input value="<?php echo $result['RUT_SIN_DV'] ?>" disabled="disabled"  class="form-control">
	</div>
	<div class="form-group col-sm-1">
		<label >DV</label>
		<input value="<?php echo $result['DV_RUT'] ?>" disabled="disabled"  class="form-control">
	</div>
	<div class="form-group col-sm-6">
		<label >Nombre</label>
		<input value="<?php echo $result['NOMBRE'] ?>" disabled="disabled"  class="form-control">
	</div>	
	<div class="form-group col-sm-2">
		<label>Procurador</label>
		<input value="<?php echo $result['USUSUARIO'] ?>" disabled="disabled" class="form-control">
	</div>
</div>

<hr/>
<h5>Cambio de Estado</h5>
<div class="row">
	<div class="form-group col-sm-2">
	<label>Estado</label>
		<select class="form-control" id="selectorestado" name="selectorestado">
			 <option value="0">----Seleccione----</option>
			 <?php 
			 while($resultEstado=mysql_fetch_array($datos_estado['registros'])){ 
				 if ($resultEstado['NOMBRE_ESTADO'] != $result['NOMBRE_ESTADO']){
					echo "<option value='".$resultEstado['ID_ESTADO']."' >".$resultEstado['NOMBRE_ESTADO']."</option>";
				 }				
			 } 
			 ?>
		</select>
	</div>
	<div class="form-group col-sm-4">
		<label >Observacion</label>
		<input class="form-control" id="observacion" name="observacion"> 
	</div>
</div>
<div class="row">
	<div class="form-group col-sm-1">
		<button type="button" class="btn btn-rounded btn-primary form-control" onClick="GrabarModificacionEstado();">Guardar</button>
	</div>
</div>
<hr/>
<h5>Historial de Estados</h5>
<?php 
	$table = "<div class='card-box table-responsive'><div><table id='tableHistorialEstados' class='table table-striped table-bordered' width='100%'>";
	$table .= "<thead>";
	$table .= "<tr align='center' class='info text-center text-default' style='vertical-align:middle'>";
	$table .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Estado</th>";
	$table .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Fecha</th>";
	$table .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Observacion</th>";
	$table .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Usuario</th>";
	$table .= "</tr>";
	$table .= "</thead>";
	$table .= "<tbody>";
	
	while($result=mysql_fetch_array($datos_historia['registros'])){ 
		$table .= "<tr class='text-center text-muted' data-placement='top'>";
		$table .= "<td class='text-center' style='vertical-align:middle'>".$result['NOMBRE_ESTADO']."</td>";
		$table .= "<td class='text-center' style='vertical-align:middle'>".$result['fecha_estado']."</td>";
		$table .= "<td class='text-center' style='vertical-align:middle'>".$result['observacion']."</td>";
		$table .= "<td class='text-center' style='vertical-align:middle'>".$result['usuario']."</td>";
		$table .= "</tr>";		
	} 
	$table .= "</tbody>";
	$table .= "</table></div></div>";	
	echo $table;
?>
<?php
break;

case 7: //Actualizacion de estado

$selector_estado=$_GET["selector_estado"];
$observacion=$_GET["observacion"];
$id_custodia=$_GET["id_custodia"];
$fecha_actual=fecha_actual();

$sql_consulta=$var_update."registros_custodia ".$var_set." ID_ESTADO=".$selector_estado." ".$var_where." ID='".$id_custodia."'";
call_update($sql_consulta);

$sql_insert=$var_insert_into." pagare_historial_custodia (id_registros_custodia, id_estado_custodia, fecha_estado, observacion, usuario) "
.$var_values."(".$id_custodia.",".$selector_estado.",'".$fecha_actual."','".$observacion."','".$_SESSION['username']."')";
call_insert($sql_insert, "");

break;	

case 8: //Actualizacion de estado

$ids=$_GET["ids"];
$ids = explode(";", $ids);			
$fecha_actual=fecha_actual();

foreach	($ids as $item){
	echo $item;
	if ($item != ""){
		$sql_consulta=$var_update." registros_custodia ".$var_set." ID_ESTADO=2 ".$var_where." ID=".$item.";";
		echo $sql_consulta;
		call_update($sql_consulta);

		$sql_insert=$var_insert_into." pagare_historial_custodia (id_registros_custodia, id_estado_custodia, fecha_estado, observacion, usuario) "
		.$var_values."(".$item.",2,'".$fecha_actual."','".$observacion."','".$_SESSION['username']."')";
		call_insert($sql_insert, "");
	}				
}

break;

}

?>