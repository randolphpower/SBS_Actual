<?php require 'includes/header_start.php'; ?>
<!-- extra css -->
<?php require 'includes/header_end.php'; ?>


<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<form role="form" name="form" action="./controlador/script_carga_inicial.php" enctype="multipart/form-data" method="post" id="form1">

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
						<div class="card-header">Carga Inicial de Juicios</div>
							<div class="card-block">
								<div class="row">
									<div class="form-group col-sm-6">
											<label>Archivo</label><br>
											<input type="file" id="archivo" name="archivo" accept=".xlsx">
									</div>

								</div>
								<div class="row">
									<div class="form-group col-sm-6">
											<button type="submit" class="btn btn-rounded btn-primary">Subir</button>
											<button type="button" class="btn btn-rounded btn-danger" onClick="location='principal.php'">Volver</button>
									</div>
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
