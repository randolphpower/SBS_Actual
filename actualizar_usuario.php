<?php
require 'includes/header_start.php';

include("modelo/consultaSQL.php");

//<!-- extra css -->
require 'includes/header_end.php';

//LLamada para ir a buscar los datos de la tabla usuarios.
$datosUsuarios=array();
$datosUsuarios=call_select($var_select_asterisk_from."usuarios ");	

?>
<script type="text/javascript" src="js/validador.js"></script>
<script type="text/javascript" src="js/ajax_usuario.js"></script>

<script type="text/javascript">

	function cambioInput(){

			if(document.form.cambioPass.checked==true){
				document.form.contrasena.type = "text";
			}else{
				document.form.contrasena.type = "password";
			}
		}

</script>

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
                        <h1 class="page-title">Usuarios</h1>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            
            <div class="row">
            	<div class="col-sm-12">
					<div class="card">
						<div class="card-header">Informacion de Juicios</div>
						<div class="card-block">
							<div class="table-responsive">
                        			<table width="100%" class="table table-striped table-bordered table-hover">
										<tr>
											<th>Rut</th>
											<th>Nombre</th>
											<th>Celular</th>
											<th>Email</th>
											<th>Activo</th>
											<th></th>
										</tr>
									<?php 
										while($resUsuarios=mysql_fetch_array($datosUsuarios['registros'])){
										?>
											<tr class="text-center text-muted" data-placement="top">
												<td><?php echo $resUsuarios["US_RUT"]?></td>
												<td><?php echo $resUsuarios["US_NOMBRE"]." ".$resUsuarios["US_APELLIDOS"] ?></td>
												<td><?php echo $resUsuarios["US_CELULAR"]?></td>
												<td><?php echo $resUsuarios["US_EMAIL"]?></td>
												<td><?php if($resUsuarios["US_ACTIVO"]==1){ echo "Si"; }else{ echo "No"; } ?></td>
												<td align="center"><button class="btn btn-success" type="button" data-toggle="modal" data-target="#editarUsuario" data-whatever="<?php echo $resUsuarios["US_NOMBRE"] ?>/<?php echo $resUsuarios["US_APELLIDOS"] ?>/<?php echo $resUsuarios["US_RUT"] ?>/<?php echo $resUsuarios["US_EMAIL"] ?>/<?php echo $resUsuarios["US_CELULAR"] ?>/<?php echo $resUsuarios["US_ACTIVO"] ?>/<?php echo $resUsuarios["rol"] ?>"><span class="fa fa-pencil"></span></button></td>
											</tr>
										<?php   
										}

									 ?>
									</table>
                       			</div>
                       			
                       			<div class="col-lg-6" align="center">
									 <div id="info_juicios"></div>
								</div>
                       			
						</div>
					</div>
				</div>
            </div>
            
            <!-- end row -->
				
        </div> <!-- container -->

    </div> <!-- content -->

</div>
<!-- End content-page -->


<div class="modal fade bs-example-modal-lg" id="editarUsuario" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
             	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h4 class="modal-title" id="mySmallModalLabel"><div id="num_op"></div></h4>
             </div>
         	 <div class="modal-body">
               	<form role="form" name="form">

					<div id="collapse1" class="collapse in" role="tabpanel" aria-labelledby="heading1" data-parent="#accordion">
						<div class="card-block">
							<div class="row">
     							<div class="form-group col-sm-6" col>
      								<label>Rut</label>

      								<input type="hidden" placeholder="181740639" disabled class="form-control" id="rut" width="70px" tabindex="1" onKeyPress="return soloNum_LetraRUT(event);">
                                    <input type="text" disabled class="form-control" id="rut_aux" width="70px" tabindex="1">

      							</div>
      							<div class="form-group col-sm-6">
      								<label>Nombre</label>
      								<input type="text" placeholder="Claudia" class="form-control" id="nombre" tabindex="2">
      							</div>
      						</div>
      						<div class="row">
      							<div class="form-group col-sm-6">
      								<label>Apellido</label>
      								 <input type="text" placeholder="Gallardo" class="form-control" id="apellido" width="70px" tabindex="3">
      							</div>
      							<div class="form-group col-sm-6">
      								<label>Email</label>
      								<input type="text" class="form-control" id="email" placeholder="claudia.gallardo@gmail.com" tabindex="4">
      							</div>
      						</div>
      						<div class="row">
      							<div class="form-group col-sm-6">
      								<label>Contrase&ntilde;a</label>
      								<input class="form-control" type="password" id="contrasena" placeholder="******" tabindex="5">
      								<span class="font-13 text-muted">Mostrar Contrase&ntilde;a </span><input type="checkbox" id="cambioPass" onClick="cambioInput()">
      							</div>
      							<div class="form-group col-sm-6">
      								<label>Celular</label>
      								<input class="form-control" type="text" id="celular" placeholder="82945122" maxlength="9" tabindex="6" onKeyPress="return solonum(event)">
      							</div>
      						</div>

      						<div class="row">
      							<div class="form-group col-sm-6">
      								<label>Activo</label>
      								<select class="form-control" id="activo" tabindex="7">
      									<option value="0">No</option>
      									<option value="1">Si</option>
      								</select>
      							</div>
      							<div class="form-group col-sm-6">
                       				<label>Rol</label>
                       				<select class="form-control" id="rol">
                       					<option>procurador</option>
                       					<option>administrador</option>
                       				</select>
                       			</div>
      						</div>

      						<div>
								<button class="btn btn-success btn-sm btn-rounded waves-effect waves-light" type="button" onClick="valida_usuario('actualizar')" id="guardar" tabindex="10"><strong><span class="fa fa-floppy-o"></span> Guardar</strong></button>
							</div>
     						<br>
     						<div>
     							<div id="usuarios_guarda" align="center">

     							</div>
     						</div>

						</div>
					</div>
				</form>
         	 </div>
     	 </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
</div>


<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php require 'includes/footer_start.php' ?>
<!-- extra js -->

<script>

		$('#editarUsuario').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var recipient = button.data('whatever') // Extract info from data-* attributes
		  var datos = new Array();
		  datos = recipient.split("/");

		  var modal = $(this);
		  var nomb_op=datos[1];


		  document.getElementById('num_op').innerHTML= 'Editar '+datos[0]+" "+nomb_op;
		  document.form.nombre.value = datos[0];
		  document.form.apellido.value = datos[1];
		  document.form.rut.value = datos[2];
		  document.form.rut_aux.value = formato_rut(datos[2]);
		  document.form.email.value = datos[3];
		  document.form.celular.value = datos[4];
		  document.form.activo.value = datos[5];
		  document.form.rol.value = datos[6];	

		});

</script>

<?php require 'includes/footer_end.php' ?>
