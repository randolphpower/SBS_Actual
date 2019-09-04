<?php 
	require 'includes/header_start.php';
	require 'includes/header_end.php';
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
                        <h4 class="page-title">Reportes</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
			            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Reportes</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
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
				e.preventDefault();  //stop the browser from following
				var min = $("[name='min']").val();
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
		$("#min").datepicker();
	});
	</script>

<?php require 'includes/footer_end.php' ?>