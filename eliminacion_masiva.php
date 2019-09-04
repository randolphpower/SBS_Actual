<?php 

require 'includes/header_start.php';

include("modelo/conectarBD.php");
include("modelo/consultaSQL.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

require 'vendor/autoload.php';

?>
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<!-- DataTables -->
<link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<!-- Responsive datatable examples -->
<link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<!-- extra css -->
<?php require 'includes/header_end.php'; ?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
<div class="content">
<div class="container">
<!-- content //-->

<div class="row">
	<div class="col-xs-12">
		<div class="page-title-box">
			<h3 class="page-title">Eliminación Masiva de Registros</h3>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<pre>
<?php

$debug = false;

if (isset($_FILES['file'])) {

	$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
 
	if (isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {

		$arr_file = explode('.', $_FILES['file']['name']);
		$extension = end($arr_file);

		if('csv' == $extension) {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		} else {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}

		$spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		$sheetData = $spreadsheet->getActiveSheet()->toArray();

		$i = 0;
		foreach($sheetData as $line) {
			if ($i > 0) {

				$num_juicio = $line[0];
				$rut = $line[1];

				echo "RUT: {$rut}\n";

				$sql = "SELECT * FROM relacion_cliente_juicio ";
				$sql .= "WHERE ID_CLIENTE = '{$rut}' AND NUM_JUICIO = '{$num_juicio}';";
				$data = call_select($sql, "");
				
				$records = $data['num_registros'];

				if ($records == 0) {
					echo "Advertencia: No tiene registros.\n\t";
				} 

				// Info Juicios
				$sql = "SELECT * FROM op_info_juicios ";
				$sql .= "WHERE CNCASENO = '{$num_juicio}';";
				$data = call_select($sql, "");

				// Informe datos
				$sql = "SELECT * FROM informe_datos ";
				$sql .= "WHERE ID_JUICIO = '{$num_juicio}';";
				$data = call_select($sql, "");

				echo "\t------------------------------------------------------------------------\n";
				echo "\tInforme de datos\n";
				echo "\t------------------------------------------------------------------------\n";

				while ($results = mysql_fetch_array($data['registros'])) {   
					echo "\tId: {$results['id']}, ";
					echo "Proc: {$results['ID_ETA_PROCE']}, ";
					echo "Gestión: {$results['ID_200_GESTION']}, ";
					echo "Gasto: {$results['ID_GASTOS']}\n";
				}

				// Etapas Procesales
				$sql = "SELECT * FROM op_eta_proce ";
				$sql .= "WHERE CSCASENO = '{$num_juicio}';";
				$data = call_select($sql, "");

				echo "\t------------------------------------------------------------------------\n";
				echo "\tEtapas Procesales\n";
				echo "\t------------------------------------------------------------------------\n";

				while ($results = mysql_fetch_array($data['registros'])) {   
					echo "\tId: {$results['id']}, ";
					echo "Etapa: {$results['CSSTGID']}\n";
				}

				// 200 Gestiones
				$sql = "SELECT * FROM op_200_gestiones ";
				$sql .= "WHERE ACACCT = '{$num_juicio}';";
				$data = call_select($sql, "");

				echo "\t------------------------------------------------------------------------\n";
				echo "\t200 Gestiones\n";
				echo "\t------------------------------------------------------------------------\n";

				while ($results = mysql_fetch_array($data['registros'])) {   
					echo "\tId: {$results['id']}, ";
					echo "Comentario: {$results['ACCOMN']}\n";
				}

				// Gastos
				$sql = "SELECT * FROM op_gastos ";
				$sql .= "WHERE CSCASENO = '{$num_juicio}';";
				$data = call_select($sql, "");

				echo "\t------------------------------------------------------------------------\n";
				echo "\tGastos\n";
				echo "\t------------------------------------------------------------------------\n";

				while ($results = mysql_fetch_array($data['registros'])) {   
					echo "\tId: {$results['id']}, ";
					echo "Etapa: {$results['CSSTGID']}";
					echo "Desc: {$results['EXDESC']}\n";
				}

				if (!$debug) {

					$sql = "DELETE FROM informe_datos WHERE ID_JUICIO = '{$num_juicio}';";
					call_select($sql, "");

					$sql = "DELETE FROM op_eta_proce WHERE CSCASENO = '{$num_juicio}';";
					call_select($sql, "");

					$sql = "DELETE FROM op_200_gestiones WHERE ACACCT = '{$num_juicio}';";
					call_select($sql, "");

					$sql = "DELETE FROM op_gastos WHERE CSCASENO = '{$num_juicio}';";
					call_select($sql, "");

					$sql = "DELETE FROM relacion_cliente_juicio WHERE ID_CLIENTE = '{$rut}' AND NUM_JUICIO = '{$num_juicio}';";
					call_select($sql, "");

					$sql = "DELETE FROM op_info_juicios WHERE CNCASENO = '{$num_juicio}';";
					call_select($sql, "");

					echo "<<<<<<<<-- REGISTROS ELIMINADOS!!!! -->>>>>>>> \n";

				}

				ob_flush();
				flush();

			}
			$i++;
		}
	}

} else { ?>
<div class="row">
	<form method="POST" enctype="multipart/form-data" action="eliminacion_masiva.php">
		<div class="form-group">
				<input type="file" name="file" class="form-control" accept=".xlsx, .xls" id="xlsxInputFile">
		</div>
		<button type="submit" class="btn btn-primary">Enviar</button>
	</form>
</div>
<?php 
}




?>
</pre>


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

<!-- following js will activate the menu in left side bar based on url -->
<script type="text/javascript">
$(document).ready(function() {

	$("#sidebar-menu a").each(function() {
		if (this.href == window.location.href) {
				$(this).addClass("active");
				$(this).parent().addClass("active"); // add active to li of the current link
				$(this).parent().parent().prev().addClass("active"); // add active class to an anchor
				$(this).parent().parent().prev().click(); // click the item to make it drop
		}
	});

	$('#searchInput').change(function() {
		onSearch();
	});




});
</script>

</body>
</html>

