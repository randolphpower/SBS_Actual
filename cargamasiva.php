<?php

session_start();

include("modelo/conectarBD.php");
require_once("/modelo/consultaSQL.php");
$tipo = $_FILES['archivo']['type'];
$tamanio = $_FILES['archivo']['size'];
$archivotmp = $_FILES['archivo']['tmp_name'];
$lineas = file($archivotmp);
$i = 0;
$tipo = $_POST["tipo"];
$error_en_archivo = true;

if ($tipo == "1") {
	
	foreach ($lineas as $linea_num => $linea) {
		if ($i != 0) {

			/* NUM_JUICIO;TRIBUNAL;TIPO;ROL;RUT_CLIENTE;NOMBRE_DEUDOR;Fecha inicio;Fecha Demanda */

			// Fecha demanda: Corresponde a la fecha de ingreso de la demanda.
			// Fecha Inicio : fecha en la que nos asignaron el pagaré.

			$datos = explode(";", $linea);

			$numjuicio = trim($datos[0]);
			$tribunal = trim($datos[1]);
			$tipojuicio = trim($datos[2]);
			$rol = trim($datos[3]);
			$rutcliente = trim($datos[4]);
			$nombre = trim($datos[5]);
			$fecha_inicio = trim($datos[6]);
			$fecha_demanda = trim($datos[7]);
			
			// echo "Num Juicio: $numjuicio \n";
			// echo "Tribunal: $tribunal \n";
			// echo "Tipo Juicio: $tipojuicio \n";
			// echo "Rol: $rol \n";
			// echo "RUT Cliente : $rutcliente \n";
			// echo "Nombre: $nombre \n";
			// echo "Fecha de Inicio: $fecha_inicio \n"; /* op_eta_proce.CSSTDT */
			// echo "Fecha Demanda: $fecha_demanda \n"; /* op_eta_proce.CSENDDT => op_info_juicios.CELWSTDT */
			
			/* Date conversion -> dd-mm-aaaa => aaaa-mm-dd */

			$farr = split("-", $fecha_inicio);
			$fecha_inicio = "{$farr[2]}-{$farr[1]}-{$farr[0]}";
			
			$farr = split("-", $fecha_demanda);
			$fecha_demanda = "{$farr[2]}-{$farr[1]}-{$farr[0]}";


			$sql_searh = "SELECT * FROM juicios_dato_inicial WHERE id_juicio = ".$numjuicio;

			if ($numjuicio != "") {
				$registroJuicio = call_select($sql_searh, "");

				if ($registroJuicio['num_filas'] == 0) { // No existe el registro
					$arrnumjuicio[$i-1] = $numjuicio;
					$arrrutcliente[$i-1] = $rutcliente;
					$arrtipojuicio[$i-1] = $tipojuicio;
					$arrmsj[$i-1] = "Juicio no existe en datos iniciales";
					$arrcolorrow[$i-1] = "style=background-color:coral;font-weight:bold;color:black;";
				} else { // existe el registro
	
					$tipo_juicio = mysql_fetch_array($registroJuicio['registros'])['tipo_juicio'];
					if ($tipo_juicio != $tipojuicio) {
						$arrmsj[$i-1] = "Tipo Juicio incorrecto (".$tipo_juicio.")";
						$arrcolorrow[$i-1] = "style=background-color:coral;font-weight:bold;color:black;";
						$error_en_archivo = true;
					}
					else {
						$arrmsj[$i-1] = "Registro correcto";
						$error_en_archivo = false;
					}	
					$arrnumjuicio[$i-1] =$numjuicio;
					$arrrutcliente[$i-1] =$rutcliente;
					$arrtipojuicio[$i-1] =$tipojuicio;
				 }
			}	

			if ($error_en_archivo == false)
			{
				$sql_searh = "SELECT * FROM relacion_cliente_juicio WHERE NUM_JUICIO = ".$numjuicio." and ID_CLIENTE = ".$rutcliente.";";
				$num = call_select2($sql_searh);

				if ($num == 0) { // INSERT

					//  relacion_info_juicio
					$sql = "INSERT INTO relacion_cliente_juicio (NUM_JUICIO, ID_CLIENTE, CECRTID, CEDOSSIERID, CETYPE, nombre, TEMP_FECHA_INICIO, TEMP_FECHA_DEM) ";
					$sql .= "VALUES (".$numjuicio.",".$rutcliente.",'".$tribunal."','".$rol."','".$tipojuicio."','".$nombre."', '{$fecha_inicio}', '{$fecha_demanda}');";
					call_insert2($sql, "");

					// op_info_juicios en el caso que no exista en la BD
					$sql = "INSERT INTO op_info_juicios (IDENTIFICADOR, CEDOSSIERID, CNCASENO, CESSNUM, CECRTID, CETYPE, USUSUARIO, CELASTRC, CELASTAC, CELWSTDT) VALUES ";
					$sql .= "('902','".$rol."','".$numjuicio."','".$rutcliente."','".$tribunal."','".$tipojuicio."','".$_SESSION['username']."', 'MA', 'IJ', '{$fecha_demanda}')";
					call_insert2($sql, "");

					$arrnumjuicio[$i-1] = $numjuicio;
					$arrrutcliente[$i-1] = $rutcliente;
					$arrtipojuicio[$i-1] = $tipojuicio;
					$arrrol[$i-1] = $rol;
					$arrnombre[$i-1] = $nombre;
					$arrtribunal[$i-1] = $tribunal;

				} else if ($num == 1) { // UPDATE

					$sql = "UPDATE relacion_cliente_juicio SET ";
					$sql .= "CECRTID='".$tribunal."', ";
					$sql .= "CETYPE='".$tipojuicio."', ";
					$sql .= "nombre='".$nombre."', ";
					$sql .= "CEDOSSIERID='".$rol."', ";
					$sql .= "TEMP_FECHA_INICIO = '{$fecha_inicio}', ";
					$sql .= "TEMP_FECHA_DEM = '{$fecha_demanda}' ";
					$sql .= "WHERE (NUM_JUICIO='".$numjuicio."') AND (ID_CLIENTE='".$rutcliente."') ";
					call_update2($sql);

					// Insercion en op_info_juicios en el caso que exista en la tabla "relacion_info_juicio" se comprueba que exista en "op_info_juicios"
					$sql = "SELECT * FROM op_info_juicios WHERE CNCASENO=".$numjuicio."  and CESSNUM=".$rutcliente.";";
					$num = call_select2($sql);

					if ($num == 0) {
						$sql = "INSERT INTO op_info_juicios (IDENTIFICADOR, CEDOSSIERID, CNCASENO, CESSNUM, CECRTID, CETYPE, USUSUARIO, CELASTRC, CELASTAC, CECOMM) VALUES ('902','".$rol."','".$numjuicio."','".$rutcliente."','".$tribunal."','".$tipojuicio."','".$_SESSION['username']."','MA','IJ', '')";
						call_insert2($sql,"");
					}
					
					$arrnumjuicio[$i-1] =$numjuicio;
					$arrrutcliente[$i-1] =$rutcliente;
					$arrtipojuicio[$i-1] =$tipojuicio;
					$arrrol[$i-1] =$rol;
					$arrnombre[$i-1] =$nombre;
					$arrtribunal[$i-1] =$tribunal;

				}
			}
			// INSERT OR UPDATE
			// Fecha Inicio, Fecha Fin ( = Fecha Demanda) -> PROCESO "Ingreso de la demanda..." 
			
			// $stg_id = 1; // Identificador de la etapa -> "Ingreso de la demanda"
			// $sql = "SELECT * FROM op_eta_proce WHERE CSCASENO = '{$numjuicio}' AND CSSTGID = {$stg_id}";
			// $num = call_select2($sql);

			// if ($num == 0) {
			// 	$sql = "INSERT INTO op_eta_proce (CSCASENO, CSTYPE, CSSTGID, CSSTDT	, CSENDDT, USUSUARIO) VALUES ";
			// 	$sql .= "('{$numjuicio}', '{$tipojuicio}', {$stg_id}, '{$fecha_inicio}', '{$fecha_demanda}','{$_SESSION['username']}');";
			// 	call_insert2($sql, "");
			// } else {
			// 	$sql = "UPDATE op_eta_proce ";
			// 	$sql .= "SET CSSTDT = '{$fecha_inicio}', CSENDDT = '{$fecha_demanda}', USUSUARIO = '{$_SESSION['username']}' ";
			// 	$sql .= "WHERE CSCASENO = '{$numjuicio}' AND CSSTGID = {$stg_id};";
			// 	call_update2($sql, "");
			// }
			
	   }

	   $i++;
	}

} else if ($tipo == "2") {

	$le_id_name = "EXPTYPE1";

	foreach($lineas as $linea_num => $linea) {

	   	if ($i != 0) {

			$datos = explode(";",$linea);
		   	$le_code = trim($datos[0]);
		   	$le_description = trim($datos[1]);
		   	$le_monto = trim($datos[2]);

		   	$sql_searh = "SELECT * FROM gastos_judiciales WHERE (LE_CODE='".$le_code."');";
		   	$num = call_select2($sql_searh);

		  	if ($num == 0) {

		   		$sql_insert = "INSERT INTO gastos_judiciales (LE_ID, LE_CODE, LE_DESCRIPTION, LE_MONTO) VALUES ('".$le_id_name."','".$le_code."','".$le_description."','".$le_monto."')";
		   		call_insert2($sql_insert,"");

		   		$ar_le_id[$i-1] = $le_id_name;
		   		$ar_le_code[$i-1] = $le_code;
		   		$ar_le_description[$i-1] = $le_description;
		   		$ar_le_monto[$i-1] = $le_monto;

		  	}

	   	}

	   	$i++;

	}

}

