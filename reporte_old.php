<?php require 'includes/header_start.php'; ?>

<?php
	include("modelo/conectarBD.php");
	include("modelo/consultaSQL.php");

	$sql_admin = $var_select_asterisk_from."usuarios ".$var_where."US_USUARIO='".$_SESSION['username']."' AND rol='administrador'";
	$datos_admin = call_select($sql_admin, "");
	$reg_fil = $datos_admin['num_filas'];

	if($reg_fil==1){

		$sql_select = $var_select."op_eta_proce.*, op_200_gestiones.*, op_gastos.*, relacion_cliente_juicio.*, informe_datos.FECHA_INSERT, (SELECT etapas_procesales.CD_DESC FROM etapas_procesales WHERE etapas_procesales.CD_TYPE=op_eta_proce.CSTYPE AND etapas_procesales.CD_STGID=op_eta_proce.CSSTGID) AS DESC_STGID, (SELECT codigo_accion.DESCRIPCION FROM codigo_accion WHERE codigo_accion.CODIGO=op_200_gestiones.ACACCODE) AS DESC_CODE ".$var_from."informe_datos LEFT JOIN op_eta_proce ON informe_datos.ID_ETA_PROCE = op_eta_proce.id LEFT JOIN op_200_gestiones ON informe_datos.ID_200_GESTION = op_200_gestiones.id LEFT JOIN op_gastos ON informe_datos.ID_GASTOS = op_gastos.id LEFT JOIN relacion_cliente_juicio ON informe_datos.ID_JUICIO = relacion_cliente_juicio.NUM_JUICIO";

		$datos = call_select($sql_select, "");

	}else{

		$sql_select = $var_select."op_eta_proce.*, op_200_gestiones.*, op_gastos.*, relacion_cliente_juicio.*, informe_datos.FECHA_INSERT, (SELECT etapas_procesales.CD_DESC FROM etapas_procesales WHERE etapas_procesales.CD_TYPE=op_eta_proce.CSTYPE AND etapas_procesales.CD_STGID=op_eta_proce.CSSTGID) AS DESC_STGID, (SELECT codigo_accion.DESCRIPCION FROM codigo_accion WHERE codigo_accion.CODIGO=op_200_gestiones.ACACCODE) AS DESC_CODE ".$var_from."informe_datos LEFT JOIN op_eta_proce ON informe_datos.ID_ETA_PROCE = op_eta_proce.id LEFT JOIN op_200_gestiones ON informe_datos.ID_200_GESTION = op_200_gestiones.id LEFT JOIN op_gastos ON informe_datos.ID_GASTOS = op_gastos.id LEFT JOIN relacion_cliente_juicio ON informe_datos.ID_JUICIO = relacion_cliente_juicio.NUM_JUICIO ".$var_where."op_eta_proce.USUSUARIO ='".$_SESSION['username']."' OR op_200_gestiones.USUSUARIO ='".$_SESSION['username']."' OR op_gastos.USUSUARIO ='".$_SESSION['username']."'";

		$datos = call_select($sql_select, "");
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

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Reportes</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

			<div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                           <form role="form" name="form">
								<table border="0" >
									<thead>
										<tr height="40px;">
											<td>Fecha inicial:</td>
											<td><input class="form-control" name="min" id="min" type="text"></td>
										</tr>
										<tr>
											<td>Fecha Final:</td>
											<td><input class="form-control" name="max" id="max" type="text"></td>
											<!--<td>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-success" onClick="funcion_guarda_info('controlador/script_reporte.php?fechaIn='+document.form.min.value+'&fechaFin='+document.form.max.value+'&opcion=1','hola')">Buscar</button></td>-->
										</tr>
									</thead>
								</table>
								<br>
                            </form>
                            <div>
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

            //Buttons examples
            var table = $('#datatable-buttons').DataTable({
                lengthChange: false,
				responsive: true,
				buttons: ['copy', 'excel', 'pdf', 'colvis']/*,
				columnDefs:[
				{
					targets:[15],
					visible: false,
					searchable: false
				}
				]*/
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

			$.fn.dataTable.ext.search.push(
			function (settings, data, dataIndex) {
				var min = $('#min').datepicker("getDate");
				var max = $('#max').datepicker("getDate");

				var x = data[14].split("|");
				var d = x[0].split("/");
				var startDate = new Date(d[2]+"/"+d[1]+"/"+d[0]);

				if (min == null && max == null) { return true; }
				if (min == null && startDate <= max) { return true;}
				if(max == null && startDate >= min) {return true;}
				if (startDate <= max && startDate >= min) { return true; }
				return false;
			}
			);

			// Event listener to the two range filtering inputs to redraw on input
			 $('#min, #max').change(function () {
				 table.draw();
			 });

		});

    </script>

    <script>
		$(document).ready(function() {

     		$("#min, #max").datepicker({ firstDay: 1, changeMonth: true, changeYear: true, dateFormat: 'dd/mm/yy', autoclose: true, language: 'es' });
		});


	</script>

<?php require 'includes/footer_end.php' ?>