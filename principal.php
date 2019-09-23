<?php
require 'includes/header_start.php';

include("modelo/consultaSQL.php");

//<!-- extra css -->
require 'includes/header_end.php';

//LLamada para ir a buscar los datos de la tabla op_info_juicios.

$datosInfoJuicios=array();
$datosInfoJuicios=call_select($var_select_asterisk_from."op_info_juicios ".$var_where."CESENDDT = 0 AND USUSUARIO ='".$_SESSION['username']."'","");
	
//Llamada para ir a buscar los datos de la tabla op_eta_proce	
$datosEtaProce=array();
$datosEtaProce=call_select($var_select."a.*, b.CT_DESC, c.CD_DESC ".$var_from."op_eta_proce a, tipos_juicio b, etapas_procesales c ".$var_where."a.CSSNDT = 0 AND a.USUSUARIO ='".$_SESSION['username']."' AND (a.CSTYPE=b.CT_TYPE) AND (a.CSTYPE=c.CD_TYPE) AND (a.CSSTGID=c.CD_STGID)","");
	
//Llamada para ir a buscar los datos de la tabla op_200_gestiones
$datos200Gest=array();
$datos200Gest=call_select($var_select_asterisk_from."op_200_gestiones ".$var_where."ACSNDATE= 0 AND USUSUARIO ='".$_SESSION['username']."'","");
	
//Llamada para ir a buscar los datos de la tabla op_gastos
$datosGastos=array();
$datosGastos=call_select($var_select_asterisk_from."op_gastos ".$var_where."EXSENDDT = 0 AND USUSUARIO ='".$_SESSION['username']."'","");	

