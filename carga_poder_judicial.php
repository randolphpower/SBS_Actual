<?php 
	session_start();
	require 'includes/header_start.php'; 
	require 'includes/header_end.php'; 
	require 'mail.php'; 
	require_once("/modelo/consultaSQL.php");
	require_once("/modelo/conectarBD.php");
	require_once("/PHPExcel.php");
	require_once("controlador/script_general.php");
	$tipo = $_FILES['archivo']['type'];
	$tamanio = $_FILES['archivo']['size'];
	$tmpfname = $_FILES['archivo']['tmp_name'];
	$cargaDuplicados = $_GET['cargaDuplicados'];
	$itemsACargar = $_GET['itemsACargar'];

	if ($cargaDuplicados == "SI"){		
		$itemsACargar = explode("*", $itemsACargar);
		$insertados = 0;
		foreach	($itemsACargar as $item){
			$item = explode(";", $item);			
			if ($item[0] != ""){
				$arrayInsertados[$insertados] = array($item[0],$item[1],$item[2],$item[3],$item[4],$item[5],$item[6],$item[7]);
				$insertados++;
			}				
		}
		$tableInsertados = "<table class='table table-striped table-bordered table-hover'>";
		$tableInsertados .= "<thead>";
		$tableInsertados .= "<tr align='center' class='info text-center text-default' style='vertical-align:middle'>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Nro.</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Identificador</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rut Cliente</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Nombre Cliente</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tipo Juicio</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tribunal</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rol</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Fecha Inicio</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Fecha Demanda</th>";
		$tableInsertados .= "</tr>";
		$tableInsertados .= "</thead>";
		$tableInsertados .= "<tbody>";
	
		if (count($arrayInsertados) > 0) {
			array_multisort( array_column($arrayInsertados, 0), SORT_ASC, $arrayInsertados );	
			$fecha_actual=fecha_actual();					
			for ($j = 0; $j < $insertados; $j++) {						
				insertarJuicios($arrayInsertados[$j], $fecha_actual);
				$tableInsertados .= "<tr class='text-center text-muted' data-placement='top'>";
				$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".($j+1)."</td>";
				$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][0]."</td>";
				$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][1]."</td>";
				$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][2]."</td>";
				$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][3]."</td>";
				$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][4]."</td>";
				$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][5]."</td>";
				$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][6]."</td>";
				$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][7]."</td>";
				$tableInsertados .= "</tr>";			
			}   
		}
		$tableInsertados .= "</tbody>";
		$tableInsertados .= "</table>";	
	}
	else
	{
		if ($tmpfname != ""){
			$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
			$excelObj = $excelReader->load($tmpfname);
			$sheetCount = $excelObj->getSheetCount();
			$i = 0;		
			$insertados = 0;
			$duplicados = 0;
			$noEncontrados = 0;
			
			$worksheet = $excelObj->getSheet(0);
			$lastRow = $worksheet->getHighestRow();
			$rut_demandado = "";
			$nombre_demandado = "";
	
			for ($row = 2; $row <= $lastRow; $row++) {
		
					/*$tipo_ligitante = $worksheet->getCell('I'.$row)->getValue();
					if ($tipo_ligitante == "Demandado") {
						$rut_demandado = $worksheet->getCell('J'.$row)->getValue();
						$nombre_demandado = $worksheet->getCell('K'.$row)->getValue();
					}
					else {
						$tipo_ligitante = $worksheet->getCell('L'.$row)->getValue();
						if ($tipo_ligitante == "Demandado") {
							$rut_demandado = $worksheet->getCell('M'.$row)->getValue();
							$nombre_demandado = $worksheet->getCell('N'.$row)->getValue();
						}
						else {
							$tipo_ligitante = $worksheet->getCell('O'.$row)->getValue();
							if ($tipo_ligitante == "Demandado") {
								$rut_demandado = $worksheet->getCell('P'.$row)->getValue();
								$nombre_demandado = $worksheet->getCell('Q'.$row)->getValue();
							}
							else {
								$tipo_ligitante = $worksheet->getCell('R'.$row)->getValue();
								if ($tipo_ligitante == "Demandado") {
									$rut_demandado = $worksheet->getCell('S'.$row)->getValue();
									$nombre_demandado = $worksheet->getCell('T'.$row)->getValue();
								}
							}
						}
					}*/
					$rut_demandado = $worksheet->getCell('B'.$row)->getValue();
					$cuenta = $worksheet->getCell('A'.$row)->getValue();
					$nombre_demandado = "";
					//$rut_demandado = substr($rut_demandado,0,strpos($rut_demandado, '-'));
	
					$sql_search = "SELECT * FROM juicios_dato_inicial WHERE rut = '".$rut_demandado."' 
								   AND cuenta = '".$cuenta."';";
					$datos = call_select($sql_search, "");	
					//echo $sql_search;
					$tribunal = $worksheet->getCell('C'.$row)->getValue();
					$sql_search = "SELECT * FROM tribunales WHERE descripcion LIKE '%".$tribunal."%';";
					$datosTribunal = call_select($sql_search, "");
					$resultTribunal = mysql_fetch_array($datosTribunal['registros']);
					$fechaDemanda = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('F'.$row)->getValue()));
					$fechaDemanda = date('Y-m-d', strtotime($fechaDemanda. ' + 1 days'));
					$fechaDemanda = split("-", $fechaDemanda);
					$fechaDemanda = "{$fechaDemanda[2]}/{$fechaDemanda[1]}/{$fechaDemanda[0]}";
					$procurador = $worksheet->getCell('E'.$row)->getValue();
					$sql_search = "SELECT * FROM usuarios WHERE codigo_servicobranza = '".$procurador."';";
					$datosProc = call_select($sql_search, "");
					$resultProc=mysql_fetch_array($datosProc['registros']);
					$procurador = $resultProc['US_USUARIO'];
					if ($datos['num_filas'] ==1) { 
						$result=mysql_fetch_array($datos['registros']);
						$rol =  $worksheet->getCell('D'.$row)->getValue();
						/*$rolPos1 = substr($rol, 0, 1);
						$rolPos3 = substr($rol, -4);
						$rol = str_replace($rolPos1,$rolPos1."-",$rol);
						$rol = str_replace($rolPos3,"-".$rolPos3,$rol);*/
						$fechaAsignacion = strtotime($result['fecha_asignacion']);
						$fechaAsignacion = date("d/m/Y", $fechaAsignacion);
						//$fechaDemanda = date("d/m/Y", $fechaDemanda);
						$arrayInsertados[$insertados-1] = 
							array($result['id_juicio'], 
								  $rut_demandado, 
								  $result['nombre'],
								  $result['tipo_juicio'], 
								  $resultTribunal['codigo'],
								  $rol,
								  $fechaAsignacion,
								  $fechaDemanda,
								  $procurador);
						$insertados++;
					}	
					else  { 
						$result=mysql_fetch_array($datos['registros']);
						$rol =  $worksheet->getCell('B'.$row)->getValue();
						$rolPos1 = substr($rol, 0, 1);
						$rolPos3 = substr($rol, -4);
						$rol = str_replace($rolPos1,$rolPos1."-",$rol);
						$rol = str_replace($rolPos3,"-".$rolPos3,$rol);
						$fechaAsignacion = strtotime($result['fecha_asignacion']);
						$fechaAsignacion = date("d/m/Y", $fechaAsignacion);
						$arrayNoEncontrados[$noEncontrados-1] = 
							array($result['id_juicio'], 
								  $rut_demandado, 
								  $nombre_demandado,
								  $result['tipo_juicio'], 
								  $resultTribunal['codigo'],
								  $rol,
								  $fechaAsignacion,
								  $fechaDemanda,
								  $procurador);
						$noEncontrados++;					
					}							
				$i++;
			}			
		
			$tableInsertados = "<table class='table table-striped table-bordered table-hover'>";
			$tableInsertados .= "<thead>";
			$tableInsertados .= "<tr align='center' class='info text-center text-default' style='vertical-align:middle'>";
			$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Nro.</th>";
			$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Identificador</th>";
			$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rut Cliente</th>";
			$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Nombre Cliente</th>";
			$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tipo Juicio</th>";
			$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tribunal</th>";
			$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rol</th>";
			$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Fecha Asignacion</th>";
			$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Fecha Demanda</th>";
			$tableInsertados .= "</tr>";
			$tableInsertados .= "</thead>";
			$tableInsertados .= "<tbody>";
			$countMail = 0;
			if (count($arrayInsertados) > 0) {
				array_multisort( array_column($arrayInsertados, 0), SORT_ASC, $arrayInsertados );			
				$fecha_actual=fecha_actual();
				for ($j = 0; $j < $insertados; $j++) {								
					insertarJuicios($arrayInsertados[$j], $fecha_actual);
					$tableInsertados .= "<tr class='text-center text-muted' data-placement='top'>";
					$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".($j+1)."</td>";
					$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][0]."</td>";
					$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][1]."</td>";
					$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][2]."</td>";
					$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][3]."</td>";
					$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][4]."</td>";
					$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][5]."</td>";
					$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][6]."</td>";
					$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][7]."</td>";
					$tableInsertados .= "</tr>";			
				}   
				for ($j = 0; $j < count($datoPagare); $j++) {
					//enviarMail("rpower@servicobranza.cl", $arrayMail[$j][0]);
				}
			}
			$tableInsertados .= "</tbody>";
			$tableInsertados .= "</table>";	
	
			$tableDuplicados = "<table class='table table-striped table-bordered table-hover' id='tableDuplicados'>";
			$tableDuplicados .= "<thead>";
			$tableDuplicados .= "<tr align='center' class='info text-center text-default' style='vertical-align:middle'>";
			$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'></th>";
			$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Identificador</th>";
			$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rut Cliente</th>";
			$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Nombre Cliente</th>";
			$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tipo Juicio</th>";
			$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tribunal</th>";
			$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rol</th>";
			$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Fecha Inicio</th>";
			$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Fecha Demanda</th>";
			$tableDuplicados .= "</tr>";
			$tableDuplicados .= "</thead>";
			$tableDuplicados .= "<tbody>";
		
			if (count($arrayDuplicados) > 0) {
				array_multisort(array_column($arrayDuplicados, 1), SORT_ASC, $arrayDuplicados );			
				for ($k = 0; $k < $duplicados; $k++) {
					$tableDuplicados .= "<tr class='text-center text-muted' data-placement='top'>";
					$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'><input type='checkbox' name'check'></td>";
					$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][0]."</td>";
					$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][1]."</td>";
					$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][2]."</td>";
					$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][3]."</td>";
					$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][4]."</td>";
					$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][5]."</td>";
					$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][6]."</td>";
					$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][7]."</td>";
					$tableDuplicados .= "</tr>";			
				}   
			}
			$tableDuplicados .= "</tbody>";
			$tableDuplicados .= "</table>";	
	
			$tableNoEncontrados = "<table class='table table-striped table-bordered table-hover' id='tableNoEncontrados'>";
			$tableNoEncontrados .= "<thead>";
			$tableNoEncontrados .= "<tr align='center' class='info text-center text-default' style='vertical-align:middle'>";
			$tableNoEncontrados .= "<th class='col-md-1 text-center' style='vertical-align:middle'></th>";
			$tableNoEncontrados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Identificador</th>";
			$tableNoEncontrados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rut Cliente</th>";
			$tableNoEncontrados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Nombre Cliente</th>";
			$tableNoEncontrados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tipo Juicio</th>";
			$tableNoEncontrados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tribunal</th>";
			$tableNoEncontrados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rol</th>";
			$tableNoEncontrados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Fecha Inicio</th>";
			$tableNoEncontrados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Fecha Demanda</th>";
			$tableNoEncontrados .= "</tr>";
			$tableNoEncontrados .= "</thead>";
			$tableNoEncontrados .= "<tbody>";
		
			if (count($arrayNoEncontrados) > 0) {
				array_multisort(array_column($arrayNoEncontrados, 1), SORT_ASC, $arrayNoEncontrados);		
				for ($k = 0; $k < $noEncontrados; $k++) {
					$tableNoEncontrados .= "<tr class='text-center text-muted' data-placement='top'>";
					$tableNoEncontrados .= "<td class='text-center' style='vertical-align:middle'><input type='checkbox' name'check'></td>";
					$tableNoEncontrados .= "<td class='text-center' style='vertical-align:middle'>".$arrayNoEncontrados[$k][0]."</td>";
					$tableNoEncontrados .= "<td class='text-center' style='vertical-align:middle'>".$arrayNoEncontrados[$k][1]."</td>";
					$tableNoEncontrados .= "<td class='text-center' style='vertical-align:middle'>".$arrayNoEncontrados[$k][2]."</td>";
					$tableNoEncontrados .= "<td class='text-center' style='vertical-align:middle'>".$arrayNoEncontrados[$k][3]."</td>";
					$tableNoEncontrados .= "<td class='text-center' style='vertical-align:middle'>".$arrayNoEncontrados[$k][4]."</td>";
					$tableNoEncontrados .= "<td class='text-center' style='vertical-align:middle'>".$arrayNoEncontrados[$k][5]."</td>";
					$tableNoEncontrados .= "<td class='text-center' style='vertical-align:middle'>".$arrayNoEncontrados[$k][6]."</td>";
					$tableNoEncontrados .= "<td class='text-center' style='vertical-align:middle'>".$arrayNoEncontrados[$k][7]."</td>";
					$tableNoEncontrados .= "</tr>";			
				}   
			} 
			$tableNoEncontrados .= "</tbody>";
			$tableNoEncontrados .= "</table>";	
		}	
	}
	

	function insertarJuicios($arrayInsertados, $fecha_actual){
		$numjuicio = $arrayInsertados[0];		
		$rutcliente = $arrayInsertados[1];
		$nombre = $arrayInsertados[2];
		$tipojuicio = $arrayInsertados[3];
		$tribunal = $arrayInsertados[4];
		$rol = $arrayInsertados[5];
		$fecha_inicio = $arrayInsertados[6];
		$fecha_inicio = split("/", $fecha_inicio);
		$fecha_inicio = "{$fecha_inicio[2]}-{$fecha_inicio[1]}-{$fecha_inicio[0]}";
		$fecha_demanda= $arrayInsertados[7];
		$fecha_demanda = split("/", $fecha_demanda);
		$fecha_demanda = "{$fecha_demanda[2]}-{$fecha_demanda[1]}-{$fecha_demanda[0]}";
		$procurador = $arrayInsertados[8];
		$nro_pagare = "";
		$sql_searh = "SELECT * FROM relacion_cliente_juicio WHERE NUM_JUICIO = ".$numjuicio." and ID_CLIENTE = '".$rutcliente."';";		
		$num = call_select2($sql_searh);

		if ($num == 0) { // INSERT			
			//  relacion_info_juicio
			$sql = "INSERT INTO relacion_cliente_juicio (NUM_JUICIO, ID_CLIENTE, CECRTID, CEDOSSIERID, CETYPE, nombre, TEMP_FECHA_INICIO, TEMP_FECHA_DEM) ";
			$sql .= "VALUES (".$numjuicio.",".$rutcliente.",'".$tribunal."','".$rol."','".$tipojuicio."','".$nombre."', '{$fecha_inicio}', '{$fecha_demanda}');";
			call_insert2($sql, "");

			// op_info_juicios en el caso que no exista en la BD
			$sql = "INSERT INTO op_info_juicios (IDENTIFICADOR, CEDOSSIERID, CNCASENO, CESSNUM, CECRTID, CETYPE, USUSUARIO, CELASTRC, CELASTAC, CELWSTDT) VALUES ";
			$sql .= "('902','".$rol."','".$numjuicio."','".$rutcliente."','".$tribunal."','".$tipojuicio."','".$procurador."', 'MA', 'IJ', '{$fecha_demanda}')";
			call_insert2($sql, "");

		} else if ($num == 1) { // UPDATE
			$sql = "UPDATE relacion_cliente_juicio SET ";
			$sql .= "CECRTID='".$tribunal."', ";
			$sql .= "CETYPE='".$tipojuicio."', ";
			$sql .= "nombre='".$nombre."', ";
			$sql .= "CEDOSSIERID='".$rol."', ";
			$sql .= "TEMP_FECHA_INICIO = '{$fecha_inicio}', ";
			$sql .= "TEMP_FECHA_DEM = '{$fecha_demanda}' ";
			$sql .= "WHERE (NUM_JUICIO='".$numjuicio."') AND (ID_CLIENTE='".$rutcliente."') ";
			call_update2($sql);

			// Actualizado en op_info_juicios en el caso que exista en la tabla "relacion_info_juicio" se comprueba que exista en "op_info_juicios"
			$sql = "SELECT * FROM op_info_juicios WHERE CNCASENO=".$numjuicio."  and CESSNUM=".$rutcliente.";";
			$num = call_select2($sql);

			if ($num > 0) {
				$sql = "UPDATE op_info_juicios SET ";
				$sql .= "CECRTID='".$tribunal."', ";
				$sql .= "CETYPE='".$tipojuicio."', ";				
				$sql .= "CEDOSSIERID='".$rol."', ";
				$sql .= "CELWSTDT = '{$fecha_demanda}', ";
				$sql .= "USUSUARIO = '{$procurador}' ";
				$sql .= "WHERE (CNCASENO='".$numjuicio."' AND CESSNUM='".$rutcliente."') ";
				call_update2($sql);
			}	
			
		}	
	}

	function call_insert2($insert_sql, $parametro_condicional){
		include("modelo/conectarBD.php");
		global $var_retorno_datos;
		mysql_query($insert_sql,$conexion) or die(mysql_error());
	
		if($parametro_condicional!=""){
			$parametro_sql=mysql_query($parametro_condicional,$conexion) or die(mysql_error());
			$var_retorno_datos = array('ultimo_id' => $parametro_sql,
								 'por_asignar1' => '',
								 'por_asignar2' => '',
								 'por_asignar3' => '',
								 'por_asignar4' => '');
		}
	
		mysql_close($conexion);
		return $var_retorno_datos;
	
	}
	
	function call_select2($select_sql){
		include("modelo/conectarBD.php");
		$result=mysql_query($select_sql,$conexion) or die(mysql_error());
		$numresult=mysql_num_rows($result);
		mysql_close($conexion);
		return $numresult;
	
	}
	
	function call_update2($update_sql){
	
		include("modelo/conectarBD.php");
		global $var_retorno_datos;
		$nro_filas_afectadas = 0;
	
		$parametro_sql=mysql_query($update_sql,$conexion) or die(mysql_error());
		$nro_filas_afectadas = mysql_affected_rows($conexion);
	
		$var_retorno_datos = "";
	
		mysql_close($conexion);
		return $var_retorno_datos;
	
	}//Fin funcion update
