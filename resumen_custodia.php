<?php
	include("modelo/conectarBD.php");
	include("modelo/consultaSQL.php");

  $sql = $var_select." b.NOMBRE_EMPRESA, a.*, c.NOMBRE_ESTADO,dias_en_custodia(a.id) AS dias ".$var_from."registros_custodia a, empresas_afiliadas b, estado_custodia c ".$var_where."(a.ID_EMPRESA=b.ID) ".$var_and."(a.ID_ESTADO=c.ID_ESTADO)";
	$datosx=array();
	$datosx = call_select($sql, "");
	$reg_fil = $datosx['num_registros'];


?>
<style>
.divTopRow{
  style="width: 100%; overflow: hidden;"
}
  .dt-buttons{
    width: 600px; float: left;
  }
  .datatable2_filter{
    margin-left: 620px;
  }

.divBottomRow{
  style="width: 100%; overflow: hidden;"
}
  .dataTables2_length{
    width: 600px; float: left;
  }
  .dataTables2_paginate{
    margin-left: 620px;
  }
</style>
<?php require 'includes/header_start.php'; ?>
<!-- DataTables -->
    <link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css"/>

<!-- extra css -->
<?php require 'includes/header_end.php'; ?>
<script type="text/javascript" src="js/ajax_usuario.js"></script>


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
										<th>Mandante</th>
										<th>Nro. Pagare</th>										
										<th>Rut</th>
										<th>Dv</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Procurador</th>
                    <th>Dias en Custodia</th>
                    <th>Documento</th>
									</tr>
                                </thead>
                                <tbody>
                                 <?php
                                   	while($resul=mysql_fetch_array($datosx['registros'])){
										
								 ?>
									<tr >
										<td><?php echo $resul["NOMBRE_EMPRESA"] ?></td>
										<td><?php echo $resul["NRO_PAGARE_ORIGINAL"] ?></td>										
										<td><?php echo $resul["RUT_SIN_DV"] ?></td>
										<td><?php echo $resul["DV_RUT"] ?></td>
                    <td><?php echo $resul["NOMBRE"] ?></td>
                    <td data-toggle="modal" data-target="#modalEstado"data-whatever="<?php echo $resul["ID"] ?>|<?php echo $resul["NRO_PAGARE_ORIGINAL"] ?>|<?php echo $resul["NOMBRE_ESTADO"] ?>" style="cursor: pointer;"><?php echo $resul["NOMBRE_ESTADO"] ?></td>
                    <td><?php echo $resul["USUSUARIO"] ?></td>
                    <td><?php echo $resul["dias"] ?></td>                    
                    <td align="center"><a target="_blank" onclick="window.open('<?php echo $resul["URL"];?>', '<?php echo "Archivo: ".$resul['NRO_PAGARE_ALTERADO']; ?>', 'directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=600, height=800')" style="cursor:pointer;"><i class="zmdi zmdi-collection-pdf zmdi-hc-2x"></i></a></td></td>                    
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEstado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 75%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
      </div>
      <div class="modal-body" style="height: 700px">
        <div class="">
        	
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
            var table = $('#datatable2').DataTable({
                dom: '<"divTopRow"Bf>t<"divTopRow"pl>',
                buttons: [ 'excel' ],
                language: {                  
                  "zeroRecords": "Registros no encontrados",
                  "info": "Mostrando pagina _PAGE_ de _PAGES_",
                  "infoEmpty": "No hay registros disponible",
                  "infoFiltered": "(filtered from _MAX_ total records)",
                  "paginate": {
                      "first":      "Primero",
                      "last":       "Ultimo",
                      "next":       "Siguiente",
                      "previous":   "Anterior"
                  },
                  "lengthMenu":     "Mostrando _MENU_ registros por pagina",
                  "search":         "Buscar:",
                }
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

        $('#modalEstado').on('show.bs.modal', function (event) {
          var tableHistorialEstados = $('#tableHistorialEstados').DataTable({
                responsive: true,
				        buttons: ['copy', 'excel', 'pdf', 'colvis']
            });
          var button = $(event.relatedTarget) // Button that triggered the modal
          var recipient = button.data('whatever') // Extract info from data-* attributes
          var datos = recipient.split("|");
          var modal = $(this);
          modal.find('.modal-title').text('Informacion Pagare (' + datos[1] + ") - Estado (" + datos [2] + ")");

          ajax=objetoAjax_Global();
          ajax.open("GET", 'controlador/script_custodia.php?id_custodia='+datos[0]+'&opcion=6');
          ajax.onreadystatechange=function() {
            if (ajax.readyState==4) {
              modal.find('.modal-body').html(ajax.responseText);					
            }
          }      
          ajax.send(null)
        });

        function GrabarModificacionEstado(){	
          var selector_estado=document.getElementById("selectorestado").value;
	        var observacion=document.getElementById("observacion").value;
          var id_custodia=document.getElementById("idCustodia").value;

          if(selector_estado==0){
            alert("¡ERROR! Ingrese el estado a modificar");
            document.getElementById("selectorestado").focus();
            return;
          }

          if(observacion==""){
            alert("¡ERROR! Debe ingresar la observación de la modificación del estado.");
            document.getElementById("observacion").focus();
            return;
          }

          ajax=objetoAjax();
		      ajax.open("GET", 'controlador/script_custodia.php?selector_estado='+selector_estado+"&id_custodia="+id_custodia+"&observacion="+observacion+'&opcion=7');
		      ajax.onreadystatechange=function() {
          if (ajax.readyState==4) {
              var resp = ajax.statusText;
              if(resp=="OK"){
                alert("¡Bien hecho! Actualización de Estado de Pagare exitoso.");						
                $('#modalEstado').modal('hide');
                location.reload();  
              }
            } 
		      }
		      ajax.send(null)                  
        }
    </script>

<?php require 'includes/footer_end.php' ?>
