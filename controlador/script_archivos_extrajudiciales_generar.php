<?php 
	
switch ($_GET["opcion"]) {

    case "1":
    $conexion = mysqli_connect("localhost","root","12345678","servicobranza");
    if(!$conexion){
        echo "error al conectarse a la base de datos";
    }
    else {
        echo "conexion exitosa</br>";
        $a = GenerarPlano200($conexion);
        $b = GenerarPlano600($conexion);
        $c = GenerarPlano700($conexion);
        $d = GenerarPlano800($conexion);

        llenaeFTP($a, $b, $c, $d);
        $now = strval(date("Y-m-d"));
        //marcar todo como generado en base de datos
        $qwery = "UPDATE `plano200` SET `CESENDDT`= '". $now . "' WHERE `CESENDDT` = '0000-00-00';";
        $Respuesta = mysqli_query($conexion ,$qwery);
        $qwery = "UPDATE `plano600` SET `CESENDDT`= '". $now . "' WHERE `CESENDDT` = '0000-00-00';";
        $Respuesta = mysqli_query($conexion ,$qwery);
        $qwery = "UPDATE `plano700` SET `CESENDDT`= '". $now . "' WHERE `CESENDDT` = '0000-00-00';";
        $Respuesta = mysqli_query($conexion ,$qwery);
        $qwery = "UPDATE `plano800` SET `CESENDDT`= '". $now . "' WHERE `CESENDDT` = '0000-00-00';";
        $Respuesta = mysqli_query($conexion ,$qwery);
        mysqli_close($conexion);
        echo "Archivos generados";

    }
    break;
}
function  GenerarPlano200( $conexion)
    {
        try
        {
            $qwery = "SELECT * FROM `plano200` WHERE`CESENDDT`= '0000-00-00'";
            $Respuesta = mysqli_query($conexion ,$qwery);
            $transacciones = $Respuesta;
            $nombre = "traSERVICOB_".date('Ymd', strtotime('+1 days')).".txt";
            $ar =fopen($nombre,"w") or die ("error al crear");
            $i = 2;
            foreach ( $transacciones AS $item )
            {
                $linea = ""; 
                //if ($item[0] == null) continue;
                $linea = $linea . str_pad($item["VALORCONSTATE"], 3, " ", STR_PAD_RIGHT); 
                $linea = $linea . str_pad($item["GRUPO"], 1, " ", STR_PAD_RIGHT); 
                $linea = $linea . str_pad($item["CUENTA"], 25, " ", STR_PAD_RIGHT);  
                $linea = $linea . str_pad($item["FECHA"], 8, " ", STR_PAD_RIGHT); //Fecha
                $linea = $linea . str_pad($item["HORA"], 9, " ", STR_PAD_LEFT); //Hora
                $linea = $linea . str_pad($item["SECUENCIA"], 3, " ", STR_PAD_RIGHT); //Secuencia
                $linea = $linea . str_pad($item["CODIGOACCION"], 2, " ", STR_PAD_RIGHT); //Código de Acción
                $linea = $linea . str_pad($item["RESULTADO"], 2, " ", STR_PAD_RIGHT); //Código de Resultado
                $linea = $linea . str_pad($item["CODIGOCARTA"], 2, " ", STR_PAD_RIGHT); //Código de Carta
                $linea = $linea . str_pad($item["IDEMPEX"], 8, " ", STR_PAD_RIGHT); //Id 
                $linea = $linea . str_pad($item["COMENTARIO"], 56, " ", STR_PAD_RIGHT); //Comentario
                if (strlen($item["TELEFONO"]) == 8){
                    $linea = $linea . str_pad("2".$item["TELEFONO"], 13, " ", STR_PAD_RIGHT); //TELEFONO
                }
                else {
                    $linea = $linea . str_pad($item["TELEFONO"], 13, " ", STR_PAD_RIGHT); //TELEFONO
                }
                 
                $linea = $linea . str_pad($item["IDGESTOR"], 8, " ", STR_PAD_RIGHT); //IDGESTOR
                fwrite($ar, $linea);
                fwrite($ar, "\n");
                $linea = "";
                $i++;
            }
            fclose($ar);
            return $nombre;
        }
        catch (Exception $ex)
        {
            // log.Error("GenerarPlano200", $ex);
            // throw;
        }
    
    }
    function GenerarPlano600($conexion)
    {
        try
        {
            $qwery = "SELECT * FROM `plano600` WHERE`CESENDDT`= '0000-00-00'";
            $Respuesta = mysqli_query($conexion ,$qwery);
            $transacciones = $Respuesta;
            $nombre = "600SERVICOB_".date('Ymd', strtotime('+1 days')).".txt";
            $ar =fopen($nombre,"w") or die ("error al crear");

            foreach ( $transacciones AS $item )
            {
                $linea = "";
                $linea =$linea  . str_pad($item["VALORCONSTANTE"], 3, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["GRUPO"], 1, " ", STR_PAD_RIGHT);   
                $linea =$linea  . str_pad($item["CUENTA"], 25, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["IDEMPEX"], 8, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["ACCION"], 2, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["RESULTADO"], 2, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["FECHA"], 8, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["PROMNO"], 3, "0", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["PROMAI"], 3, "0", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["FECHAVENCPROM"], 8, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["PROMMONTO"], 15, "0", STR_PAD_RIGHT); 
                fwrite($ar, $linea);
                fwrite($ar, "\n");
                $linea = "";
            }
            fclose($ar);
            return $nombre;
        }
        catch (Exception $ex)
        {
            //log.Error("GenerarPlano600", ex);
            //throw;
        }
    }
    
    function GenerarPlano700($conexion)
    {
        try
        {
            $qwery = "SELECT * FROM `plano700` WHERE`CESENDDT`= '0000-00-00'";
            $Respuesta = mysqli_query($conexion ,$qwery);
            $transacciones = $Respuesta;
            $listaLineas = array();
            $nombre = "telSERVICOB_".date('Ymd', strtotime('+1 days')).".txt";
            $ar =fopen($nombre,"w") or die ("error al crear");

            foreach ( $transacciones AS $item )
            {
                $linea = "";
                $linea =$linea  . str_pad($item["VALORCONSTANTE"], 3, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["GRUPO"], 1, " ", STR_PAD_RIGHT);   
                $linea =$linea  . str_pad($item["CUENTA"], 25, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["IDCLIENTE"], 25, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["TIPOTELEFONO"], 3, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["AREACODE"], 5, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["TELEFONO"], 13, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["FONOEXTEN"], 8, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["IDEMPEX"], 8, " ", STR_PAD_RIGHT); 
                fwrite($ar, $linea);
                fwrite($ar, "\n");
                $linea = "";
            }
            fclose($ar);
            return $nombre;
        }
        catch (Exception $ex)
        {
            //log.Error("GenerarPlano700", ex);
           // throw;
        }
    }
    function GenerarPlano800($conexion)
    {
        try
        {
            $qwery = "SELECT * FROM `plano800` WHERE`CESENDDT`= '0000-00-00'";
            $Respuesta = mysqli_query($conexion ,$qwery);
            $transacciones = $Respuesta;
            $listaLineas = array();
            $nombre = "dirSERVICOB_".date('Ymd', strtotime('+1 days')).".txt";
            $ar =fopen($nombre,"w") or die ("error al crear");
            foreach ( $transacciones AS $item )
            {
                $linea = "";
                $linea =$linea  . str_pad($item["VALORCONSTANTE"], 3, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["GRUPO"], 1, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["CUENTA"], 25, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["IDCLIENTE"], 25, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["TIPDIRECC"], 1, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["DOMICILIO"], 80, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["COMUNA"], 80, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["REGION"], 80, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["CIUDAD"], 40, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["DIRESTADO"], 5, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["POSTALCODE"], 10, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["IDEMPREX"], 8, " ", STR_PAD_RIGHT); 
                $linea =$linea  . str_pad($item["ESTADO"], 1, " ", STR_PAD_RIGHT); 
                fwrite($ar, $linea);
                fwrite($ar, "\n");
                $linea = "";
            }
        fclose($ar);
        return $nombre;
        }
        catch (Exception $ex)
        {
           // log.Error("GenerarPlano800", ex);
            //throw;
        }
    }


    function llenaeFTP($a, $b, $c, $d) {

        $zipa = new ZipArchive();
        $zipb = new ZipArchive();
        $zipc = new ZipArchive();
        $zipd = new ZipArchive();
        $nombreZipa = substr($a,0, -4) . ".zip";
        $nombreZipb = substr($b,0, -4) . ".zip";
        $nombreZipc = substr($c,0, -4) . ".zip";
        $nombreZipd = substr($d,0, -4) . ".zip";
        $zipa->open($nombreZipa, ZipArchive::CREATE);
        $zipb->open($nombreZipb, ZipArchive::CREATE);
        $zipc->open($nombreZipc, ZipArchive::CREATE);
        $zipd->open($nombreZipd, ZipArchive::CREATE);
        $zipa->addFile($a,substr($a,8,24));
        $zipb->addFile($b,substr($b,8,24));
        $zipc->addFile($c,substr($c,8,24));
        $zipd->addFile($d,substr($d,8,24));
        $zipa->close();
        $zipb->close();
        $zipc->close();
        $zipd->close();

        /*include 'Net/SFTP.php';
        $sftp = new Net_SFTP('200.53.142.68');
        if (!$sftp->login('servicob', '53rv1c0b_2017')) {
           echo "error de login";
        }else {
            $sftp->put($a, $a, NET_SFTP_LOCAL_FILE);
            $sftp->put($nombreZipa, $nombreZipa, NET_SFTP_LOCAL_FILE);
            $sftp->put($b, $b, NET_SFTP_LOCAL_FILE);
            $sftp->put($nombreZipb, $nombreZipb, NET_SFTP_LOCAL_FILE);
            $sftp->put($c, $c, NET_SFTP_LOCAL_FILE);
            $sftp->put($nombreZipc, $nombreZipc, NET_SFTP_LOCAL_FILE);
            $sftp->put($d, $d, NET_SFTP_LOCAL_FILE);
            $sftp->put($nombreZipd, $nombreZipd, NET_SFTP_LOCAL_FILE);
        }*/
    }

	