<?php
	session_start();
	require_once("../modelo/consultaSQL.php");
	require_once("../modelo/conectarBD.php");

    // Checks if user sent an empty form  
    if(!empty(array_filter($_FILES['archivos']['name']))) { 
  
        // Loop through each file in archivos[] array 
        foreach ($_FILES['archivos']['tmp_name'] as $key => $value) { 
              
            $file_tmpname = $_FILES['archivos']['tmp_name'][$key]; 
            $file_name = $_FILES['archivos']['name'][$key]; 
            echo realpath($_SERVER["DOCUMENT_ROOT"]);
            echo $file_tmpname."<br/>"; 
            echo $file_name."<br/>"; 
        } 
    }  
    else {           
        echo "No archivos selected."; 
    } 

    //header('Location: ../carga_inicial_resultado.php?resultado='.$print);
?>
