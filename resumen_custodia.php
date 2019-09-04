<?php
	include("modelo/conectarBD.php");
	include("modelo/consultaSQL.php");

	$sql = $var_select." b.NOMBRE_EMPRESA, a.*, c.NOMBRE_ESTADO ".$var_from."registros_custodia a, empresas_afiliadas b, estado_custodia c ".$var_where."(a.ID_EMPRESA=b.ID) ".$var_and."(a.ID_ESTADO=c.ID_ESTADO)";
	$datosx=array();
	$datosx = call_select($sql, "");
	$reg_fil = $datosx['num_registros'];


?>

<?php require 'includes/header_start.php'; ?>
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
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Reporte Custodia</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

			<div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                           <div>
                            <table id="datatable2" class="table table-striped table-bordered" width="100%">
                                <thead>
									<tr>
										<th>Empresa</th>
										<th>ID</th>
										<th>Nro. Pagare</th>
										<th>Estado</th>
										<th>Rut</th>
										<th>Dv</th>
										<th>Nombre</th>
										<th>Direcci&oacute;n</th>
										<th>Comuna</th>
										<th>Distrito</th>
									</tr>
                                </thead>
                                <tbody>
                                 <?php
                                   	while($resul=mysql_fetch_array($datosx['registros'])){
										
								 ?>
									<tr data-toggle="modal" data-target="#exampleModal" data-whatever="<?php echo $resul["URL"] ?>|<?php echo $resul["NRO_PAGARE_ORIGINAL"] ?>" style="cursor: pointer;">
										<td><?php echo $resul["NOMBRE_EMPRESA"] ?></td>
										<td><?php echo $resul["ID_REFERENCIA"] ?></td>
										<td><?php echo $resul["NRO_PAGARE_ORIGINAL"] ?></td>
										<td><?php echo $resul["NOMBRE_ESTADO"] ?></td>
										<td><?php echo $resul["RUT_SIN_DV"] ?></td>
										<td><?php echo $resul["DV_RUT"] ?></td>
										<td><?php echo $resul["NOMBRE"] ?></td>
										<td><?php echo $resul["DIRECCION"] ?></td>
										<td><?php echo $resul["COMUNA"] ?></td>
										<td><?php echo $resul["DISTRITO"] ?></td>
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 75%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
      </div>
      <div class="modal-body" style="height: 570px">
        <div class="">
        	<embed class="form-control" id="id_pdf" src="" width="800" height="530" type="application/pdf"></embed>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


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
        $(document).ready(function () {

            //Buttons examples
            var table = $('#datatable2').DataTable({
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
			
           
		});
		
		$('#exampleModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var recipient = button.data('whatever') // Extract info from data-* attributes
		  var datos = recipient.split("|");
		  
		  var modal = $(this);
		  modal.find('.modal-title').text('PDF - '+datos[1]);
		  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		  $("#id_pdf").attr("src",datos[0]+"#toolbar=0");
		});

    </script>

<?php require 'includes/footer_end.php' ?>
