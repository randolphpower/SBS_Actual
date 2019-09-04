<?php
session_start();
require_once("modelo/conectarBD.php");
require_once("modelo/consultaSQL.php");

//echo $_GET["direc"];
$id_custodia=$_GET["id_custodia"];

$sql_consulta=$var_select_asterisk_from."custodia_up ".$var_where."(ID='".$id_custodia."')";
$datos = array();
$datos = call_select($sql_consulta,"");
$result=mysql_fetch_array($datos['registros']);

$direc=$_GET["direc"];
$direc_real = substr($direc, 3);  
$id_custodia_info=$result["ID_CUSTODIA_INFO"];
$id_custodia_referencia=$result["ID_REFERENCIA"];
$id_custodia_pagare=$result["NRO_PAGARE_ORIGINAL"];
$id_custodia_pagare_alt=$result["NRO_PAGARE_ALTERADO"];
$id_custodia_rut_comp=$result["RUT_COMPLETO"];
$id_custodia_rut_sin_dv=$result["RUT_SIN_DV"];
$id_custodia_dgv_rut=$result["DV_RUT"];
$id_custodia_nombre=$result["NOMBRE"];
$id_custodia_direccion=$result["DIRECCION"];
$id_custodia_comuna=$result["COMUNA"];
$id_custodia_distrito=$result["DISTRITO"];
$id_custodia_estado=$result["ESTADO"];
$id_custodia_url=$result["URL"];

//`ID_CUSTODIA_INFO`, `ID_REFERENCIA`, `NRO_PAGARE_ORIGINAL`, `NRO_PAGARE_ALTERADO`, `RUT_COMPLETO`, `RUT_SIN_DV`, `DV_RUT`, `NOMBRE`, `DIRECCION`, `COMUNA`, `DISTRITO`, `ESTADO`, `URL`

$arreglo_pend1 = array();
$directorio = opendir($direc_real); //ruta actual
while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
    if ((!is_dir($archivo)) && (pathinfo( strtolower($archivo), PATHINFO_EXTENSION )=="pdf"))//verificamos si es o no un directorio
    {
     	$cadena=substr($archivo,0,-4);   
		
		$sql_consulta=$var_select_asterisk_from."custodia_up ".$var_where."(ID_CUSTODIA_INFO='".$id_custodia_info."') ".$var_and."(NRO_PAGARE_ALTERADO='".$cadena."')";
		$datosx = array();
		$datosx = call_select($sql_consulta,"");
		
		if($datosx["num_registros"]!=1){
			$nodo["nombre"]=$archivo;
			$nodo["ruta"]=$direc_real.$archivo;
			$arreglo_pend1[]=$nodo;
			unset($nodo);
		}		
		unset($datosx);

    }
}

//var_dump($arreglo_pend1);
//exit();

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ServiCobranzas</title>
 <!-- App Favicon -->
<link rel="icon" href="images/logo2.ico">
<!-- Switchery css -->
<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
<script type="text/javascript" src="js/ajax.js"></script>

<!-- App CSS -->
<link href="assets/css/style.css" rel="stylesheet" type="text/css" />
<script>

function guardar_vinculacion_arch(){
	var radio=document.getElementsByName("radio");
	var id_custodia=document.getElementById("id_custodia").value;
	var direc_url=document.getElementById("direc_url").value;
    // Recorremos todos los valores del radio button para encontrar el
    // seleccionado
    for(var i=0;i<radio.length;i++)
    {
    	if(radio[i].checked)
        	var resultado = radio[i].value;
    }
	
	$(".botones").prop('disabled', true);
	
		ajax=objetoAjax();
		ajax.open("GET", 'controlador/script_custodia.php?nombre='+resultado+"&id="+id_custodia+"&direc_url="+direc_url+'&opcion=3');
		ajax.onreadystatechange=function() {
                if (ajax.readyState==4) {

					var resp = ajax.responseText;
					if(resp==1){
						alert("¡Bien hecho! Vinculación de archivo PDF realizada satisfactoriamente.");
						window.close();
					}
				}
		}
		ajax.send(null)
	
	
}
	
