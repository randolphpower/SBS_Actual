<?php

session_start();

if (isset($_POST)) {

    include("modelo/conectarBD.php");
    include("modelo/consultaSQL.php");

    $numjuicio = $_POST["numjuicio"];
    $arr = split("/", $_POST["fechafin"]);
    $fechafin = $arr[2]."-".$arr[1]."-".$arr[0];
    
    $stg_id = 2; // notificacion de la demanda
    $sql = "SELECT * FROM op_eta_proce WHERE CSCASENO = '{$numjuicio}' AND CSSTGID = {$stg_id}";
    $num = call_select($sql, "");

    $status = "Petición No Válida!, debe ingresar NOTIFICACION primero";

    if ($num > 0) {
        
        $sql = "UPDATE op_eta_proce ";
        $sql .= "SET CSENDDT = '{$fechafin}', USUSUARIO = '{$_SESSION['username']}' ";
        $sql .= "WHERE CSCASENO = '{$numjuicio}' AND CSSTGID = {$stg_id};";
        call_update($sql, "");
        
        $status = "Fecha actualizada correctamente, Ingrese nuevamente EMBARGO DE BIENES";

    } else { # Nothing to do

        $status = "Ingrese NOTIFICACION DE LA DEMANDA";

    }
    
    header('Content-type: application/json');
    echo json_encode(["status" => $status]);

    exit;

}

?>