// var_dump($le_description);

function call_insert2($insert_sql, $parametro_condicional){
	include("modelo/conectarBD.php");
	global $var_retorno_datos;
	mysql_query($insert_sql,$conexion) or die(mysql_error());

	if($parametro_condicional!=""){
		$parametro_sql=mysql_query($parametro_condicional,$conexion) or die(mysql_error());
		$var_retorno_datos = array('ultimo_id' => $parametro_sql,
							 'por_asignar1' => '',
							 'por_asignar2' => '',
							 'por_asignar3' => '',
							 'por_asignar4' => '');
	}

	mysql_close($conexion);
	return $var_retorno_datos;

}

function call_select2($select_sql){
	include("modelo/conectarBD.php");
	$result=mysql_query($select_sql,$conexion) or die(mysql_error());
	$numresult=mysql_num_rows($result);
	mysql_close($conexion);
	return $numresult;

}

function call_update2($update_sql){

	include("modelo/conectarBD.php");
	global $var_retorno_datos;
	$nro_filas_afectadas = 0;

	$parametro_sql=mysql_query($update_sql,$conexion) or die(mysql_error());
	$nro_filas_afectadas = mysql_affected_rows($conexion);

	$var_retorno_datos = "";

	mysql_close($conexion);
	return $var_retorno_datos;

}//Fin funcion update

