<?php require 'includes/header_start.php'; ?>
<!-- extra css -->
<?php require 'includes/header_end.php'; ?>


<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<form role="form" name="form" action="cargamasiva.php" enctype="multipart/form-data" method="post" id="form1">

<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Carga</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        	<div class="row">
            	<div class="col-sm-12">
					<div class="card">
						<div class="card-header">Carga de Archivo CSV</div>
						<div class="card-block"><b>Ejemplo de Archivo de Juicios</b>
						<a href="ejemplo_archivo_juicios.csv">DESCARGAR</a>
						<br>

NUM_JUICIO;TRIBUNAL;TIPO;ROL;RUT_CLIENTE;NOMBRE_DEUDOR;FECHA_INICIO;FECHA_DEM<br>

238402;25JUZCIVSANTI;EJCPMM-5;C-4930-2013;14345545;FERNANDO ANDRES CASTRO RAMIREZ;20-03-2017;21-10-2018<br>
238404;17JUZCIVSANTI;EJCPMM1;C-12289-2013;13757535;CHRISTIAN ANDRE TARBES CARRASCO;20-03-2017;21-10-2018

<br><br><b>Ejemplo de Archivo de Gastos</b> <a href="ejemplo_archivo_gastos.csv">DESCARGAR</a>
<br>CODIGOGASTO;GESTION;MONTO<br>
EXP11;Busqueda de Bienes (propiedades);21111<br>
EXP12;Busqueda de Bienes (vehículos);21111<br><br>

							<div class="row">
								<div class="form-group col-sm-6">
										<label>Archivo</label><br>
										<input type="file" id="archivo" name="archivo" accept=".csv">
								</div>

							</div>
							<div class="row">

								<div class="form-group col-sm-6">
									 <label for="exampleSelect1">Tipo de Archivo</label>
                                            <select class="form-control" id="tipo" name="tipo">
                                                <option value="1"> Archivo de Juicio</option>
                                                <option value="2">Archivo de Gastos</option>
                                            </select>
								</div>


							</div>
							<div class="row">

								<div class="form-group col-sm-6">
										<button type="submit" class="btn btn-rounded btn-primary">Subir</button>
										<button type="button" class="btn btn-rounded btn-primary" id="preview">Previsualizar Carga</button>
										<button type="button" class="btn btn-rounded btn-danger" onClick="location='principal.php'">Volver</button>										
								</div>
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
</form>

<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php require 'includes/footer_start.php' ?>
<!-- extra js -->
<?php require 'includes/footer_end.php' ?>

<script type="text/javascript">

	$(document).ready(function () {
		var archivo = "";

		$('#archivo').change(function(e) {
			archivo = e.target.files[0].name;
			
		});

		$('#preview').click(function(e) {
			
			e.preventDefault();  //stop the browser from following			
			window.location.href = './preview_carga_masiva.php?archivo=' + archivo;
		});
	});

</script>