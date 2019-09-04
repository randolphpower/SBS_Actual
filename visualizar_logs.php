<?php 
	require 'includes/header_start.php';
	require 'includes/header_end.php';
	$filelist = unserialize(base64_decode($_GET['filelist']));
	$content = "";
	$content .= '<table width="100%" class="table table-striped table-bordered table-hover">';
	
	foreach ($filelist as $value){ 
		$content .= '<div class="card mb-3">';	
		$content .= "<div class='card-header' style='background: #F8E4BB'><span style='font-weight:bold'>Nombre de Archivo: </span>".basename(str_replace("./","./controlador/",$value)). "</div>";
		$content .= "<div class='card-block'>";
		$file902 = fopen(str_replace("./","./controlador/",$value), 'r') or die('Unable to open file!');
		while(!feof($file902)) {		
			$content .= fgets($file902). "</br>";
		}		
		$content .= "</div>";
		$content .= "</div>";
	} 		
	
	$content .= '</table>';
?>
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">



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
                        <h4 class="page-title">Visualizar Logs</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

			<div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                           <form role="form" name="form">
								<table border="0">
									<thead>
										<tr height="40px;">
											<td>Fecha: </td>
											<td>
												<input class="form-control" name="min" id="min" type="text" value="<?php echo $_GET['min']; ?>">
											</td>
											<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
											<td>
												<button id="getExcel" class="btn btn-success" type="button">Visualizar</button>
											</td>
										</tr>

									</thead>
								</table>
								<br>
                            </form>
                            <div>											
                            </div>
                        </div>
                    </div>
                </div>
			<?php echo $content ?>
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
				var min = $("[name='min']").val();
				if (min == "") {
					alert("Debe ingresar una fecha");
					return false;
				}
				e.preventDefault();  //stop the browser from following
				
				window.location.href = 'controlador/script_descargar_archivo.php?fecha='+ encodeURIComponent(min);
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
		$("#min").datepicker({ firstDay: 1, changeMonth: true, changeYear: true, dateFormat: 'dd/mm/yy', autoclose: true, language: 'es' });
	});
	</script>

<?php require 'includes/footer_end.php' ?>