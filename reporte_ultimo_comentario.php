<?php 

	require 'includes/header_start.php';

	include("modelo/conectarBD.php");
	include("modelo/consultaSQL.php");

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

	$sql =  "SELECT DISTINCT(op_200_gestiones.ACACCT) AS ACACCT ";
	$sql .= "FROM op_200_gestiones ";
	$sql .= "INNER JOIN informe_datos ";
	$sql .= "ON op_200_gestiones.id = informe_datos.ID_200_GESTION ";
	$sql .= "WHERE True ";
	
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

	$sql_count =  "SELECT COUNT(op_200_gestiones.ACACCT) ";
	$sql_count .= "FROM op_200_gestiones ";
	$sql_count .= "INNER JOIN informe_datos ";
	$sql_count .= "ON op_200_gestiones.id = informe_datos.ID_200_GESTION ";
	$sql_count .= "WHERE True ";
	
	if ($reg_fil == 0) {
		$sql .= "AND op_200_gestiones.USUSUARIO = '".$_SESSION['username']."' ";	
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

	if (trim($_GET['nrojuicio']) != "") {
		$sql .= "AND informe_datos.ID_JUICIO =".$_GET['nrojuicio']." ";
	}	
	$datos = get_select($sql, $sql_count);

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
											<td>Nro. Juicio:</td>
											<td>
												<input class="form-control" name="txtjuicio" id="txtjuicio" type="text">
											</td>
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
                            <div>
 
								<p><?php  	if ($p > 1) {
												echo "<a href='?v={$vv}&min={$_GET['min']}&max={$_GET['max']}&p=".($p-1)."'>Anterior</a> - "; 
											} ?>

											Registros: <?php echo $datos['records'] ?> - 
											Página <?php echo $datos['page'] ?> de <?php echo $datos['pages'] ?>

											<?php  if ($p < $datos['pages']) {
												echo " - <a href='?v={$vv}&min={$_GET['min']}&max={$_GET['max']}&p=".($p+1)."'>Siguiente</a> "; 
											} ?></p>

                            	<table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 12px">
                                <thead>
									<tr>
										<th bgcolor="#5B9BD5">Nro Juicio</th>
										<th bgcolor="#5B9BD5">Rut</th>
										<th bgcolor="#5B9BD5">Juzgado</th>
										<th bgcolor="#5B9BD5">Rol</th>
										<th bgcolor="#C6E0B4">Codigo Accion</th>
										<th bgcolor="#C6E0B4">Accion</th>
										<th bgcolor="#C6E0B4">Codigo Respuesta</th>
										<th bgcolor="#C6E0B4">Respuesta</th>
										<th bgcolor="#C6E0B4">Comentario</th>
										<th bgcolor="#C6E0B4">Fecha</th>			
									</tr>
                                </thead>
                                <tbody>
                                 <?php
                                   	while($resul=mysql_fetch_array($datos['registros'])){
										$sql = "SELECT op_200_gestiones.ACACCODE AS CODIGO_ACCION,codigo_accion.DESCRIPCION AS ACCION, ";
										$sql .= "op_200_gestiones.ACRCCODE AS CODIGO_RESPUESTA, ";
										$sql .= "codigo_result.DESCRIPCION AS RESPUESTA, op_200_gestiones.ACCOMN AS COMENTARIO,op_200_gestiones.DATE ";
										$sql .= "FROM op_200_gestiones ";
										$sql .= "INNER JOIN codigo_accion ";
										$sql .= "ON op_200_gestiones.ACACCODE = codigo_accion.CODIGO ";
										$sql .= "INNER JOIN codigo_result ";
										$sql .= "ON op_200_gestiones.ACRCCODE = codigo_result.CODIGO ";
										$sql .= "WHERE ACACCT='".$resul["ACACCT"]."' ";
										$sql .= "ORDER BY DATE DESC ";
										$sql .= "LIMIT 1;";
										$datoComentario = call_select($sql, "");
										while($resulComentario=mysql_fetch_array($datoComentario['registros'])){
											$sql = "SELECT NUM_JUICIO,ID_CLIENTE,CECRTID,CEDOSSIERID ";
											$sql .= "FROM relacion_cliente_juicio  ";
											$sql .= "WHERE NUM_JUICIO=".substr($resul["ACACCT"],1);
											$datoJuicio = call_select($sql, "");
											while($resulJuicio=mysql_fetch_array($datoJuicio['registros'])){
								 ?>
									<tr>
										<td><?php echo substr($resul["ACACCT"],1) ?></td>
										<td><?php echo $resulJuicio["ID_CLIENTE"] ?></td>
										<td><?php echo $resulJuicio["CECRTID"] ?></td>
										<td><?php echo $resulJuicio["CEDOSSIERID"] ?></td>										
										<td><?php echo $resulComentario["CODIGO_ACCION"] ?></td>
										<td><?php echo $resulComentario["ACCION"] ?></td>
										<td><?php echo $resulComentario["CODIGO_RESPUESTA"] ?></td>
										<td><?php echo $resulComentario["RESPUESTA"] ?></td>
										<td><?php echo $resulComentario["COMENTARIO"] ?></td>
										<td><?php echo $resulComentario["DATE"] ?></td>
									</tr>
                               	<?php
											}
										}
									}
								?>
                                </tbody>
                            </table>

								<p><?php  	if ($p > 1) {
												echo "<a href='?min={$_GET['min']}&max={$_GET['max']}&p=".($p-1)."'>Anterior</a> - "; 
											} ?>

											Registros: <?php echo $datos['records'] ?> - 
											Página <?php echo $datos['page'] ?> de <?php echo $datos['pages'] ?>

											<?php  if ($p < $datos['pages']) {
												echo " - <a href='?min={$_GET['min']}&max={$_GET['max']}&p=".($p+1)."'>Siguiente</a> "; 
											} ?></p>
											
                            </div>
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
				var obj = document.getElementById('lastComment');
				var v = 0;
				window.location.href = 'get_excel_ultimo_comentario.php?v=' + v + '&min=' + encodeURIComponent(min) + '&max=' + encodeURIComponent(max);
			});

			$('#min, #max, #txtjuicio, #txtrut').change(function () {
				var min = $("[name='min']").val();
				var max = $("[name='max']").val();
				var obj = document.getElementById('lastComment');
				var v = 0;
				var nrojuicio = $("[name='txtjuicio']").val();
				var rut = $("[name='txtrut']").val();
				window.location = '?v=' + v + '&min=' + encodeURIComponent(min) + '&max=' + encodeURIComponent(max) + '&nrojuicio=' + encodeURIComponent(nrojuicio);
			 });

		});

    </script>

    <script>
	$(document).ready(function() {
		$("#min, #max").datepicker({ firstDay: 1, changeMonth: true, changeYear: true, dateFormat: 'dd/mm/yy', autoclose: true, language: 'es' });
	});
	</script>

<?php require 'includes/footer_end.php' ?>