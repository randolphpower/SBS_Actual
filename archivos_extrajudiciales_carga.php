<?php 
	session_start();
	require 'includes/header_start.php'; 
	require 'includes/header_end.php'; 
	require_once("/modelo/consultaSQL.php");
	require_once("/modelo/conectarBD.php");
	require_once("/PHPExcel.php");
    require_once("controlador/script_general.php");
   $DIALSBDD = array();



	
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $finalizo = false;
    

    $tipo = $_FILES['file']['type'];
    $error = $_FILES['file']['error'];
    $name       = $_FILES['file']['name'];  
    $temp_name  = $_FILES['file']['tmp_name'];  
    //echo $tipo;
    //echo $temp_name;
    if (empty($temp_name)) { return; }
    $lineas = array();
    $fp = fopen($temp_name, "rb");
    while (!feof($fp)){
        array_push($lineas,fgets($fp));
    }
    fclose($fp);
    
    $matriz = array();
     
    echo $_SESSION['sesion_matriz'];
    $fech_filter = array();
        foreach ($lineas as $lineaarray){
        
            $parts = preg_split('/\t/', $lineaarray);
            if ($parts[0] == "lead_id" ||$parts[0] == "") continue;
            //$reg = llenartrans($parts);
            //array_push($matriz,$reg);
            if(!in_array(trim(substr($parts[2],0,10)), $fech_filter) && trim(substr($parts[2],0,10)) != '0000-00-00'){
                array_push($fech_filter,substr($parts[2],0,10));           
                //echo ".".trim(substr($parts[2],0,10)). ".<br>";             
            }   
            //echo $lineaarray . "<br>";
            $finalizo = true;
        }

    echo $lineas;
    $_SESSION['sesion_matriz'] = $lineas;  

    //GenerarPlano200($matriz, $conexion);
    //GenerarPlano600($matriz, $conexion);
    //GenerarPlano700($matriz, $conexion);
    //GenerarPlano800($matriz, $conexion);
    
}
    


    
?>
<style>
   #page-loader {
   position: absolute;
   top: 0;
   bottom: 0%;
   left: 0;
   right: 0%;
   background-color: white;
   z-index: 99;
   display: none;
   text-align: center;
   width: 100%;
   padding-top: 25px;
   }
</style>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->

<form name="form" action="?" method="post" enctype="multipart/form-data">
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h3 class="page-title">Archivos Extra Judiciales</h3>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <?php  if ($finalizo == false){    ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">Cargar Archivo</div>						
                                <div class="card-block">
                                    <div id="page-loader">
                                        <h3>Cargando Archivo Extra Judicial...</h3>
                                        <img src="./images/gif-load.gif" alt="loader">
                                        <h3>...por favor espere</h3>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                                <label>Archivo</label><br>
                                                <input type="file" id="file" name="file" /><br>
                                        </div>

                                    </div>
                            
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                                <button type="submit" class="btn btn-rounded btn-primary" id="subir">Subir</button>
                                                <button type="button" class="btn btn-rounded btn-danger" onClick="location='principal.php'">Volver</button>
                                        </div>
                                    </div>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>	
                </div>
                <?php }else{ ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">Fecha encontradas</div>						
                                    <div class="card-block">
                                        <div id="page-loader">
                                            <h3>Cargando Archivo Extra Judicial...</h3>
                                            <img src="./images/gif-load.gif" alt="loader">
                                            <h3>...por favor espere</h3>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                    <?php 
                                                    $con = 0;
                                                    foreach($fech_filter as $fech){

                                                        $con++;
                                                    ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input checkesito" type="checkbox" value="<?=$fech?>" name="fechas_check" id="defaultCheck<?=con?>">
                                                            <label class="form-check-label" for="defaultCheck<?=con?>">
                                                            <?php echo $fech; ?>
                                                            </label>
                                                        </div>

                                                    <?php 
                                                }
                                                //echo sizeof($_SESSION['sesion_matriz']);
                                                ?>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <button type="button" class="btn btn-rounded btn-primary" onclick="javascript:enviar();" >Enviar</button>
                                            </div>
                                        </div>                
                                    
                                        
                                        
                                    </div>
                                </div>
                            </div>
                        </div>	    
                    </div>
                <?php } ?>
                <?php if ($finalizo == true) { ?>
                  
                <?php } ?>
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
<script src="assets/js/sweetalert.min.js"></script>
<?php require 'includes/footer_end.php' ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#subir').click(function(e) {
            if (document.form.file.value == "") {
                alert('Debe seleccionar un archivo');
                return false;
            }
            document.getElementById('page-loader').style.display='block';
        });
   });
   function enviar(){
        //var fechas = document.getParameterByName("fechas_check");

        var fechas = ",";

        if($('[name="fechas_check"]').is(':checked')) {
            $('[name="fechas_check"]:checked').each(function(index){
            
            fechas = fechas+","+$(this).val();
            
            console.log(fechas);
            });
            fechas = fechas.replace(",,"," ");
            fechas = $.trim(fechas);
            
            $.ajax({
                type: "POST",
                url: "archivos_extrajudiciales_carga_ajax.php",
                data: { fechas : fechas},
                success: function() {
                    
                    swal({
                        title: "Bien hecho!",
                        text: "Su solicitud se realizo correctamente!",
                        type: "success",
                      
                        },
                        function(){
                            window.location = "archivos_extrajudiciales_carga.php";
                        });
                }
            });
            
        }else{
            alert('Debe seleccionar al menos una fecha');
        }
        /*
        
        */
   }


</script>