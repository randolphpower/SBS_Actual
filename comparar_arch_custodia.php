<?php
require 'includes/header_start.php';

include("modelo/conectarBD.php");
include("modelo/consultaSQL.php");
require_once("controlador/script_general.php");

$sql_consulta=$var_select." a.*, b.NOMBRE_EMPRESA ".$var_from." custodia_up_info a, empresas_afiliadas b ".$var_where."(a.ESTADO=0) ".$var_and."(a.ID_EMPRESA=b.ID) ".$var_order_by."FECHA ".$var_desc;
$datos = array();
$datos = call_select($sql_consulta,"");


//<!-- extra css -->
require 'includes/header_end.php';

?>
<script type="text/javascript" src="js/ajax_usuario.js"></script>
<script type="text/javascript" src="js/validador.js"></script>

<script type="text/javascript">
	
function guardar_vinculacion_arch(){
	var selector_pagare=document.getElementById("selector").value;
	var nombre_arch=document.getElementById("nombre_arch").value;
	var direc_url=document.getElementById("direc_url").value;
    // Recorremos todos los valores del radio button para encontrar el
    // seleccionado
	if(selector_pagare==0){
		alert("¡ERROR! Debe seleccionar una opcion valida.");
		document.getElementById("selector").focus();
		return;
	}
	
	$(".botones").prop('disabled', true);
	
		ajax=objetoAjax();
		ajax.open("GET", 'controlador/script_custodia.php?selector='+selector_pagare+"&direc_url="+direc_url+"&nombre_arch="+nombre_arch+'&opcion=3');
		ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {

					var resp = ajax.responseText;
					if(resp==1){
						buscar();
						alert("¡Bien hecho! Vinculación de archivo PDF realizada satisfactoriamente.");
						$('#exampleModal').modal('hide');
						
					}
				}
		}
		ajax.send(null)
	
	
}


function buscar(){
	
	var direc = document.form.direc.value;
	var num_carga = document.form.num_carga.value;
	
	if(direc==""){
		alert("¡ERROR! Ingrese la direccion de la carpeta a donde desea buscar");
		document.getElementById("direc").focus();
		return;
	}
	
	if(num_carga==""){
		alert("¡ERROR! Debe haber como minimo una carga de registros.");
		document.getElementById("num_carga").focus();
		return;
	}

	$(".botones").prop('disabled', true);
	
		ajax=objetoAjax_Global();
		ajax.open("GET", 'controlador/script_custodia.php?direc='+direc+'&num_carga='+num_carga+'&opcion=2');
		ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {

					document.getElementById("muestra_result").innerHTML = ajax.responseText;
					document.getElementById("resultados_busqueda").style.display="block";
					$(".botones").prop('disabled', false);
				}
		}
		ajax.send(null)
	
}
	
function almacenar_carga_final(id_carga){
	
	if(confirm("Desea almacenar la operación actual?")){
	
	if(id_carga=="" || id_carga=="0"){
		alert("¡ERROR! hubo un problema a la hora de almacenar los registros. Actualice la busqueda e intente de nuevo...");
		return;
	}
	
	$(".botones").prop('disabled', true);
	$(".boton_guardar").prop('disabled', true);
	
		ajax=objetoAjax_Global();
		ajax.open("GET", 'controlador/script_custodia.php?num_carga='+id_carga+'&opcion=4n');
		ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {

					var resp = ajax.responseText;
					
					if(resp==1){
						alert("¡Bien hecho! La operación fue almacenada satisfactoriamente.");
						setInterval(function(){ $(location).attr('href','comparar_arch_custodia.php'); } , 1000);
					
					}else if(resp==2){
						alert("¡ATENCION! Hubo un problema al momento de guardar la operación. Intente de nuevo...");
						$(".botones").prop('disabled', false);
						$(".boton_guardar").prop('disabled', true);
					}
					
				}
		}
		ajax.send(null)
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
                        <h1 class="page-title">Custodia</h1>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            
            <div class="row">
            	<div class="col-sm-12">
					<div class="card">
						<div class="card-header">Comparar data con Archivos</div>
						<div class="card-block">
							
                       		<form name="form" role="form" >
                       			<div class="row">
                       				<div class="form-group col-sm-5">
                       					<label>Ubicaci&oacute;n del Archivo</label>
                       					<input type="text" class="form-control" name="direc" id="direc" >
                       					<span class="font-weight-light" style="font-size: 11px;">Ejm: pagare</span>
                       				</div>
                       				
                       				<div class="form-group col-sm-6">
                       					<label>Datos de cargas pendientes</label>
                       					<select class="form-control" id="num_carga" name="num_carga">
                       					<?php 
											 while($result=mysql_fetch_array($datos['registros'])){ 
											 	echo "<option value='".$result['ID']."' >Fecha: ".fecha_convierte_a_normal($result['FECHA'])." - Empresa: ".utf8_encode($result['NOMBRE_EMPRESA'])." - Num. Reg: ".$result['CANTIDAD_REGISTROS']."</option>";
                                        	} 
										?>
                       					</select>
                       				</div>
                       				
                       				<div class="form-group col-sm-1">
                       					<label>Buscar</label>
                       					<button type="button" class="form-control btn btn-info botones" onClick="buscar()" ><i class="zmdi zmdi-search"></i></button>
                       				</div>
                       			</div>
                       			
                       		</form>
                       			
						</div>
					</div>
				</div>
            </div>
            
            <!-- end row -->
            
             <div class="row" id="resultados_busqueda" style="display: none;">
            	<div class="col-sm-12">
            		<div id="muestra_result"></div>
				</div>
            </div>
            
            <!-- end row -->
				
        </div> <!-- container -->

    </div> <!-- content -->

</div>
<!-- End content-page -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
      </div>
      <div class="modal-body" style="height: 100%">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success botones"  id="guardar_vinc_arch" onClick="guardar_vinculacion_arch()">Guardar <i class="zmdi zmdi-edit zmdi-hc-lg"></i></button>
		<button type="button" class="btn btn-default botones" data-dismiss="modal">Cerrar <i class="zmdi zmdi-close-circle-o zmdi-hc-lg"></i></button>
      </div>
    </div>
  </div>
</div>


<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php require 'includes/footer_start.php' ?>

<script>

	$('#exampleModal').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget) // Button that triggered the modal
	  var recipient = button.data('whatever') // Extract info from data-* attributes
	  var datos = recipient.split("|");
	  var id_custodia=datos[0];
	  var direc=datos[1];
	  var nombre_arch=datos[2];
		
	  var modal = $(this);
	  modal.find('.modal-title').text('Identifique PDF - '+datos[2]);
	  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
	  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
	  //$("#id_pdf").attr("src",datos[0]+"#toolbar=0");
	  
		ajax=objetoAjax_Global();
		ajax.open("GET", 'controlador/script_custodia.php?id_custodia='+id_custodia+'&direc='+direc+'&nombre_arch='+nombre_arch+'&opcion=5');
		ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {

					modal.find('.modal-body').html(ajax.responseText);
					
				}
		}
		ajax.send(null)
		
		
	});

</script>
<!-- extra js -->
<?php require 'includes/footer_end.php' ?>