?>

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
                        <h1 class="page-title">Bienvenido</h1>
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
									<?php

										// echo "<pre>";
										// echo $var_select_asterisk_from."op_info_juicios ".$var_where."CESENDDT = 0 AND USUSUARIO ='".$_SESSION['username']."'";
										// echo "</pre>";

									?>
                        			<table width="100%" class="table table-striped table-bordered table-hover">
										<tr>
											<th>Identificador</th>
											<th>Nro Expediente</th>
											<th>Nro Juicio</th>
											<th>ID Cliente</th>
											<th>Juzgado</th>
											<th>Fecha Demanda</th>
											<th>Fecha envio</th>
											<th></th>
										</tr>
									<?php 
										while($resInfoJuicios=mysql_fetch_array($datosInfoJuicios['registros'])){
										?>
											<tr class="text-center text-muted" data-placement="top">
												<td><?php echo $resInfoJuicios["IDENTIFICADOR"]?></td>
												<td><?php echo $resInfoJuicios["CEDOSSIERID"]?></td>
												<td><?php echo $resInfoJuicios["CNCASENO"]?></td>
												<td><?php echo $resInfoJuicios["CESSNUM"]?></td>
												<td><?php echo $resInfoJuicios["CECRTID"]?></td>
												<td><?php echo $resInfoJuicios["CELWSTDT"]?></td>
												<td><?php echo $resInfoJuicios["CESENDDT"]?></td>
												<td><button class="btn btn-danger" onClick="borrarRegistroInfo('controlador/script_info_juicios.php?id='+<?php echo $resInfoJuicios["id"]?>+'&opcion=8')"><span class="fa fa-minus-circle"></span></button></td>
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

			<div class="row">
            	<div class="col-sm-12">
					<div class="card">
						<div class="card-header" style="background: #FBFBB3">Reporte de Etapas Procesales</div>
						<div class="card-block">
							<div class="table-responsive">
									<?php

									// echo "<pre>";
									// echo $var_select."a.*, b.CT_DESC, c.CD_DESC ".$var_from."op_eta_proce a, tipos_juicio b, etapas_procesales c ".$var_where."a.CSSNDT = 0 AND a.USUSUARIO ='".$_SESSION['username']."' AND (a.CSTYPE=b.CT_TYPE) AND (a.CSTYPE=c.CD_TYPE) AND (a.CSSTGID=c.CD_STGID)";
									// echo "</pre>";

									?>
                        			<table width="100%" class="table table-striped table-bordered table-hover">
										<tr>
											<th>Nro Juicio</th>
											<th>Tipo Caso</th>
											<th>Identificador de la Etapa</th>
											<th>Fecha Inicio</th>
											<th>Fecha Fin</th>
											<th>Fecha envio</th>
											<th></th>
										</tr>
									<?php
										
										while($resEtaProce=mysql_fetch_array($datosEtaProce['registros'])){
										?>
											<tr class="text-center text-muted" data-placement="top">
												<td><?php echo $resEtaProce["CSCASENO"]?></td>
												<td><?php echo $resEtaProce["CT_DESC"]?></td>
												<td><?php echo $resEtaProce["CD_DESC"]?></td>
												<td><?php echo $resEtaProce["CSSTDT"]?></td>
												<td><?php echo $resEtaProce["CSENDDT"]?></td>
												<td><?php echo $resEtaProce["CSSNDT"]?></td>
												<td><button class="btn btn-danger" onClick="borrarRegistroProce('controlador/script_eta_procesales.php?id='+<?php echo $resEtaProce["id"]?>+'&opcion=6')"><span class="fa fa-minus-circle"></span></button></td>
											</tr>											
										<?php   
										}

									 ?>
									</table>
                       			</div>
                       			
                       			<div class="col-lg-6" align="center">
									 <div id="etapas_procesales"></div>
								</div>
                       			
						</div>
					</div>
				</div>
            </div>
            
            <!-- end row -->
            
            <div class="row">
            	<div class="col-sm-12">
					<div class="card">
						<div class="card-header"  style="background: #C4FBB5">Reporte "200" Gestiones</div>
						<div class="card-block">
							<div class="table-responsive">
									<?php

									// echo "<pre>";
									// echo $var_select_asterisk_from."op_200_gestiones ".$var_where."ACSNDATE= 0 AND USUSUARIO ='".$_SESSION['username']."'";
									// echo "</pre>";

									?>						
                        			<table width="100%" class="table table-striped table-bordered table-hover">
										<tr>
											<th>Constante</th>
											<th>ID del Juicio</th>
											<th>ID Agente</th>
											<th>Fecha</th>
											<th>Fecha envio</th>
											<th></th>
										</tr>
									<?php 
										while($res200Gest=mysql_fetch_array($datos200Gest['registros'])){
										?>
											<tr>
												<td><?php echo $res200Gest["CONSTANTE"]?></td>
												<td><?php echo $res200Gest["ACACCT"]?></td>
												<td><?php echo $res200Gest["ACCIDMAN"]?></td>
												<td><?php echo $res200Gest["DATE"]?></td>
												<td><?php echo $res200Gest["ACSNDATE"]?></td>
												<td><button class="btn btn-danger" onClick="borrarRegistro200('controlador/script_200_gestion.php?id=+<?php echo $res200Gest['id']; ?>+&opcion=6')"><span class="fa fa-minus-circle"></span></button></td>
											</tr>
										<?php   
										}

									 ?>
									</table>
							</div>
							
							<div class="col-lg-6" align="center">
								 <div id="act_op_cheq"></div>
							</div>
							
						</div>
					</div>
				</div>
            </div>
            
            <!-- end row -->
            
            <div class="row">
            	<div class="col-sm-12">
					<div class="card">
						<div class="card-header" style="background: #F8E4BB">Reporte de Gastos Judiciales</div>
						<div class="card-block">
							<div class="table-responsive">
									<?php

									// echo "<pre>";
									// echo "SELECT * FROM op_gastos WHERE EXSENDDT = 0";
									// echo "</pre>";

									?>				
                        			<table width="100%" class="table table-striped table-bordered table-hover">
										<tr>
											<th>Nro Juicio</th>
											<th>Tipo Caso</th>
											<th>Proveedor</th>
											<th>Nro de Factura</th>
											<th>Fecha Autorizacion</th>
											<th>Fecha envio</th>
											<th></th>
										</tr>
									<?php 
										while($resGastos=mysql_fetch_array($datosGastos['registros'])){
										?>
											<tr class="text-center text-muted" data-placement="top">
												<td><?php echo $resGastos["CSCASENO"]?></td>
												<td><?php echo $resGastos["CETYPE"]?></td>
												<td><?php echo $resGastos["EXSUPPLIER"]?></td>
												<td><?php echo $resGastos["EXINVOICE"]?></td>
												<td><?php echo $resGastos["EXAUTDT"]?></td>
												<td><?php echo $resGastos["EXSENDDT"]?></td>
												<td><button class="btn btn-danger" onClick="borrarRegistroGastos('controlador/script_gastos.php?id=+<?php echo $resGastos["id"]; ?>+&opcion=7')"><span class="fa fa-minus-circle"></span></button></td>
											</tr>
										<?php   
										}

									 ?>
									</table>
                       			</div>
                       			
                       			<!--<button type="button" class="btn btn-success" onClick="enviarGastos('../controlador/script_gastos.php?opcion=6');">
                          				<span class="glyphicon glyphicon-file"> Enviar</span>
                          		</button>-->
                          		<div class="col-lg-6" align="center">
									 <div id="gastos"></div>
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


<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php require 'includes/footer_start.php' ?>
<!-- extra js -->
<?php require 'includes/footer_end.php' ?>
