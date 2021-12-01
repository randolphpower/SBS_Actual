<?php 
   session_start();
   require 'includes/header_start.php'; 
   require 'includes/header_end.php'; 
   require_once("/modelo/consultaSQL.php");
   require_once("/modelo/conectarBD.php");
   require_once("/controlador/script_general.php");
   require_once("/PHPExcel.php");
   $tipo = $_FILES['archivo']['type'];
   $tamanio = $_FILES['archivo']['size'];
   $tmpfname = $_FILES['archivo']['tmp_name'];
   $fechaAsignacion = $_POST["fechaAsignacion"];
   $fechaAsignacion = split("/", $fechaAsignacion);
   $fechaAsignacion = "{$fechaAsignacion[2]}-{$fechaAsignacion[1]}-{$fechaAsignacion[0]}";
   if ($tmpfname != ""){
   	$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
   	$excelObj = $excelReader->load($tmpfname);
   	$sheetCount = $excelObj->getSheetCount();
   	$i = 0;
   	$instertados = 0;
   	$actualizados = 0;
   	for ($sheetRow = 0; $sheetRow <= $sheetCount-1; $sheetRow++) {				
   		$worksheet = $excelObj->getSheet($sheetRow);
   		$lastRow = $worksheet->getHighestRow();
   		
   		$tituloJuicio = $worksheet->getCell('A1')->getValue();
   		$tituloRut = $worksheet->getCell('C1')->getValue();
   		$tituloTipoJuicio = $worksheet->getCell('AA1')->getValue();
   
   		//echo "itera</br>";
   		for ($row = 2; $row <= $lastRow; $row++) {
   		
   			$id_juicio = $worksheet->getCell('A'.$row)->getValue();
   			$rut = $worksheet->getCell('B'.$row)->getValue();
			$nombre =str_replace("'","",$worksheet->getCell('C'.$row)->getValue());
			$nombre =str_replace("`","",$nombre);
			$nombre =str_replace("´","",$nombre);
   			$cuenta = $worksheet->getCell('D'.$row)->getValue();
   			$tipo_juicio = $worksheet->getCell('E'.$row)->getValue();
   			$fecha = $worksheet->getCell('F'.$row)->getValue();
   			$fecha = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('F'.$row)->getValue()));
   			$fecha = date('Y-m-d', strtotime($fecha. ' + 1 days'));
   			$monto = $worksheet->getCell('G'.$row)->getValue();
   			
   			if ($tipo_juicio != "") {
   				$sql_search = "SELECT id FROM juicios_dato_inicial WHERE id_juicio = ".$id_juicio." and tipo_juicio = '".$tipo_juicio."';";				
   				$datos = call_select($sql_search, "");			
   				$id_tabla = mysql_fetch_array($datos['registros'])['id'];
   				//echo $id_tabla."</br>";
   				if ($datos['num_filas'] == 0) { // INSERT
   					$sql = "INSERT INTO juicios_dato_inicial (id_juicio, tipo_juicio, rut, fecha_asignacion, cuenta, monto, nombre)  ";
   					$sql .= "VALUES (".$id_juicio.",'".$tipo_juicio."','".$rut."','".$fecha."','".$cuenta."','".$monto."','".$nombre."');";
   					call_insert($sql, "");
   
   					$arrnumjuicio[$i-1] = $id_juicio;
   					$arrrutcliente[$i-1] = $rut;
   					$arrcliente[$i-1] = $nombre;
   					$arrtipojuicio[$i-1] = $tipo_juicio;
   					$arrcuenta[$i-1] = $cuenta;
   					$arrmonto[$i-1] = $monto;
   					$arraccion[$i-1] = "INSERT";	
   					$arrfecha[$i-1] = $fecha;	
   					$instertados++;
   				} else { // UPDATE
   					$sql = "UPDATE juicios_dato_inicial SET ";
   					$sql .= "tipo_juicio='".$tipo_juicio."', ";				
   					$sql .= "rut='".$rut."', ";				
   					$sql .= "fecha_asignacion='".$fecha."', ";	
   					$sql .= "cuenta='".$cuenta."', ";				
   					$sql .= "monto='".$monto."', ";				
   					$sql .= "nombre='".$nombre."' ";									
   					$sql .= "WHERE (id='".$id_tabla."') ";
   					call_update($sql);
   
   					$arrnumjuicio[$i-1] = $id_juicio;
   					$arrrutcliente[$i-1] = $rut;
   					$arrcliente[$i-1] = $nombre;
   					$arrtipojuicio[$i-1] = $tipo_juicio;
   					$arrcuenta[$i-1] = $cuenta;
   					$arrmonto[$i-1] = $monto;
   					$arraccion[$i-1] = "UPDATE";		
   					$arrfecha[$i-1] = $fecha;	
   					$actualizados++;
   					}
   			}	
   			$i++;		
   		}						
   	}	
   
   	$print = "<table class='table table-striped table-bordered table-hover'>";
   	$print .= "<thead>";
   	$print .= "<tr align='center' class='info text-center text-default' style='vertical-align:middle'>";
   	$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Nro.</th>";
   	$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Identificador</th>";
   	$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rut Cliente</th>";
   	$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Cliente</th>";
   	$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Cuenta</th>";
   	$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tipo Juicio</th>";
   	$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Fecha</th>";
   	$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Monto</th>";
   	$print .= "</tr>";
   	$print .= "</thead>";
   	$print .= "<tbody>";
   
   	$j=1;
   	$count = count($arrnumjuicio);
   	//echo $count;
   	for ($i = 0; $i < $count; $i++) {
   		if ($arraccion[$i-1] == "INSERT"){
   			$color = "#C4FBB5";
   		}
   		else if ($arraccion[$i-1] == "UPDATE"){
   			$color = "#F8E4BB";
   		}
   		$print .= "<tr class='text-center text-muted' data-placement='top' style='background-color:".$color."'>";
   		$print .= "<td class='text-center' style='vertical-align:middle'>".$j."</td>";
   		$print .= "<td class='text-center' style='vertical-align:middle'>".$arrnumjuicio[$i-1]."</td>";
   		$print .= "<td class='text-center' style='vertical-align:middle'>".$arrrutcliente[$i-1]."</td>";
   		$print .= "<td class='text-center' style='vertical-align:middle'>".$arrcliente[$i-1]."</td>";
   		$print .= "<td class='text-center' style='vertical-align:middle'>".$arrcuenta[$i-1]."</td>";
   		$print .= "<td class='text-center' style='vertical-align:middle'>".$arrtipojuicio[$i-1]."</td>";
   		$print .= "<td class='text-center' style='vertical-align:middle'>".$arrfecha[$i-1]."</td>";
   		$print .= "<td class='text-center' style='vertical-align:middle'>".$arrmonto[$i-1]."</td>";
   		$print .= "</tr>";
   		$j=$j+1;
   	}   
   	$print .= "</tbody>";
   	$print .= "</table>";	
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
<form role="form" name="form" action="./carga_asignaciones.php" enctype="multipart/form-data" method="post" id="form1">
   <div class="content-page">
      <!-- Start content -->
      <div class="content">
         <div class="container">
            <div class="row">
               <div class="col-xs-12">
                  <div class="page-title-box">
                     <h3 class="page-title">Asignaciones</h3>
                     <div class="clearfix"></div>
                  </div>
               </div>
            </div>
            <!-- end row -->		
            <div class="row">
               <div class="col-sm-12">
                  <div class="card">
                     <div class="card-header">Carga de Archivo</div>
                     <div class="card-block">
                        <div id="page-loader">
                           <h3>Cargando Asignaciones...</h3>
						   <img src="./images/gif-load.gif" alt="loader">
						   <h3>...por favor espere</h3>
                        </div>
                        <div class="row">
                           <div class="form-group col-sm-6">
                              <label>Ejemplo de Archivo de Juicios</label>
                              <a href="ejemplo_archivo_asignaciones.xlsx">DESCARGAR</a>
                           </div>
                        </div>
                        <div class="row">
                           <div class="form-group col-sm-6">
                              <label>Archivo</label><br>
                              <input type="file" id="archivo" name="archivo" accept=".xlsx">
                           </div>
                        </div>
                        <div class="row">
                           <div class="form-group col-sm-6">
                              <button type="submit" class="btn btn-rounded btn-primary" id="subir">Subir</button>
                              <button type="button" class="btn btn-rounded btn-danger" onClick="location='principal.php'">Volver</button>
                           </div>
                        </div>
                        <div id="guardardo_info"></div>
                     </div>
                  </div>
               </div>
            </div>
            <?php if ($error != "") { ?>
            <div class="row">
               <div class="alert alert-danger alert-dismissible" role="alert" align="center">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
                  <strong>¡ATENCION!</strong> <?php echo $error ?>
               </div>
            </div>
            <?php } ?>
            <?php if ($instertados > 0 || $actualizados > 0) { ?>
            <div class="row">
               <div class="col-sm-12">
                  <div class="card">
                     <div class="card-header"><?php echo "Datos Cargados (<span style='font-weight:bold'>Insertados: ".$instertados."</span>, <span style='font-weight:bold'>Actualizados: ".$actualizados."</span>)"; ?>
                     </div>
                     <div class="card-block">
                        <?php echo $print ?>
                     </div>
                  </div>
               </div>
            </div>
            <?php } ?>
         </div>
         <!-- end row -->
         <div class="col-sm-12">
            <div class="card">
               <div class="card-header">Descarga de Asignaciones</div>
               <div class="card-block">
                  <div class="row">
                     <div class="form-group col-sm-2">
                        <label>Fecha</label>
                        <select class="form-control" id="selector" name="selector">
                           <option value="0">----Seleccione----</option>
                           <?php 
                              $sql_consulta="SELECT DISTINCT fecha_asignacion FROM juicios_dato_inicial ORDER BY fecha_asignacion DESC";
                              $datosFechas = array();
                              $datosFechas = call_select($sql_consulta,"");
                              while($resultFechas=mysql_fetch_array($datosFechas['registros'])){ 
                              	echo "<option value='".date("d/m/Y", strtotime($resultFechas['fecha_asignacion']))."' >".date("d/m/Y", strtotime($resultFechas['fecha_asignacion']))."</option>";
                              } 
                              ?>
                        </select>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-sm-1">
                        <button type="button" class="btn btn-rounded btn-primary" id="descargarDatosIniciales">Descargar</button>
                     </div>
                     <div class="form-group col-sm-1">
                        <button type="button" class="btn btn-rounded btn-primary" id="descargarDatosTodos">Descargar Todas</button>
                     </div>
                  </div>
                  <div id="guardardo_info"></div>
               </div>
            </div>
         </div>
      </div>
      <!-- container -->
   </div>
   <!-- content -->
   </div>
   <!-- End content-page -->
</form>
<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->
<?php require 'includes/footer_start.php' ?>
<!-- extra js -->
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js"></script>
<?php require 'includes/footer_end.php' ?>
<script type="text/javascript">
   $(document).ready(function () {
   	$('#subir').click(function(e) {
   		if (document.form.archivo.value == "") {
   			alert('Debe seleccionar un archivo');
   			return false;
   		}
   		document.getElementById('page-loader').style.display='block';
   	});
   	$('#descargarDatosIniciales').click(function(e) {
   		if (document.form.selector.value == "0") {
   			alert('Debe seleccionar una fecha de asignacion');
   			return false;
   		}
   		e.preventDefault();  //stop the browser from following			
   		window.location.href = './descargar_datos_iniciales.php?fecha='+document.form.selector.value;
   	});
      $('#descargarDatosTodos').click(function(e) {
   		e.preventDefault();  //stop the browser from following			
   		window.location.href = './descargar_datos_iniciales.php?fecha=0';
   	});
   });
   
</script>