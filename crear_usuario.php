<?php
require 'includes/header_start.php';

include("modelo/consultaSQL.php");

//<!-- extra css -->
require 'includes/header_end.php';

?>
<script type="text/javascript" src="js/ajax_usuario.js"></script>
<script type="text/javascript" src="js/validador.js"></script>

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
						<div class="card-header">Crear Usuario</div>
						<div class="card-block">
							
                       		<form name="form" role="form">
                       			<div class="row">
                       				<div class="form-group col-sm-6">
                       					<label>Rut</label>
                       					<input type="text" class="form-control" id="rut" placeholder="257849637">
                       				</div>
                       				<div class="form-group col-sm-6">
                       					<label>Nombre</label>
                       					<input type="text" class="form-control" id="nombre" placeholder="Cristian">
                       				</div>
                       			</div>
                       			
                       			<div class="row">
                       				<div class="form-group col-sm-6">
                       					<label>Apellido</label>
                       					<input type="text" class="form-control" id="apellido" placeholder="Gallardo">
                       				</div>
                       				<div class="form-group col-sm-6">
                       					<label>Email</label>
                       					<input type="text" class="form-control" id="email" placeholder="cristian.gallardo@gmail.com">
                       				</div>
                       			</div>
                       			
                       			<div class="row">
                       				<div class="form-group col-sm-6">
                       					<label>Contrase&ntilde;a</label>
                       					<input type="password" class="form-control" id="contrasena" placeholder="********">
                       					<span class="font-13 text-muted">Mostrar Contrase&ntilde;a </span><input type="checkbox" id="cambioPass" onClick="cambioInput()">
                       				</div>
                       				<div class="form-group col-sm-6">
                       					<label>Celular</label>
                       					<input type="text" class="form-control" id="celular" placeholder="82968745">
                       				</div>
                       			</div>
                       			
                       			<div class="row">
                       				<div class="form-group col-sm-6">
                       					<label>Activo</label>
                       					<select class="form-control" id="activo">
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
                       			
                       			<div class="row">
									<div class="form-group col-sm-12">
										<button type="button" class="btn btn-rounded btn-secondary" onClick="location='principal.php'"><span class="zmdi zmdi-caret-left-circle"></span> Volver</button>
										<button type="button" class="btn btn-rounded btn-primary" onClick="valida_usuario('insertar')" id="guardar"><span class="fa fa-floppy-o"></span> Guardar</button>
									</div>
								</div>
                       			
                       			<div class="col-lg-6" align="center">
									 <div id="usuarios_guarda">
									 	
									 </div>
								</div>
                       		</form>
                       			
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
<!-- extra js -->
<?php require 'includes/footer_end.php' ?>
