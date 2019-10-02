<?php 
	session_start();
	//require 'includes/header_start.php'; 
	//require 'includes/header_end.php'; 
	require_once("/modelo/consultaSQL.php");
	require_once("/modelo/conectarBD.php");
	require_once("/PHPExcel.php");
	$tipo = $_FILES['archivo']['type'];
	$tamanio = $_FILES['archivo']['size'];
	$tmpfname = $_FILES['archivo']['tmp_name'];
	if ($tmpfname != ""){
		$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
		$excelObj = $excelReader->load($tmpfname);
		$sheetCount = $excelObj->getSheetCount();
		$i = 0;		
		$insertados = 0;
		$duplicados = 0;
		$noEncontrados = 0;
		
		$worksheet = $excelObj->getSheet(3);
		$lastRow = $worksheet->getHighestRow();
		$rut_demandado = "";

		for ($row = 5; $row <= $lastRow; $row++) {

			if (strpos($worksheet->getCell('C'.$row)->getValue(), 'ANDES') !== false) {
					
				$tipo_ligitante = $worksheet->getCell('I'.$row)->getValue();
				if ($tipo_ligitante == "Demandado") {
					$rut_demandado = $worksheet->getCell('J'.$row)->getValue();
				}
				else {
					$tipo_ligitante = $worksheet->getCell('L'.$row)->getValue();
					if ($tipo_ligitante == "Demandado") {
						$rut_demandado = $worksheet->getCell('M'.$row)->getValue();
					}
					else {
						$tipo_ligitante = $worksheet->getCell('O'.$row)->getValue();
						if ($tipo_ligitante == "Demandado") {
							$rut_demandado = $worksheet->getCell('P'.$row)->getValue();
						}
						else {
							$tipo_ligitante = $worksheet->getCell('R'.$row)->getValue();
							if ($tipo_ligitante == "Demandado") {
								$rut_demandado = $worksheet->getCell('S'.$row)->getValue();
							}
						}
					}
				}

				$sql_search = "SELECT * FROM juicios_dato_inicial WHERE rut = '".$rut_demandado."';";
				$datos = call_select($sql_search, "");	

				$tribunal = $worksheet->getCell('A'.$row)->getValue();
				$sql_search = "SELECT * FROM tribunales WHERE descripcion LIKE '%".$tribunal."%';";
				$datosTribunal = call_select($sql_search, "");
				$resultTribunal = mysql_fetch_array($datosTribunal['registros']);
				
				if ($datos['num_filas'] > 1) { 
					while($result=mysql_fetch_array($datos['registros'])){
						$rol =  $worksheet->getCell('B'.$row)->getValue();
						$rolPos1 = substr($rol, 0, 1);
						$rolPos3 = substr($rol, -4);
						$rol = str_replace($rolPos1,$rolPos1."-",$rol);
						$rol = str_replace($rolPos3,"-".$rolPos3,$rol);
						$arrayDuplicados[$duplicados-1] = 
							array($result['id_juicio'], 
								  $rut_demandado, 
								  $result['tipo_juicio'], 
								  $resultTribunal['codigo'],
								  $rol,
								  "DUPLICADO");
						$duplicados++;		
					}					
				}	
				else if ($datos['num_filas'] ==1) { 
					$result=mysql_fetch_array($datos['registros']);
					$rol =  $worksheet->getCell('B'.$row)->getValue();
					$rolPos1 = substr($rol, 0, 1);
					$rolPos3 = substr($rol, -4);
					$rol = str_replace($rolPos1,$rolPos1."-",$rol);
					$rol = str_replace($rolPos3,"-".$rolPos3,$rol);
					$arrayInsertados[$insertados-1] = 
						array($result['id_juicio'], 
							  $rut_demandado, 
							  $result['tipo_juicio'], 
							  $resultTribunal['codigo'],
							  $rol,
							  "INSERTADO");
					$insertados++;
				}	
				else  { 
					$result=mysql_fetch_array($datos['registros']);
					$rol =  $worksheet->getCell('B'.$row)->getValue();
					$rolPos1 = substr($rol, 0, 1);
					$rolPos3 = substr($rol, -4);
					$rol = str_replace($rolPos1,$rolPos1."-",$rol);
					$rol = str_replace($rolPos3,"-".$rolPos3,$rol);
					$arrayNoEncontrados[$noEncontrados-1] = 
						array($result['id_juicio'], 
							  $rut_demandado, 
							  $result['tipo_juicio'], 
							  $resultTribunal['codigo'],
							  $rol,
							  "NO ENCONTRADO");
					$noEncontrados++;					
				}			
			}						
			$i++;
		}			
	
		$tableInsertados = "<table class='table table-striped table-bordered table-hover'>";
		$tableInsertados .= "<thead>";
		$tableInsertados .= "<tr align='center' class='info text-center text-default' style='vertical-align:middle'>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Nro.</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Identificador</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rut Cliente</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tipo Juicio</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tribunal</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rol</th>";
		$tableInsertados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Mensaje</th>";
		$tableInsertados .= "</tr>";
		$tableInsertados .= "</thead>";
		$tableInsertados .= "<tbody>";
	
		$count = count($insertados);

		array_multisort( array_column($arrayInsertados, 0), SORT_ASC, $arrayInsertados );
		
		for ($j = 0; $j < $insertados; $j++) {			
			insertarJuicios($arrayInsertados[$j]);
			$tableInsertados .= "<tr class='text-center text-muted' data-placement='top'>";
			$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".($j+1)."</td>";
			$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][0]."</td>";
			$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][1]."</td>";
			$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][2]."</td>";
			$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][3]."</td>";
			$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][4]."</td>";
			$tableInsertados .= "<td class='text-center' style='vertical-align:middle'>".$arrayInsertados[$j][5]."</td>";
			$tableInsertados .= "</tr>";			
		}   
		$tableInsertados .= "</tbody>";
		$tableInsertados .= "</table>";	

		$tableDuplicados = "<table class='table table-striped table-bordered table-hover' id='tableDuplicados'>";
		$tableDuplicados .= "<thead>";
		$tableDuplicados .= "<tr align='center' class='info text-center text-default' style='vertical-align:middle'>";
		$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'></th>";
		$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Identificador</th>";
		$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rut Cliente</th>";
		$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tipo Juicio</th>";
		$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tribunal</th>";
		$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rol</th>";
		$tableDuplicados .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Mensaje</th>";
		$tableDuplicados .= "</tr>";
		$tableDuplicados .= "</thead>";
		$tableDuplicados .= "<tbody>";
	
		$count = count($duplicados);

		array_multisort( array_column($arrayDuplicados, 1), SORT_ASC, $arrayDuplicados );
		
		for ($k = 0; $k < $duplicados; $k++) {
			$tableDuplicados .= "<tr class='text-center text-muted' data-placement='top'>";
			$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'><input type='checkbox' name'check'></td>";
			$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][0]."</td>";
			$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][1]."</td>";
			$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][2]."</td>";
			$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][3]."</td>";
			$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][4]."</td>";
			$tableDuplicados .= "<td class='text-center' style='vertical-align:middle'>".$arrayDuplicados[$k][5]."</td>";
			$tableDuplicados .= "</tr>";			
		}   
		$tableDuplicados .= "</tbody>";
		$tableDuplicados .= "</table>";	
	}	

	function insertarJuicios($arrayInsertados){
		$numjuicio = $arrayInsertados[0];
		$rutcliente = $arrayInsertados[1];
		$tipojuicio = $arrayInsertados[2];
		$tribunal = $arrayInsertados[3];
		$rol = $arrayInsertados[4];
		$nombre = "";
		$fecha_inicio = "";
		$fecha_demanda= "";
		$sql_searh = "SELECT * FROM relacion_cliente_juicio WHERE NUM_JUICIO = ".$numjuicio." and ID_CLIENTE = ".$rutcliente.";";
		$num = call_select2($sql_searh);

		if ($num == 0) { // INSERT

			//  relacion_info_juicio
			$sql = "INSERT INTO relacion_cliente_juicio (NUM_JUICIO, ID_CLIENTE, CECRTID, CEDOSSIERID, CETYPE, nombre, TEMP_FECHA_INICIO, TEMP_FECHA_DEM) ";
			$sql .= "VALUES (".$numjuicio.",".$rutcliente.",'".$tribunal."','".$rol."','".$tipojuicio."','".$nombre."', '{$fecha_inicio}', '{$fecha_demanda}');";
			call_insert2($sql, "");

			// op_info_juicios en el caso que no exista en la BD
			$sql = "INSERT INTO op_info_juicios (IDENTIFICADOR, CEDOSSIERID, CNCASENO, CESSNUM, CECRTID, CETYPE, USUSUARIO, CELASTRC, CELASTAC, CELWSTDT) VALUES ";
			$sql .= "('902','".$rol."','".$numjuicio."','".$rutcliente."','".$tribunal."','".$tipojuicio."','".$_SESSION['username']."', 'MA', 'IJ', '{$fecha_demanda}')";
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

			// Insercion en op_info_juicios en el caso que exista en la tabla "relacion_info_juicio" se comprueba que exista en "op_info_juicios"
			$sql = "SELECT * FROM op_info_juicios WHERE CNCASENO=".$numjuicio."  and CESSNUM=".$rutcliente.";";
			$num = call_select2($sql);

			if ($num == 0) {
				$sql = "INSERT INTO op_info_juicios (IDENTIFICADOR, CEDOSSIERID, CNCASENO, CESSNUM, CECRTID, CETYPE, USUSUARIO, CELASTRC, CELASTAC, CECOMM)";
				$sql .= "VALUES ('902','".$rol."','".$numjuicio."','".$rutcliente."','".$tribunal."','".$tipojuicio."','".$_SESSION['username']."','MA','IJ', '')";
				call_insert2($sql,"");
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
								<div class="row">
									<div class="form-group col-sm-6">
											<label>Archivo</label><br>
											<input type="file" id="archivo" name="archivo" accept=".xlsx">
									</div>

								</div>
								<div class="row">
									<div class="form-group col-sm-6">
											<button type="submit" class="btn btn-rounded btn-primary">Subir</button>
											<button type="button" class="btn btn-rounded btn-danger" onClick="location='principal.php'">Volver</button>
											<button type="button" class="btn btn-rounded btn-primary" id="descargarDatosIniciales">Descargar Datos Iniciales</button>
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
								<a data-toggle="collapse" href="#collapse-example" aria-expanded="true" aria-controls="collapse-example" id="heading-example" class="d-block">
            					<i class="fa fa-chevron-down pull-right"></i>
            						Datos Duplicados
        						</a>
							</div>
							<div id="collapse-example" class="collapse show" aria-labelledby="heading-example">
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
	});
	function GetSelected() {
        var grid = document.getElementById("tableDuplicados");
        var checkBoxes = grid.getElementsByTagName("INPUT");
        var message = "Id Name                  Country\n";

        for (var i = 0; i < checkBoxes.length; i++) {
            if (checkBoxes[i].checked) {
                var row = checkBoxes[i].parentNode.parentNode;
                message += row.cells[1].innerHTML;
                message += "   " + row.cells[2].innerHTML;
                message += "   " + row.cells[3].innerHTML;
                message += "\n";
            }
        }
        alert(message);
    }
</script>