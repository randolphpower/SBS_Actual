<?php
session_start();

if(!$_SESSION['loggedin']){
		
		header("Location: pages/login.php?men=¡Diculpe! La sesion expiro, ingrese nuevamente..."); //envío al usuario a la pag. de autenticación
		exit();
	}
//fin session

?>