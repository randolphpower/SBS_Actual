<?php require 'includes/header_start.php'; ?>

<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/validador.js"></script>
<script type="text/javascript" src="js/ajax_gastos.js"></script>
<script type="text/javascript" src="js/ajax_200_gestion.js"></script>


<script type="text/javascript">

function pasarCodR(descCodR){
	var codR = document.form.codR.value;
	mostrarCodR('controlador/script_200_gestion.php?codR='+codR+'&opcion=7')
}

</script>

<!-- extra css -->
<?php require 'includes/header_end.php'; ?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <!-- Start content -->
    <div class="content">
    <div class="container">
	<!-- container //-->

		<div class="row">
		<div class="col-xs-12">
			<div class="page-title-box">
				<h1 class="page-title">Proceso Judicial</h1>
				<div class="clearfix"></div>
			</div>
		</div>
		</div>
		<!-- end row -->


        <form name="form" role="form">
		<div class="card mb-3">
		<!-- card //-->
		
		<div class="card-header" role="tab" id="heading1">
		<div class="form-row">
			<div class="col-lg-12" data-toggle="collapse" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
				Informaci&oacute;n de Juicios
			</div>
		</div>
		</div>

		<div id="collapse1" class="collapse in" role="tabpanel" aria-labelledby="heading1" data-parent="#accordion">
		<div class="card-block">

			<div class="row">
				<div class="form-group col-sm-4" col>
					<label>Rut</label>
					<input type="text" placeholder="0181740639" class="form-control" id="idCliente" width="70px" tabindex="1" name="idCliente" 
						onkeyup="mostrar_idCliente('controlador/script_info_juicios.php?idCliente='+document.form.idCliente.value+'&opcion=4',document.form.idCliente.value);" 
						onKeyPress="return solonum_Rut(event);">
					<div style="background-color:#FFF;" id="cliente"></div>
				</div>
				<div class="form-group col-sm-1">
					<label>Nro Juicio</label>
					<input type="text" placeholder="371904" class="form-control" id="numJuicio" readonly>
				</div>
				<div class="form-group col-sm-3">
					<a onClick="formatear('idJuzgado')" style="float: right">Reset</a>
					<label>Juzgado</label>
					<input type="text" placeholder="1JUZLARI" class="form-control" id="idJuzgado" width="70px" tabindex="1" name="idJuzgado" 
						onkeyup="mostrar_juzgados('controlador/script_info_juicios.php?idJuzgado='+document.form.idJuzgado.value+'&opcion=2',document.form.idJuzgado.value);" readonly>
					<div id="juzgados"></div>
					<input type="hidden" id="idenJuz" value="">
				</div>
				<div class="form-group col-sm-2">
					<a onClick="formatear('numExpe')" style="float: right">Reset</a>
					<label>Rol</label>
					<input type="text" class="form-control" id="numExpe" placeholder="c-23837-2016" readonly>
					<input class="form-control" type="hidden" id="informacion" value="">
				</div>
				<div class="form-group col-sm-2">
					<label>Fecha Demanda</label>
					<input class="form-control" type="text" id="fechaDem" readonly>
				</div>
			</div>
				
			<div class="row">
				<div class="form-group col-sm-12" align="right">
					<button type="button" class="btn btn-rounded btn-primary" onClick="guardar_info('guardardo_info')">Guardar</button>
				</div>
			</div>
			
			<div id="guardardo_info"></div>

		</div>
		</div>
	
		<!-- card //-->
		</div>

		<div class="card mb-3">
			<div class="card-header" role="tab" id="heading2" style="background: #FBFBB3">
				<div class="form-row">
					<div class="col-lg-12" data-toggle="collapse" href="#collapse2" aria-expanded="true" aria-controls="collapse1">
						Etapas Procesales
					</div>
				</div>
			</div>
			<div id="collapse2" class="collapse in" role="tabpanel" aria-labelledby="heading2" data-parent="#accordion">
				<div class="card-block">
					<div class="row">
						<div class="form-group col-sm-4" id="identificadorEtapa">

						</div>
						<div class="form-group col-sm-4" col>
							<label>Fecha Inicio</label>
							<div class="input-group">
								<input type="text" class="form-control" id="fechaInicio" placeholder="dd/mm/aaaa"><span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
								<input type="hidden" id="idenJui" value="">
							</div>
						</div>
						<div class="form-group col-sm-4" col>
							<label>Fecha Fin</label>
							<div class="input-group">
								<input type="text" class="form-control" id="fechaFin" placeholder="dd/mm/aaaa"><span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card mb-3">
			<div class="card-header" role="tab" id="heading3" style="background: #C4FBB5">
				<div class="form-row">
					<div class="col-lg-12" data-toggle="collapse" href="#collapse3" aria-expanded="true" aria-controls="collapse1">
						200 Gestiones
					</div>
				</div>
			</div>
			<div id="collapse3" class="collapse in" role="tabpanel" aria-labelledby="heading3" data-parent="#accordion">
				<div class="card-block">
					<div class="row">
						<div class="form-group col-sm-6" col>
								<label>Fecha</label>
								<div class="input-group">
									<input type="text" class="form-control" id="fecha" placeholder="dd/mm/aaaa"><span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
								</div>
						</div>
						<div class="form-group col-sm-6">
								<label>C&oacute;digo de Acci&oacute;n</label>
								<input type="text" class="form-control" id="desCodA" onkeyup="mostrar_codigos_resultados('controlador/script_200_gestion.php?desCodA='+document.form.desCodA.value+'&opcion=1',document.form.desCodA.value);" placeholder="AB">
								<div id="codigo_accion"></div>
								<input type="hidden" id="codA" value="">
						</div>
					</div>
					<div class="row">
						<div id="codigoResultado" class="form-group col-sm-6">
							<label>C&oacute;digo Resultado</label>
							<select class="form-control" id="codR" name="" onchange="pasarCodR()">
										<option value="defecto">Seleccione</option>
							</select>
						</div>
						<div class="form-group col-sm-6">
							<label>Comentario</label>
							<input type="text" class="form-control" id="comen" placeholder="">
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card mb-3">
			<div class="card-header" role="tab" id="heading4" style="background: #F8E4BB">
				<div class="form-row">
					<div class="col-lg-12" data-toggle="collapse" href="#collapse4" aria-expanded="true" aria-controls="collapse1" onClick="identificadorJuicio()">
						Gastos Judiciales
					</div>
				</div>
			</div>
			<div id="collapse4" class="collapse in" role="tabpanel" aria-labelledby="heading4" data-parent="#accordion">
				<div class="card-block">
					<div class="row">
						<div class="form-group col-sm-6" id="identificadorEt">
								<label>Identificador de la Etapa</label>
								<select class="form-control" id="identEtapa" name="identEtapa">
										<option value="sel">Seleccione</option>
								</select>
						</div>
						<div class="form-group col-sm-6">
								<label>Nombre Del Receptor</label>
								<input type="text" class="form-control" id="proveedor">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-6">
							<label>N&uacute;mero de Factura</label>
							<input type="text" class="form-control" id="numFactura">
						</div>
						<div class="form-group col-sm-6" col>
							<a onClick="formatear('descGasto')" style="float: right">Reset</a>
							<label>Descripci&oacute;n del Gasto</label>
							<input type="text" placeholder="Embargo con Fuerza Publica (Incluye gastos de desarrajamiento y otros)" class="form-control" id="descGasto" width="70px" tabindex="1" name="descGasto" onkeyup="mostrar_desc_gasto('controlador/script_gastos.php?descGasto='+document.form.descGasto.value+'&opcion=4',document.form.descGasto.value);" onKeyPress="return soloLetras(event)">
							<div id="gastos"></div>
							<input type="hidden" id="tipoGasto" value="">
							<input type="hidden" id="subtipoGasto" value="">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-6">
							<label>Fecha de Diligencia</label>
							<div class="input-group">
								<input type="text" class="form-control" id="fechaAuto" placeholder="dd/mm/aaaa"><span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
							</div>
						</div>
						<div class="form-group col-sm-6" col>
							<label>Monto del Gasto</label>
							<input type="text" class="form-control" id="montoGas" placeholder="">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-6">
							<label>Descripci&oacute;n de Factura</label>
							<input type="text" class="form-control" id="desFact" placeholder="Descripcion">
						</div>
					</div>

				</div>

			</div>
		</div>

		<div class="row" align="center">
			<div class="form-group col-sm-12">
			<button type="button" class="btn btn-rounded btn-primary" onClick="guardar_datos('respuestaGuardado')" id="guardar2">Guardar</button>
			</div>
		</div>
		<div id="respuestaGuardado"></div>

		</form>

    </div> <!-- container -->
    </div> <!-- content -->

</div>
<!-- End content-page -->

<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php require 'includes/footer_start.php' ?>

<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js"></script>
<script>
	$(document).ready(function() {
		$("#fechaInicio, #fechaFin, #fecha, #fechaAuto").datepicker({ weekStart: 1, changeMonth: true, changeYear: true, dateFormat: 'dd/mm/yy', autoclose: true, language:'es' });
	});
</script>

<!-- extra js -->
<?php require 'includes/footer_end.php' ?>
