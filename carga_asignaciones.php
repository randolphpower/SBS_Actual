<?php 
	session_start();
	require 'includes/header_start.php'; 
	require 'includes/header_end.php'; 
	require_once("/modelo/consultaSQL.php");
	require_once("/modelo/conectarBD.php");
	require_once("/PHPExcel.php");
	$tipo = $_FILES['archivo']['type'];
	$tamanio = $_FILES['archivo']['size'];
	$tmpfname = $_FILES['archivo']['tmp_name'];
	$fechaAsignacion = $_POST["fechaAsignacion"];
	$fechaAsignacion = split("/", $fechaAsignacion);
	$fechaAsignacion = "{$fechaAsignacion[2]}-{$fechaAsignacion[1]}-{$fechaAsignacion[0]}";
	if ($tmpfname != ""){
		$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
		$excelObj = $excelReader->load($tmpfname);
		$sheetCount = $excelObj->getSheetCount();
		$i = 0;
		$instertados = 0;
		$actualizados = 0;
		for ($sheetRow = 0; $sheetRow <= $sheetCount-1; $sheetRow++) {				
			$worksheet = $excelObj->getSheet($sheetRow);
			$lastRow = $worksheet->getHighestRow();
			
			$tituloJuicio = $worksheet->getCell('A1')->getValue();
			$tituloRut = $worksheet->getCell('C1')->getValue();
			$tituloTipoJuicio = $worksheet->getCell('AA1')->getValue();
			if ($tituloJuicio != "ID JUICIO"){
				$error = "La columna ID JUICIO no esta en la posición correcta";
				break;
			} else if ($tituloRut != "Rut_Deudor"){
				$error = "La columna Rut_Deudor no esta en la posición correcta";
				break;
			} else if ($tituloTipoJuicio != "TIPO JUICIO"){
				$error = "La columna TIPO JUICIO no esta en la posición correcta";
				break;
			}
			else {
				echo "itera</br>";
				for ($row = 2; $row <= $lastRow; $row++) {
				
					$id_juicio = $worksheet->getCell('A'.$row)->getValue();
					$tipo_juicio = $worksheet->getCell('AA'.$row)->getValue();
					$rut = substr($worksheet->getCell('C'.$row)->getValue(),0,strpos($worksheet->getCell('C'.$row)->getValue(), '-'));
					
					if ($tipo_juicio != "") {
						$sql_search = "SELECT id FROM juicios_dato_inicial WHERE id_juicio = ".$id_juicio." and tipo_juicio = '".$tipo_juicio."';";				
						$datos = call_select($sql_search, "");			
						$id_tabla = mysql_fetch_array($datos['registros'])['id'];
						//echo $id_tabla."</br>";
						if ($datos['num_filas'] == 0) { // INSERT
							$sql = "INSERT INTO juicios_dato_inicial (id_juicio, tipo_juicio, rut, fecha_asignacion)  ";
							$sql .= "VALUES (".$id_juicio.",'".$tipo_juicio."','".$rut."','".$fechaAsignacion."');";
							call_insert($sql, "");
		
							$arrnumjuicio[$i-1] = $id_juicio;
							$arrrutcliente[$i-1] = $rut;
							$arrtipojuicio[$i-1] = $tipo_juicio;
							$arraccion[$i-1] = "INSERT";	
							$instertados++;
						} else { // UPDATE
							$sql = "UPDATE juicios_dato_inicial SET ";
							$sql .= "tipo_juicio='".$tipo_juicio."', ";				
							$sql .= "rut='".$rut."', ";				
							$sql .= "fecha_asignacion='".$fechaAsignacion."' ";						
							$sql .= "WHERE (id='".$id_tabla."') ";
							call_update($sql);
		
							$arrnumjuicio[$i-1] = $id_juicio;
							$arrrutcliente[$i-1] = $rut;
							$arrtipojuicio[$i-1] = $tipo_juicio;
							$arraccion[$i-1] = "UPDATE";	
							$actualizados++;
						 }
					}	
					$i++;		
				}		
			}
				
		}	
	
		$print = "<table class='table table-striped table-bordered table-hover'>";
		$print .= "<thead>";
		$print .= "<tr align='center' class='info text-center text-default' style='vertical-align:middle'>";
		$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Nro.</th>";
		$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Identificador</th>";
		$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rut Cliente</th>";
		$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tipo Juicio</th>";
		$print .= "</tr>";
		$print .= "</thead>";
		$print .= "<tbody>";
	
		$j=1;
		$count = count($arrnumjuicio);
		echo $count;
		for ($i = 0; $i < $count; $i++) {
			if ($arraccion[$i-1] == "INSERT"){
				$color = "#C4FBB5";
			}
			else if ($arraccion[$i-1] == "UPDATE"){
				$color = "#F8E4BB";
			}
			$print .= "<tr class='text-center text-muted' data-placement='top' style='background-color:".$color."'>";
			$print .= "<td class='text-center' style='vertical-align:middle'>".$j."</td>";
			$print .= "<td class='text-center' style='vertical-align:middle'>".$arrnumjuicio[$i-1]."</td>";
			$print .= "<td class='text-center' style='vertical-align:middle'>".$arrrutcliente[$i-1]."</td>";
			$print .= "<td class='text-center' style='vertical-align:middle'>".$arrtipojuicio[$i-1]."</td>";
			$print .= "</tr>";
			$j=$j+1;
		}   
		$print .= "</tbody>";
		$print .= "</table>";	
	}	
