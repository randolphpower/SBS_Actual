<?php
//session_start();
include("../modelo/conectarBD.php");
include("../modelo/consultaSQL.php");



switch ($_GET["opcion"]) {
		
case "1":
		

//echo "Numero juicio: ".$_GET["numeroJuicio"]."<br>";
//echo "ID Cliente: ".$_GET["idCliente"]."<br>";

		

/*$fechaOp = $_GET["fechaOp"];
$fechaVen = $_GET["fechaVe"];

	if($fechaOp==""){
		$fechaOp=date("Y-m-d");
	}

	if($fechaVen==""){
		$fechaVen=date("Y-m-d");
	}*/
	
 	//"INSERT INTO $tabla_db1 (nombre,email,fecha) VALUES ('$nombre','$email','$fecha')";
	$sql_insert=$var_insert_into." relacion_cliente_juicio (NUM_JUICIO, ID_CLIENTE) ".$var_values."('".$_GET["numeroJuicio"]."','".$_GET["idCliente"]."')";
		
	//echo $sql_insert;
	
	call_insert($sql_insert,"");
	
	?>
        
        
<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Bien Hecho!</strong> Su operaci&oacute;n fue almacenada y procesada.
</div>
        
    <?php
	
break;//Finaliza ingresar el identificador del cliente y del juicio		
		
		
		
		
}
?>