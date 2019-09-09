<?php

session_start();
require_once("/modelo/consultaSQL.php");
require_once("/modelo/conectarBD.php");

$file = $_GET['archivo'];
echo($file);

$lineas = file($file);

$i = 0;


	
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
            
			$num = call_select($sql_searh, "");

			if ($num['num_filas'] == 0) { // INSERT
				$arrnumjuicio[$i-1] = $numjuicio;
				$arrrutcliente[$i-1] = $rutcliente;
				$arrtipojuicio[$i-1] = $tipojuicio;
				$arrrol[$i-1] = $rol;
				$arrnombre[$i-1] = $nombre;
                $arrtribunal[$i-1] = $tribunal;
                $arrmsj[$i-1] = "NO EXISTE";

			} else { // UPDATE                
				$arrnumjuicio[$i-1] =$numjuicio;
				$arrrutcliente[$i-1] =$rutcliente;
				$arrtipojuicio[$i-1] =$tipojuicio;
				$arrrol[$i-1] =$rol;
				$arrnombre[$i-1] =$nombre;
                $arrtribunal[$i-1] =$tribunal;
                $arrmsj[$i-1] = "SI EXISTE";
		 	}
	   }

	   $i++;
	}



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
                              $count = count($arrnumjuicio);
                              for ($i = 0; $i < $count; $i++) {

                              ?>

                                  <tr class="text-center text-muted" data-placement="top">

                                  	  <td class="text-center" style="vertical-align:middle"><?php echo $i+1; ?></td>

                                      <td class="text-center" style="vertical-align:middle"><?php echo $arrnumjuicio[$i]?></td>

                                      <td class="text-center" style="vertical-align:middle"><?php echo $arrrutcliente[$i]?></td>

                                      <td class="text-center" style="vertical-align:middle"><?php echo $arrtipojuicio[$i]?></td>

                                      <td class="text-center" style="vertical-align:middle"><?php echo $arrmsj[$i]?></td>
                                  </tr>


  						  <?php

						}?>

</tbody>

</table>                         



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