?>


<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<form role="form" name="form" action="./carga_asignaciones.php" enctype="multipart/form-data" method="post" id="form1">

<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h3 class="page-title">Carga Asignaciones</h3>
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
									<div class="form-group col-sm-2" col>
											<label>Fecha</label>
											<div class="input-group">
												<input type="text" class="form-control" name="fechaAsignacion" id="fechaAsignacion" placeholder="dd/mm/aaaa"><span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
											</div>
									</div>
								</div>						
								<div class="row">
									<div class="form-group col-sm-6">
											<button type="submit" class="btn btn-rounded btn-primary" id="subir">Subir</button>
											<button type="button" class="btn btn-rounded btn-danger" onClick="location='principal.php'">Volver</button>
											<button type="button" class="btn btn-rounded btn-primary" id="descargarDatosIniciales">Descargar Datos Iniciales</button>
									</div>
								</div>
								<div id="guardardo_info"></div>
							</div>
						</div>
        			</div>
				</div>
				<?php if ($error != "") { ?>
				<div class="row">
					<div class="alert alert-danger alert-dismissible" role="alert" align="center">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<strong>¡ATENCION!</strong> <?php echo $error ?>
					</div>
				</div>
				<?php } ?>
				<?php if ($instertados > 0 || $actualizados > 0) { ?>
				<div class="row">
            		<div class="col-sm-12">
						<div class="card">
							<div class="card-header"><?php echo "Datos Cargados (<span style='color:#C4FBB5;font-weight:bold'>Insertados: ".$instertados."</span>, <span style='color:#F8E4BB;font-weight:bold'>Actualizados: ".$actualizados."</span>)"; ?>
							</div>
							<div class="card-block">
							<?php echo $print ?>
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

<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js"></script>
<script type="text/javascript">
	
	$(document).ready(function () {
		$("#fechaAsignacion").datepicker({ weekStart: 1, changeMonth: true, changeYear: true, dateFormat: 'dd/mm/yy', autoclose: true, language:'es' });
		$('#subir').click(function(e) {
			if (document.form.archivo.value == "") {
				alert('Debe seleccionar un archivo');
				return false;
			}
			if (document.form.fechaAsignacion.value == "") {
				alert('Debe seleccionar una fecha de asignacion');
				return false;
			}
		});
		$('#descargarDatosIniciales').click(function(e) {
			e.preventDefault();  //stop the browser from following			
			window.location.href = './descargar_datos_iniciales.php';
		});
	});

</script>