?>

<?php
require 'includes/header_start.php';

//<!-- extra css -->
require 'includes/header_end.php';

?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->

<div class="content-page">
	
    <!-- Start content -->
    <div class="content">
        <div class="container">

			<div class="row">
            	<div class="col-sm-12">
					<div class="card">
						<div class="card-header">Datos Cargados</div>
						<div class="card-block">

                        <?php

						if($tipo=="1"){

						?>

						<table class="table table-striped table-bordered table-hover">
						 <thead>
                              <tr align="center" class="info text-center text-default" style="vertical-align:middle">
                                  <th class="col-md-1 text-center" style="vertical-align:middle">Nro.</th>
                                  <th class="col-md-1 text-center" style="vertical-align:middle">Identificador</th>
                                  <th class="col-md-1 text-center" style="vertical-align:middle">Rut Cliente</th>
								  <th class="col-md-1 text-center" style="vertical-align:middle">Tipo Juicio</th>
								  <th class="col-md-1 text-center" style="vertical-align:middle">Mensaje</th>
                              </tr>
						</thead>
                        <tbody>


                          <?php
							  $j=1;
                              $count = count($arrnumjuicio);
                              for ($i = 0; $i < $count; $i++) {

                              ?>

									<tr class="text-center text-muted" data-placement="top" <?php echo $arrcolorrow[$i]; ?>>

                                  	  <td class="text-center" style="vertical-align:middle"><?php echo $j; ?></td>

                                      <td class="text-center" style="vertical-align:middle"><?php echo $arrnumjuicio[$i]?></td>

                                      <td class="text-center" style="vertical-align:middle"><?php echo $arrrutcliente[$i]?></td>

                                      <td class="text-center" style="vertical-align:middle"><?php echo $arrtipojuicio[$i]?></td>

									  <td class="text-center" style="vertical-align:middle"><?php echo $arrmsj[$i]?></td>
                                  </tr>

                              <?php
								$j=$j+1;
                              }
							?>
                           </tbody>

  </table>
  						  <?php

						}else if($tipo==2){
                           ?>

                            <table class="table table-striped table-bordered table-hover">
						 <thead>
                              <tr align="center" class="info text-center text-default" style="vertical-align:middle">
								  <th class="col-md-1 text-center" style="vertical-align:middle">Nro.</th>
                                  <th class="col-md-1 text-center" style="vertical-align:middle">Identificador de Gastos de Juicios</th>
                                  <th class="col-md-1 text-center" style="vertical-align:middle">Codigo</th>
                                  <th class="col-md-1 text-center" style="vertical-align:middle">Descripci&oacute;n</th>
								  <th class="col-md-1 text-center" style="vertical-align:middle">Monto</th
                              ></tr>
						</thead>
                        <tbody>


                          <?php
						      $j=1;
						  	  $count = count($ar_le_id);
                              for ($i = 0; $i < $count; $i++) {

                              ?>

                                  <tr class="text-center text-muted" data-placement="top">

                                      <td class="text-center" style="vertical-align:middle"><?php echo $j; ?></td>

                                      <td class="text-center" style="vertical-align:middle"><?php echo $ar_le_id[$i] ?></td>

                                      <td class="text-center" style="vertical-align:middle"><?php echo $ar_le_code[$i] ?></td>

                                      <td class="text-center" style="vertical-align:middle"><?php echo utf8_encode($ar_le_description[$i]) ?></td>

                                      <td class="text-center" style="vertical-align:middle"><?php echo $ar_le_monto[$i] ?></td>


                                  </tr>

                              <?php
								$j=$j+1;
                              }
							?>
                           </tbody>

  </table>

                            <?php

						}
                           ?>




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
