<?php
	session_start();
	require_once("/modelo/consultaSQL.php");
	require_once("/modelo/conectarBD.php");	
	$archivotmp = $_FILES['archivo']['tmp_name'];	
	$lineas = file($archivotmp);
	$tipo = $_POST["tipo"];
	$_SESSION['tipo'] = $tipo;
	$_SESSION['archivotmp'] = $_FILES['archivo']['name'];
	$error_en_archivo = false;
//	$lineas = file($file);
	$i = 0;
	foreach ($lineas as $linea_num => $linea) {
		if ($i != 0) {            
			$datos = explode(";", $linea);
            
			$numjuicio = trim($datos[0]);			
			$tipojuicio = trim($datos[2]);
			$rutcliente = trim($datos[4]);
			
            $sql_searh = "SELECT * FROM juicios_dato_inicial WHERE id_juicio = ".$numjuicio;
			
			if ($numjuicio != "") {
				$registroJuicio = call_select($sql_searh, "");

				if ($registroJuicio['num_filas'] == 0) { // No existe el registro
					$arrnumjuicio[$i-1] = $numjuicio;
					$arrrutcliente[$i-1] = $rutcliente;
					$arrtipojuicio[$i-1] = $tipojuicio;
					$arrmsj[$i-1] = "Juicio no existe en datos iniciales";
					$arrcolorrow[$i-1] = "style=background-color:coral;font-weight:bold;color:black;";
				} else { // existe el registro
	
					$tipo_juicio = mysql_fetch_array($registroJuicio['registros'])['tipo_juicio'];
					if ($tipo_juicio != $tipojuicio) {
						$arrmsj[$i-1] = "Tipo Juicio incorrecto (".$tipo_juicio.")";
						$arrcolorrow[$i-1] = "style=background-color:coral;font-weight:bold;color:black;";
						$error_en_archivo = true;
					}
					else {
						$arrmsj[$i-1] = "Registro correcto";
						$error_en_archivo = true;
					}	
					$arrnumjuicio[$i-1] =$numjuicio;
					$arrrutcliente[$i-1] =$rutcliente;
					$arrtipojuicio[$i-1] =$tipojuicio;
				 }
			}			
	   }
	   $i++;
	}
	require 'includes/header_start.php';
	require 'includes/header_end.php';
?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<form role="form" name="form" action="cargamasiva.php" enctype="multipart/form-data" method="post" id="form1">
	<div class="content-page">
		<!-- Start content -->
		<div class="content">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">
						<div class="card">
							<div class="card-header">Previsualizacion de Carga</div>
							<div class="card-block">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr align="center" class="info text-center text-default" style="vertical-align:middle">
											<th class="col-md-1 text-center" style="vertical-align:middle">Nro.</th>
											<th class="col-md-1 text-center" style="vertical-align:middle">Identificador</th>
											<th class="col-md-1 text-center" style="vertical-align:middle">Rut Cliente</th>
											<th class="col-md-1 text-center" style="vertical-align:middle">Tipo Juicio</th>
											<th class="col-md-1 text-center" style="vertical-align:middle">Mensaje</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$count = count($arrnumjuicio);
											for ($i = 0; $i < $count; $i++) {
										?>
										<tr class="text-center text-muted" data-placement="top" <?php echo $arrcolorrow[$i]; ?>>
											<td class="text-center" style="vertical-align:middle"><?php echo $i+1; ?></td>
											<td class="text-center" style="vertical-align:middle"><?php echo $arrnumjuicio[$i]?></td>
											<td class="text-center" style="vertical-align:middle"><?php echo $arrrutcliente[$i]?></td>
											<td class="text-center" style="vertical-align:middle"><?php echo $arrtipojuicio[$i]?></td>
											<td class="text-center" style="vertical-align:middle"><?php echo $arrmsj[$i]?></td>
										</tr>
										<?php
											}?>
									</tbody>
								</table>                         
							</div>
						</div>
					</div>
				</div><!-- end row -->
				<div class="row">
					<div class="form-group col-sm-6">
						<button type="submit" class="btn btn-rounded btn-primary" id="subir">Subir</button>
						<button type="button" class="btn btn-rounded btn-danger" onClick="location='carga_datos.php'">Volver a Carga de Archivos</button>										
					</div>
				</div>
			</div> <!-- container -->
		</div> <!-- content -->
	</div> <!-- End content-page -->
</form>

<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php
	require 'includes/footer_start.php';
	require 'includes/footer_end.php';
?>

<script type="text/javascript">
	var errorEnArchivo = "<?php echo $error_en_archivo ?>"; 
	$(document).ready(function () {
		$('#subir').click(function(e) {
			if (errorEnArchivo == "") {
				alert("Existen errores en el archivo a cargar");
				return false;
			};
		});
	});
</script>
