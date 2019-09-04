<?php 

require 'includes/header_start.php';

include("modelo/conectarBD.php");
include("modelo/consultaSQL.php");

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
			<h3 class="page-title">Eliminación Manual de Registros</h3>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<div class="row">
	<label>
		Buscar <input id="searchInput" type="text" class="form-control" value="" placeholder="Ingrese RUT" />
	</label>
</div>

<div class="row" id="results"></div>

<!-- content //-->
</div>
</div>
</div>


<script type="text/javascript">

function onSearch() {

	var rut = $('#searchInput').val();

	// alert(rut)
	$.ajax({
		type: 'POST',
		url: 'eliminacion_ajax.php',
		data: { rut: rut },
		success: function(data) {
			$('#results').html(data);
		},
		error: function(data){
			console.log(data);
		}
	});

}


</script>


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