?>
<style>
   #page-loader {
   position: absolute;
   top: 0;
   bottom: 0%;
   left: 0;
   right: 0%;
   background-color: white;
   z-index: 99;
   display: none;
   text-align: center;
   width: 100%;
   padding-top: 25px;
   }
</style>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<form role="form" name="form" action="./carga_poder_judicial.php" enctype="multipart/form-data" method="post" id="form1">

<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h3 class="page-title">Carga Poder Judicial</h3>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        	<div class="row">
            	<div class="col-sm-12">
					<div class="card">
						<div class="card-header">Informacion de Archivo</div>						
							<div class="card-block">
							<div id="page-loader">
								<h3>Cargando Demandas...</h3>
								<img src="./images/gif-load.gif" alt="loader">
								<h3>...por favor espere</h3>
							</div>
								<div class="row">
									<div class="form-group col-sm-6">
									<label>Ejemplo de Archivo de Demandas</label>
										<a href="ejemplo_archivo_demandas.xlsx">DESCARGAR</a>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-6">
											<label>Archivo</label><br>
											<input type="file" id="archivo" name="archivo" accept=".xlsx">
									</div>

								</div>
								<div class="row">
									<div class="form-group col-sm-6">
											<button type="submit" class="btn btn-rounded btn-primary" id="subir">Subir</button>
											<button type="button" class="btn btn-rounded btn-danger" onClick="location='principal.php'">Volver</button>
									</div>
								</div>
							</div>
						</div>
        			</div>
				</div>				
				<?php if ($insertados > 0) { ?>
				<div class="row">
            		<div class="col-sm-12">
						<div class="card">							
							<div class="card-header">
								<a data-toggle="collapse" href="#collapse-insertados" aria-expanded="true" aria-controls="collapse-insertados" id="heading-insertados" class="d-block">
            					<i class="fa fa-chevron-down pull-right"></i>
            						Datos Insertados
        						</a>
							</div>
							<div id="collapse-insertados" class="collapse show" aria-labelledby="heading-insertados">
        						<div class="card-block">
									<?php echo $tableInsertados ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if ($duplicados > 0) { ?>
				<div class="row">
            		<div class="col-sm-12">
						<div class="card">							
							<div class="card-header">
								<a data-toggle="collapse" href="#collapse-duplicados" aria-expanded="true" aria-controls="collapse-duplicados" id="heading-duplicados" class="d-block">
            					<i class="fa fa-chevron-down pull-right"></i>
            						Datos Duplicados
        						</a>
							</div>
							<div id="collapse-duplicados" class="collapse show" aria-labelledby="heading-duplicados">
        						<div class="card-block">
									<?php echo $tableDuplicados ?>
								</div>
								<div class="card-block">
									<button type="button" class="btn btn-rounded btn-danger" onClick="GetSelected()">Cargar Duplicados</button>
								</div>
							</div>
						</div>
					</div>
				</div>				
				<?php } ?>
				<?php if ($noEncontrados > 0) { ?>
				<div class="row">
            		<div class="col-sm-12">
						<div class="card">							
							<div class="card-header">
								<a data-toggle="collapse" href="#collapse-noEncontrados" aria-expanded="true" aria-controls="collapse-noEncontrados" id="heading-noEncontrados" class="d-block">
            					<i class="fa fa-chevron-down pull-right"></i>
            						Datos No Encontrados
        						</a>
							</div>
							<div id="collapse-noEncontrados" class="collapse show" aria-labelledby="heading-noEncontrados">
        						<div class="card-block">
									<?php echo $tableNoEncontrados ?>
								</div>
							</div>
						</div>
					</div>
				</div>				
				<?php } ?>
			</div>
			<!-- end row -->
        </div> <!-- container -->
    </div> <!-- content -->
</div>
<!-- End content-page -->
</form>

<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php require 'includes/footer_start.php' ?>
<!-- extra js -->
<?php require 'includes/footer_end.php' ?>

<script type="text/javascript">

	$(document).ready(function () {
		$('#descargarDatosIniciales').click(function(e) {
			e.preventDefault();  
			window.location.href = './descargar_datos_iniciales.php';
		});
		$('#subir').click(function(e) {
   			if (document.form.archivo.value == "") {
   			alert('Debe seleccionar un archivo');
   			return false;
   		}
   		document.getElementById('page-loader').style.display='block';
   	});
	});
	function GetSelected() {
        var grid = document.getElementById("tableDuplicados");
        var checkBoxes = grid.getElementsByTagName("INPUT");
        var item = "";

        for (var i = 0; i < checkBoxes.length; i++) {
            if (checkBoxes[i].checked) {
                var row = checkBoxes[i].parentNode.parentNode;
                item += row.cells[1].innerHTML + ";";
                item += row.cells[2].innerHTML + ";";
                item += row.cells[3].innerHTML + ";";
				item += row.cells[4].innerHTML + ";";
				item += row.cells[5].innerHTML + ";";
				item += row.cells[6].innerHTML + ";";
				item += row.cells[7].innerHTML + ";";
				item += row.cells[8].innerHTML + ";";
                item += "*";
            }
        }
		window.location.href = './carga_poder_judicial.php?cargaDuplicados=SI&itemsACargar='+encodeURIComponent(item);
    }
</script>