<?php 

	require 'includes/header_start.php';
	include("modelo/conectarBD.php");
	include("modelo/consultaSQL.php");

	date_default_timezone_set('UTC');

	$sql_admin = $var_select_asterisk_from."usuarios ".$var_where."US_USUARIO='".$_SESSION['username']."' AND rol='administrador'";
	$datos_admin = call_select($sql_admin, "");
	$reg_fil = $datos_admin['num_filas'];

	if (trim($_GET['p']) == "") $p = 1;
	else $p = (int)($_GET['p']);

	$v = trim($_GET['v']);
	$vv = "0";
	if ($v == "1") {
		$v = True;
		$vv = "1";
	} else {
		$v = False;
	}

	//$sql =  "CALL sp_ulitmo_comentario; ";
	$mysqli = new mysqli($host, $usuario, $password, $basedatos);
	if (!$mysqli->query("CALL sp_ulitmo_comentario")) {
		echo "Falló CALL: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	
	$sql = "SELECT num, ACACCT, DATE, CODIGO_ACCION, COMENTARIO, CODIGO_RESPUESTA, `temp_comentario`.ID_JUICIO, ";
	$sql .= "informe_datos.FECHA_INSERT, ";
	$sql .= "codigo_accion.DESCRIPCION AS ACCION, ";
	$sql .= "codigo_result.DESCRIPCION AS RESPUESTA, "; 
	$sql .= "NUM_JUICIO,ID_CLIENTE,CECRTID,CEDOSSIERID ";
	$sql .= "FROM `temp_comentario`, ";
	$sql .= "informe_datos, ";
	$sql .= "codigo_accion, ";
	$sql .= "codigo_result, ";
	$sql .= "relacion_cliente_juicio ";
	$sql .= "WHERE `temp_comentario`.num=1 ";
	$sql .= "AND `temp_comentario`.id = informe_datos.ID_200_GESTION ";
	$sql .= "AND `temp_comentario`.CODIGO_ACCION = codigo_accion.CODIGO ";
	$sql .= "AND `temp_comentario`.CODIGO_RESPUESTA = codigo_result.CODIGO ";
	$sql .= "AND informe_datos.ID_JUICIO = relacion_cliente_juicio.NUM_JUICIO ";
	
	if (trim($_GET['min']) != "") {

		$arr = explode("/", $_GET['min']);
		$min = $arr[2]."-".$arr[1]."-".$arr[0];
		$sql .= "AND informe_datos.FECHA_INSERT >= '{$min}' ";
	}
	
	if (trim($_GET['max']) != "") {
		$arr = explode("/", $_GET['max']);
		$max = $arr[2]."-".$arr[1]."-".$arr[0];
		$sql .= "AND informe_datos.FECHA_INSERT <= '{$max}' ";
	}

	if (trim($_GET['nrojuicio']) != "") {
		$sql .= "AND informe_datos.ID_JUICIO =".$_GET['nrojuicio']." ";
	}

	$sql_count .= "SELECT COUNT(num) ";
	$sql_count .= "FROM `temp_comentario`, ";
	$sql_count .= "informe_datos, ";
	$sql_count .= "codigo_accion, ";
	$sql_count .= "codigo_result, ";
	$sql_count .= "relacion_cliente_juicio ";
	$sql_count .= "WHERE `temp_comentario`.num=1 ";	
	$sql_count .= "AND `temp_comentario`.id = informe_datos.ID_200_GESTION ";
	$sql_count .= "AND `temp_comentario`.CODIGO_ACCION = codigo_accion.CODIGO ";
	$sql_count .= "AND `temp_comentario`.CODIGO_RESPUESTA = codigo_result.CODIGO ";
	$sql_count .= "AND informe_datos.ID_JUICIO = relacion_cliente_juicio.NUM_JUICIO ";
	
	if ($reg_fil == 0) {
		$sql_count .= "AND op_200_gestiones.USUSUARIO = '".$_SESSION['username']."' ";	
	}

	if (trim($_GET['min']) != "") {

		$arr = explode("/", $_GET['min']);
		$min = $arr[2]."-".$arr[1]."-".$arr[0];
		$sql_count .= "AND informe_datos.FECHA_INSERT >= '{$min}' ";
	}
	
	if (trim($_GET['max']) != "") {
		$arr = explode("/", $_GET['max']);
		$max = $arr[2]."-".$arr[1]."-".$arr[0];
		$sql_count .= "AND informe_datos.FECHA_INSERT <= '{$max}' ";
	}

	if (!($resultado = $mysqli->query($sql))) {
		echo "Falló SELECT: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	

	

?>
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<!-- DataTables -->
<link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<!-- Responsive datatable examples -->
<link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript">

	//funcion_guarda_info('controlador/script_reporte.php?fechaIn='+document.form.min.value+'&fechaFin='+document.form.max.value+'&opcion=1','tabla_reporte')
</script>

<!-- extra css -->
<?php require 'includes/header_end.php'; ?>


<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <!-- <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Reportes</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div> -->
            <!-- end row -->

			<div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                           <form role="form" name="form">
								<table border="0">
									<thead>
										<tr height="40px;">
											<td>Fecha inicial:</td>
											<td>
												<input autocomplete="off" class="form-control" name="min" id="min" type="text" value="<?php echo $_GET['min']; ?>">
											</td>
											<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
													<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
											<td>
												<button id="getExcel" class="btn btn-success" type="button">Descargar Excel</button>
											</td>
										</tr>
										<tr>
											<td>Fecha Final:</td>
											<td>
												<input autocomplete="off" class="form-control" name="max" id="max" type="text" value="<?php echo $_GET['max']; ?>">
											</td>
											<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
										</tr>
									</thead>
								</table>
								<br>
                            </form>
                            
                        </div>
                    </div>
                </div>

        	<!-- end row -->

        </div> <!-- container -->

    </div> <!-- content -->

</div>
<!-- End content-page -->


<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php require 'includes/footer_start.php' ?>

<!-- Required datatable js -->
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
    <script src="assets/plugins/datatables/jszip.min.js"></script>
    <script src="assets/plugins/datatables/pdfmake.min.js"></script>
    <script src="assets/plugins/datatables/vfs_fonts.js"></script>
    <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatables/buttons.print.min.js"></script>
    <script src="assets/plugins/datatables/buttons.colVis.min.js"></script>
    <!-- Responsive examples -->
    <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

	<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
  	<script src="assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {

			$('#getExcel').click(function(e) {
				e.preventDefault();  //stop the browser from following
				var min = $("[name='min']").val();
				var max = $("[name='max']").val();
				var v = 0;
				window.location.href = 'get_excel_ultimo_comentario.php?v=' + v + '&min=' + encodeURIComponent(min) + '&max=' + encodeURIComponent(max);
			});

			/*$('#min, #max').change(function () {
				var min = $("[name='min']").val();
				var max = $("[name='max']").val();
				var v = 0;
				var nrojuicio = $("[name='txtjuicio']").val();
				var rut = $("[name='txtrut']").val();
				window.location = '?v=' + v + '&min=' + encodeURIComponent(min) + '&max=' + encodeURIComponent(max);
			 });*/

		});

    </script>

    <script>
	$(document).ready(function() {
		$("#min, #max").datepicker({ firstDay: 1, changeMonth: true, changeYear: true, dateFormat: 'dd/mm/yy', autoclose: true, language: 'es' });
	});
	</script>

<?php require 'includes/footer_end.php' ?>