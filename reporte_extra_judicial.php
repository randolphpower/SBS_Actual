<?php 

	require 'includes/header_start.php';
	include("modelo/conectarBD.php");
	include("modelo/consultaSQL.php");
	$mysqli = new mysqli($host, $usuario, $password, $basedatos);
    $sql = "SELECT distinct nom_accion,cod_accion FROM vcdials where nom_vcdial <> 'Seleccione' ";
    
    $resultado = $mysqli->query($sql);
   
    
    //echo $resultado;


?>
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<!-- DataTables -->
<link href="assets/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<!-- Responsive datatable examples -->
<link href="assets/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script type="text/javascript">

	//funcion_guarda_info('controlador/script_reporte.php?fechaIn='+document.form.min.value+'&fechaFin='+document.form.max.value+'&opcion=1','tabla_reporte')
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

            <div class="row">
                <div class="col-xs-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Reporte Extra Judicial</h4>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-lg-4">
                            
                                <div class="form-group">
                                    <label for="accion">Fecha Desde - Hasta</label>
                                    <input type="text" name="daterange" class="form-control" value="<?php echo date('dd-MM-Y') - date('d-m-Y')?>'" />
                                </div>
                                <div class="form-group">
                                    <label for="accion">Accion</label>
                                    <select name="accion" id="accion" class="form-control">
                                        <option value="" disabled selected>-- SELECCIONE -- </option>
                                        <?php	while($row  = $resultado->fetch_assoc()){ ?>
                                        <option value="<?php echo $row['cod_accion']; ?>"><?php echo $row['nom_accion'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div id="contenido">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="button" id="btn_generar_excel" value="Generar" class="form-control">
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

<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->

<?php require 'includes/footer_start.php' ?>

<!-- Required datatable js -->
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
    <script src="assets/plugins/datatables/jszip.min.js"></script>
    <script src="assets/plugins/datatables/pdfmake.min.js"></script>
    <script src="assets/plugins/datatables/vfs_fonts.js"></script>
    <script src="assets/plugins/datatables/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatables/buttons.print.min.js"></script>
    <script src="assets/plugins/datatables/buttons.colVis.min.js"></script>
    <!-- Responsive examples -->
    <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatables/responsive.bootstrap4.min.js"></script>
    <script src="assets/js/sweetalert.min.js"></script>
                                        
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript">
        
        $(document).ready(function () {
    
            $("#accion").change(function(){
                $("#contenido").html("");
                //alert($("#accion").val());
                $.ajax({
                    method: "POST",
                    url: "reporte_extra_judicial_ajax.php",
                    data: { accion : $("#accion").val() }
                }).done(function( msg ) {
                    //alert(msg);
                    $("#contenido").html('<div class="form-group"><label for="respuesta">Respuesta</label><select name="respuesta" id="respuesta" class="form-control">'+msg+'</select></div>');
                });
	
            });
        });

        $("#btn_generar_excel").click(function(){
            var fecha = $('input[name="daterange"]').val();
            var fechaArr =  fecha.split("-");
            
            var respuesta = $("#respuesta").val();
            var accion = $("#accion").val();
            var min = fechaArr[0].trim();
            var max = fechaArr[1].trim();

            console.log(min);
            
            $.ajax({
                method: "GET",
                url: "reporte_extra_judicial_ajax_validacion.php",
                data: { accion : $("#accion").val(),
                        respuesta : $("#respuesta").val(),
                        min : fechaArr[0].trim(),
                        max: fechaArr[1].trim()
                    }
            }).done(function(msg){
                //console.log(msg);
                if(msg >= 1){
                    window.location.href = 'get_excel_carga_extra_judiciales.php?accion='+accion+'&respuesta=' + respuesta + '&min=' + encodeURIComponent(min) + '&max=' + encodeURIComponent(max);                 
                }else{
                    swal({
                        title: "No hay resultado!",
                        text: "Por favor, cambie datos entrantes!",
                        type: "warning",
                      
                        });
                   
                }

            });
            
        });
    </script>

    <script>
        $(function() {
                $('input[name="daterange"]').daterangepicker({
                    opens: 'right',
                    locale: {
                        format: 'DD/MM/YYYY'
                    }
                }, function(start, end, label) {
                    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                });
            });
    </script>

<?php require 'includes/footer_end.php' ?>