function cambia_estado_radio_g(){
	document.getElementById("guardar_vinc_arch").disabled=false;
}

</script>
</head>

<body>

<input type="hidden" id="id_custodia" value="<?php echo $id_custodia ?>">
<input type="hidden" id="direc_url" value="<?php echo $direc_real ?>">

    <!-- Start content -->
    <div class="content">
        <div class="container">
		<br>
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h1 class="page-title">Identifique PDF</h1>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            
             <div class="row">
            	<div class="col-sm-12">
					<div class="card">
						<div class="card-header">Datos del Pagare</div>
						<div class="card-block">
							<table class="table table-hover table-striped small">
								<thead>
								<tr>
									<th>ID</th>
									<th>Nro. de Pagare</th>
									<th>Rut</th>
									<th>dv</th>
									<th>Nombre</th>
									<th>Direcci&oacute;n</th>
									<th>Comuna</th>
									<th>Distrito</th>
								</tr>
							   </thead>   
							   <tbody>
								<tr>
									<td><?php echo $id_custodia_referencia; ?></td>
									<td><?php echo $id_custodia_pagare; ?></td>
									<td><?php echo $id_custodia_rut_sin_dv; ?></td>
									<td><?php echo $id_custodia_dgv_rut; ?></td>
									<td><?php echo $id_custodia_nombre; ?></td>
									<td><?php echo $id_custodia_direccion; ?></td>
									<td><?php echo $id_custodia_comuna; ?></td>
									<td><?php echo $id_custodia_distrito; ?></td>
								</tr>
							   </tbody>
							</table>
						</div>
					</div>
				</div>
            </div>
            
            <!-- end row -->
            
            <div class="row">
            	<div class="col-sm-12">
					<div class="card">
						<div class="card-header">Seleccione el Archivo </div>
						<div class="card-block">
							
                       		<form name="form" role="form" >
                       			
                       			<?php 
								$i=0;
								while($i<count($arreglo_pend1)){ ?>
                       			<div class="row">
                       				<div class="form-group col-sm-11">
                       					<label><?php echo $arreglo_pend1[$i]["nombre"]; ?></label>
                       					<embed class="form-control" src="<?php echo $arreglo_pend1[$i]["ruta"] ?>#toolbar=0" type="application/pdf" width="600" height="400"></embed>
<br>
                       				</div>
                       				
                       				<div class="form-group col-sm-1">
                       					<label class="form-check-label">&nbsp;</label>
										<div class="form-check">
										  <input class="form-check-input" title="<?php echo $arreglo_pend1[$i]["nombre"]; ?>" type="radio" name="radio" onChange="cambia_estado_radio_g()" id="radio<?php echo $i ?>" value="<?php echo $arreglo_pend1[$i]["nombre"] ?>">
										 
										</div>
                       				</div>
                       			</div>
                       			<?php 
								$i++;							   
								} ?>
                       		</form>
                       			
						</div>
					</div>
				</div>
            </div>
            
            <!-- end row -->
            
            <div class="row">
            	<div class="col-lg-12" align="center">
					<div class="form-group">
						<button type="button" class="btn btn-success botones"  id="guardar_vinc_arch" onClick="guardar_vinculacion_arch()" disabled>Guardar <i class="zmdi zmdi-edit zmdi-hc-lg"></i></button>
						<button type="button" class="btn btn-default botones" onClick="window.close()">Cerrar <i class="zmdi zmdi-close-circle-o zmdi-hc-lg"></i></button>
					</div>                    
				</div>
            </div>
            
            <!-- end row -->
            
            
				
        </div> <!-- container -->

    </div> <!-- content -->



<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>

</body>
</html>




