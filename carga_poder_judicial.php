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
		$instertados = 0;
		$actualizados = 0;
		
		$worksheet = $excelObj->getSheet(3);
		$lastRow = $worksheet->getHighestRow();
		$rut_demandado = "";

		for ($row = 5; $row <= $lastRow; $row++) {

			if (strpos($worksheet->getCell('C'.$row)->getValue(), 'ANDES') !== false) {
					
				$tipo_ligitante = $worksheet->getCell('I'.$row)->getValue();
				//echo $tipo_ligitante."</br>";
				if ($tipo_ligitante == "Demandado") {
					$rut_demandado = $worksheet->getCell('J'.$row)->getValue();
					//echo $rut_demandado." IJ </br>";
				}
				else {
					$tipo_ligitante = $worksheet->getCell('L'.$row)->getValue();
					//echo $tipo_ligitante."</br>";
					if ($tipo_ligitante == "Demandado") {
						$rut_demandado = $worksheet->getCell('M'.$row)->getValue();
						//echo $rut_demandado." LM</br>";
					}
					else {
						$tipo_ligitante = $worksheet->getCell('O'.$row)->getValue();
						//echo $tipo_ligitante."</br>";
						if ($tipo_ligitante == "Demandado") {
							$rut_demandado = $worksheet->getCell('P'.$row)->getValue();
							//echo $rut_demandado." OP</br>";
						}
						else {
							$tipo_ligitante = $worksheet->getCell('R'.$row)->getValue();
							//echo $tipo_ligitante."</br>";
							if ($tipo_ligitante == "Demandado") {
								$rut_demandado = $worksheet->getCell('S'.$row)->getValue();
								//echo $rut_demandado." RS</br>";
							}
						}
					}
				}

				$sql_search = "SELECT * FROM juicios_dato_inicial WHERE rut = '".$rut_demandado."';";
				$datos = call_select($sql_search, "");		
				
				echo $datos['num_filas']."</br>";				
				if ($datos['num_filas'] > 1) { 
					while($result=mysql_fetch_array($datos['registros'])){
						$arrnumjuicio[$actualizados-1] = $result['id_juicio'];					
						$arrrutcliente[$actualizados-1] = $rut_demandado;					
						$arrtipojuicio[$actualizados-1] = $result['tipo_juicio'];					
						$arrRol[$actualizados-1] = $worksheet->getCell('B'.$row)->getValue();
						$arrJuzgado[$actualizados-1] = $worksheet->getCell('A'.$row)->getValue();
						$arraccion[$actualizados-1] = "DUPLICADO";	
						$actualizados++;					
					}					
				}	
				else if ($datos['num_filas'] ==1) { 
					$result=mysql_fetch_array($datos['registros']);
					$arrnumjuicio[$actualizados-1] = $result['id_juicio'];					
					$arrrutcliente[$actualizados-1] = $rut_demandado;					
					$arrtipojuicio[$actualizados-1] = $result['tipo_juicio'];					
					$arrRol[$actualizados-1] = $worksheet->getCell('B'.$row)->getValue();
					$arrJuzgado[$actualizados-1] = $worksheet->getCell('A'.$row)->getValue();
					$arraccion[$actualizados-1] = "ENCONTRADO";	
					$actualizados++;					
					
				}	
				else  { 
					$arrnumjuicio[$actualizados-1] = $result['id_juicio'];					
					$arrrutcliente[$actualizados-1] = $rut_demandado;					
					$arrtipojuicio[$actualizados-1] = $result['tipo_juicio'];					
					$arrRol[$actualizados-1] = $worksheet->getCell('B'.$row)->getValue();
					$arrJuzgado[$actualizados-1] = $worksheet->getCell('A'.$row)->getValue();
					$arraccion[$actualizados-1] = "NO ENCONTRADO";	
					$actualizados++;					
				}			
			}						
			$i++;
		}			
	
		$print = "<table class='table table-striped table-bordered table-hover'>";
		$print .= "<thead>";
		$print .= "<tr align='center' class='info text-center text-default' style='vertical-align:middle'>";
		$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Nro.</th>";
		$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Identificador</th>";
		$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rut Cliente</th>";
		$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tipo Juicio</th>";
		$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Juzgado</th>";
		$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rol</th>";
		$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Mensaje</th>";
		$print .= "</tr>";
		$print .= "</thead>";
		$print .= "<tbody>";
	
		$count = count($actualizados);
		
		for ($j = 0; $j < $actualizados; $j++) {
			$print .= "<tr class='text-center text-muted' data-placement='top'>";
			$print .= "<td class='text-center' style='vertical-align:middle'>".($j+1)."</td>";
			$print .= "<td class='text-center' style='vertical-align:middle'>".$arrnumjuicio[$j-1]."</td>";
			$print .= "<td class='text-center' style='vertical-align:middle'>".$arrrutcliente[$j-1]."</td>";
			$print .= "<td class='text-center' style='vertical-align:middle'>".$arrtipojuicio[$j-1]."</td>";
			$print .= "<td class='text-center' style='vertical-align:middle'>".$arrJuzgado[$j-1]."</td>";
			$print .= "<td class='text-center' style='vertical-align:middle'>".$arrRol[$j-1]."</td>";
			$print .= "<td class='text-center' style='vertical-align:middle'>".$arraccion[$j-1]."</td>";
			$print .= "</tr>";			
		}   
		$print .= "</tbody>";
		$print .= "</table>";	
	}	
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

<script type="text/javascript">

	$(document).ready(function () {
		$('#descargarDatosIniciales').click(function(e) {
			e.preventDefault();  //stop the browser from following			
			window.location.href = './descargar_datos_iniciales.php';
		});
	});

</script>