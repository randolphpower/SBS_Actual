<?php
require 'includes/header_start.php';

include("modelo/consultaSQL.php");

//<!-- extra css -->
require 'includes/header_end.php';

?>
<script type="text/javascript" src="js/ajax_usuario.js"></script>
<script type="text/javascript" src="js/validador.js"></script>

<script type="text/javascript">

function importar(){	
	
		//var tipo_doc = new Number (document.form.tipo_doc.value);
		var divResultado=document.getElementById("div_muestra");
		var archivo = document.form.Archivo_up.value;
		var empresa = document.form.empresa.value;
	
		// Usar la expresion regular para reemplazar el contenido extra con un espacio en blanco
		var filepath = document.getElementById("Archivo_up").value;
		var filenameWithExtension = filepath.replace(/^.*[\\\/]/, '');
		var extension = filenameWithExtension.substr( (filenameWithExtension.lastIndexOf('.') +1) );
    	
		if(archivo==""){
			alert("¡ERROR! Debe seleccionar un archivo");
			document.getElementById("Archivo_up").focus();
			return;
		}
	
		if(extension!="xlsx"){
			alert("¡ERROR! La extensión del archivo debe ser *.xlsx");
			document.getElementById("Archivo_up").value="";
			document.getElementById("Archivo_up").focus();
			return;
		}
	
		if(empresa==0){
			alert("¡ERROR! Debe seleccionar una empresa.");
			document.getElementById("empresa").focus();
			return;
		}
		
	
		if(archivo!="" && extension=="xlsx" && empresa>0){
			$(".botones").prop('disabled', true);
		//información del formulario
        var formData = new FormData($(".formulario")[0]);
        //hacemos la petición ajax  
        $.ajax({
            url: 'controlador/script_custodia.php?empresa='+document.form.empresa.value+"&opcion=1",  
            type: 'POST',
            // Form data
            //datos del formulario
            data: formData,
            //necesario para subir archivos via ajax
            cache: false,
            contentType: false,
            processData: false,
            //mientras enviamos el archivo
            beforeSend: function(){
				var message = "<span class='alert alert-warning alert-dismissible' role='alert' >Espere mientras se realiza la carga...</span>";   
				divResultado.innerHTML = message;
            },
            //una vez finalizado correctamente
            success: function(data) {

				alert("¡Bien hecho! Fueron cargados los registros exitosamente.");
				$(".botones").prop('disabled', false);
				location.href="comparar_arch_custodia.php";
				
            },
            //si ha ocurrido un error
            error: function(){
                alert("Ha ocurrido un error. Intente de nuevo...");
				$(".botones").prop('disabled', false);
            }
        });
		
	   }//Fin validar input de File
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
						<div class="card-header">Datos de Carga</div>
						<div class="card-block">
							
                       		<form name="form" role="form" enctype="multipart/form-data" class="formulario">
                       			<div class="row">
                       				<div class="form-group col-sm-8">
                       					<label>Seleccione el archivo *.xlsx</label>
                       					<input type="file" class="form-control" name="archivo" id="Archivo_up" >
                       				</div>
                       				<div class="form-group col-sm-2">
                       					<label>Empresa</label>
                       					<select class="form-control" id="empresa" name="empresa">
                       						<option value="0">Seleccione</option>
                       						<option value="1">Falabella</option>
                       						<option value="2">Caja Los Andes</option>
                       						<option value="3">Caja Los Heroes</option>
                       					</select>
                       				</div>
                       				<div class="form-group col-sm-2">
                       					<label>Subir</label>
                       					<button type="button" class="form-control btn btn-info botones" onClick="importar()"><i class="zmdi zmdi-mail-send"></i></button>
                       				</div>
                       			</div>
                       			<div class="row">
                       				<div class="col-sm-12">
                       					<div id="div_muestra"></div>
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
<style>
/* Center the loader */
#loader {
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 1;
  width: 150px;
  height: 150px;
  margin: -75px 0 0 -75px;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Add animation to "page content" */
.animate-bottom {
  position: relative;
  -webkit-animation-name: animatebottom;
  -webkit-animation-duration: 1s;
  animation-name: animatebottom;
  animation-duration: 1s
}

@-webkit-keyframes animatebottom {
  from { bottom:-100px; opacity:0 } 
  to { bottom:0px; opacity:1 }
}

@keyframes animatebottom { 
  from{ bottom:-100px; opacity:0 } 
  to{ bottom:0; opacity:1 }
}

#myDiv {
  display: none;
  text-align: center;
}
</style>
<div id="loader" style="display:none;"></div>