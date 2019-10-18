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

	// $sql_select = $var_select."op_eta_proce.*, op_200_gestiones.*, op_gastos.*, relacion_cliente_juicio.*, informe_datos.FECHA_INSERT, (SELECT etapas_procesales.CD_DESC FROM etapas_procesales WHERE etapas_procesales.CD_TYPE=op_eta_proce.CSTYPE AND etapas_procesales.CD_STGID=op_eta_proce.CSSTGID) AS DESC_STGID, (SELECT codigo_accion.DESCRIPCION FROM codigo_accion WHERE codigo_accion.CODIGO=op_200_gestiones.ACACCODE) AS DESC_CODE ".$var_from."informe_datos LEFT JOIN op_eta_proce ON informe_datos.ID_ETA_PROCE = op_eta_proce.id LEFT JOIN op_200_gestiones ON informe_datos.ID_200_GESTION = op_200_gestiones.id LEFT JOIN op_gastos ON informe_datos.ID_GASTOS = op_gastos.id LEFT JOIN relacion_cliente_juicio ON informe_datos.ID_JUICIO = relacion_cliente_juicio.NUM_JUICIO";
	// $datos = call_select($sql_select, "");
	// Consulta ADMIN
	// $sql_select = $var_select;
	// $sql_select .= "op_eta_proce.*, op_200_gestiones.*, op_gastos.*, relacion_cliente_juicio.*, informe_datos.FECHA_INSERT, (SELECT etapas_procesales.CD_DESC FROM etapas_procesales WHERE etapas_procesales.CD_TYPE=op_eta_proce.CSTYPE AND etapas_procesales.CD_STGID=op_eta_proce.CSSTGID) AS DESC_STGID, (SELECT codigo_accion.DESCRIPCION FROM codigo_accion WHERE codigo_accion.CODIGO=op_200_gestiones.ACACCODE) AS DESC_CODE ".$var_from."informe_datos LEFT JOIN op_eta_proce ON informe_datos.ID_ETA_PROCE = op_eta_proce.id LEFT JOIN op_200_gestiones ON informe_datos.ID_200_GESTION = op_200_gestiones.id LEFT JOIN op_gastos ON informe_datos.ID_GASTOS = op_gastos.id LEFT JOIN relacion_cliente_juicio ON informe_datos.ID_JUICIO = relacion_cliente_juicio.NUM_JUICIO";
	// $datos = get_select($sql_select);

	$sql =  "SELECT ";

	$sql .= "relacion_cliente_juicio.NUM_JUICIO,";
	$sql .= "relacion_cliente_juicio.ID_CLIENTE,";
	$sql .= "relacion_cliente_juicio.CECRTID,";
	$sql .= "relacion_cliente_juicio.CEDOSSIERID,";

	//$sql .= "(SELECT etapas_procesales.CD_DESC ";
	//$sql .= "	FROM etapas_procesales ";
	$sql .= " etapas_procesales.CD_DESC AS DESC_STGID, ";
	
	$sql .= "op_eta_proce.CSSTDT,"; // fecha inicio
	$sql .= "op_eta_proce.CSENDDT,"; // fecha fin
	
	//$sql .= "(SELECT codigo_accion.DESCRIPCION FROM codigo_accion WHERE codigo_accion.CODIGO = op_200_gestiones.ACACCODE) AS DESC_CODE,";
	$sql .= "codigo_accion.descripcion AS DESC_CODE,";

	$sql .= "op_200_gestiones.ACCOMN,";
	if ($v) {
		$sql .= "MAX(op_200_gestiones.DATE) AS date,";
	}
	else{
		$sql .= "op_200_gestiones.DATE,";
	}
	$sql .= "op_gastos.EXDESC,";
	$sql .= "op_gastos.EXAMT,";
	$sql .= "op_gastos.EXINVOICE,";
	$sql .= "op_gastos.EXAUTDT,";
	$sql .= "informe_datos.FECHA_INSERT,";
	$sql .= "op_gastos.EXSUPPLIER ";

	$sql .= "FROM informe_datos "; // table base
	
	$sql .= "LEFT JOIN op_eta_proce ON informe_datos.ID_ETA_PROCE = op_eta_proce.id ";
	$sql .= "LEFT JOIN etapas_procesales ";
	$sql .= "ON etapas_procesales.cd_type = op_eta_proce.cstype  ";
	$sql .= "AND etapas_procesales.cd_stgid = op_eta_proce.csstgid ";
	$sql .= "LEFT JOIN op_200_gestiones ON informe_datos.ID_200_GESTION = op_200_gestiones.id ";
	$sql .= "LEFT JOIN codigo_accion ";
	$sql .= "ON codigo_accion.codigo = op_200_gestiones.acaccode ";
	$sql .= "LEFT JOIN op_gastos ON informe_datos.ID_GASTOS = op_gastos.id ";
	$sql .= "LEFT JOIN relacion_cliente_juicio ON informe_datos.ID_JUICIO = relacion_cliente_juicio.NUM_JUICIO ";

	// filter by min & max

	$sql .= "WHERE True ";

	if ($reg_fil == 0) {
		$sql .= "AND (";
		$sql .= "op_eta_proce.USUSUARIO = '".$_SESSION['username']."' ";
		$sql .= "OR op_200_gestiones.USUSUARIO = '".$_SESSION['username']."' ";
		$sql .= "OR op_gastos.USUSUARIO ='".$_SESSION['username']."') ";
	}
	
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

	if ($v) {
		$sql .= "GROUP BY informe_datos.ID_JUICIO ";
		$sql .= "ORDER BY relacion_cliente_juicio.num_juicio ";
	}
	else{
		$sql .= "ORDER BY informe_datos.FECHA_INSERT DESC, op_200_gestiones.DATE DESC ";
	}
	

	// sql just to count records

	$count_sql = "SELECT ";
	
	if ($v) {
		$count_sql .= "COUNT(DISTINCT(informe_datos.ID_JUICIO)) ";
	} else {
		$count_sql .= "COUNT(informe_datos.FECHA_INSERT) ";
	}

	$count_sql .= "FROM informe_datos ";
	$count_sql .= "LEFT JOIN op_eta_proce ON informe_datos.ID_ETA_PROCE = op_eta_proce.id ";
	$count_sql .= "LEFT JOIN op_200_gestiones ON informe_datos.ID_200_GESTION = op_200_gestiones.id ";
	$count_sql .= "LEFT JOIN op_gastos ON informe_datos.ID_GASTOS = op_gastos.id ";
	$count_sql .= "LEFT JOIN relacion_cliente_juicio ON informe_datos.ID_JUICIO = relacion_cliente_juicio.NUM_JUICIO ";

	$count_sql .= "WHERE True ";

	if ($reg_fil == 0) {
		$count_sql .= "AND (";
		$count_sql .= "op_eta_proce.USUSUARIO = '".$_SESSION['username']."' ";
		$count_sql .= "OR op_200_gestiones.USUSUARIO = '".$_SESSION['username']."' ";
		$count_sql .= "OR op_gastos.USUSUARIO ='".$_SESSION['username']."') ";
	}
	
	if (trim($_GET['min']) != "") {
		$arr = explode("/", $_GET['min']);
		$min = $arr[2]."-".$arr[1]."-".$arr[0];
		$count_sql .= "AND informe_datos.FECHA_INSERT >= '{$min}' ";
	}
	
	if (trim($_GET['max']) != "") {
		$arr = explode("/", $_GET['max']);
		$max = $arr[2]."-".$arr[1]."-".$arr[0];
		$count_sql .= "AND informe_datos.FECHA_INSERT <= '{$max}' ";
	}

	// if ($v) {
	// 	$count_sql .= "GROUP BY informe_datos.ID_JUICIO ";
	// }
	//echo $sql;
	//echo $count_sql;
	$datos = get_select($sql, $count_sql);

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
												<input class="form-control" name="min" id="min" type="text" value="<?php echo $_GET['min']; ?>">
											</td>
											<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
											<td>
												<button id="getExcel" class="btn btn-success" type="button">Descargar Excel</button>
											</td>
										</tr>
										<tr>
											<td>Fecha Final:</td>
											<td>
												<input class="form-control" name="max" id="max" type="text" value="<?php echo $_GET['max']; ?>">
											</td>
											<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
											<td>
												<input type="checkbox" name="lastComment" id="lastComment" value="1" onClick="javascript: onlyLast(this);" <?php if ($v) echo "checked"; ?>/> Ver sólo último comentario
											</td>
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
										<th bgcolor="#FFFF00">Iden. Etapa</th>
										<th bgcolor="#FFFF00">Fecha Inicio</th>
										<th bgcolor="#FFFF00">Fecha Fin</th>
										<th bgcolor="#C6E0B4">Codigo Accion</th>
										<th bgcolor="#C6E0B4">Comentario</th>
										<th bgcolor="#C6E0B4">Fecha</th>
										<th bgcolor="#FFD966">Desc. del Gasto</th>
										<th bgcolor="#FFD966">Monto Gasto</th>
										<th bgcolor="#FFD966">Nro Factura</th>
										<th bgcolor="#FFD966">Fecha Autorizacion</th>
										<th bgcolor="#FFD966">Iden. del Proveedor</th>

									</tr>
                                </thead>
                                <tbody>
                                 <?php
                                   	while($resul=mysql_fetch_array($datos['registros'])){
								 ?>
									<tr>
										<td><?php echo $resul["NUM_JUICIO"] ?></td>
										<td><?php echo $resul["ID_CLIENTE"] ?></td>
										<td><?php echo $resul["CECRTID"] ?></td>
										<td><?php echo $resul["CEDOSSIERID"] ?></td>
										<td><?php echo $resul["DESC_STGID"] ?></td>
										<td><?php echo $resul["CSSTDT"] ?></td>
										<td><?php echo $resul["CSENDDT"] ?></td>
										<td><?php echo $resul["DESC_CODE"] ?></td>
										<td><?php echo $resul["ACCOMN"] ?></td>
										<td><?php echo $resul["DATE"] ?></td>
										<td><?php echo $resul["EXDESC"] ?></td>
										<td><?php echo $resul["EXAMT"] ?></td>
										<td><?php echo $resul["EXINVOICE"] ?></td>
										<td><?php echo $resul["EXAUTDT"] ?></td>
										<td> <div style="display: none;" ><?php echo $resul["FECHA_INSERT"].'|'; ?></div> <?php echo $resul["EXSUPPLIER"] ?> </td>
									</tr>
                               	<?php
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

		function onlyLast(obj) {
			var v = 0;
			if (obj.checked) v = 1;
			var min = $("[name='min']").val();
			var max = $("[name='max']").val();
			window.location.href = 'reporte.php?v=' + v + '&min=' + encodeURIComponent(min) + '&max=' + encodeURIComponent(max);
		}

        $(document).ready(function () {

			$('#getExcel').click(function(e) {
				e.preventDefault();  //stop the browser from following
				var min = $("[name='min']").val();
				var max = $("[name='max']").val();
				var obj = document.getElementById('lastComment');
				var v = 0;
				if (obj.checked) v = 1;
				window.location.href = 'get_excel.php?v=' + v + '&min=' + encodeURIComponent(min) + '&max=' + encodeURIComponent(max);
			});

			$('#min, #max').change(function () {
				var min = $("[name='min']").val();
				var max = $("[name='max']").val();
				var obj = document.getElementById('lastComment');
				var v = 0;
				if (obj.checked) v = 1;
				window.location = '?v=' + v + '&min=' + encodeURIComponent(min) + '&max=' + encodeURIComponent(max);
			 });

		});

    </script>

    <script>
	$(document).ready(function() {
		$("#min, #max").datepicker({ firstDay: 1, changeMonth: true, changeYear: true, dateFormat: 'dd/mm/yy', autoclose: true, language: 'es' });
	});
	</script>

<?php require 'includes/footer_end.php' ?>