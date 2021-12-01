<?php

include 'Net/SFTP.php';

include("../modelo/conectarBD.php");
include("../modelo/consultaSQL.php");
include("script_general.php");

$UPLOAD_FTP = true;

$hoy = "20181214";
$fecha = substr($hoy, 0, 4)."-".substr($hoy, 4, 2)."-".substr($hoy, 6, 2);

echo "Procesando {$fecha} .... \n";

function agregarespacios($cadena, $largo) { 
	if (is_null($cadena) || $cadena == "" || $cadena == "00000000" ) {
		return $cadena = str_repeat(" ", $largo);
	} else {
		$largocadena = strlen($cadena);
		$faltante = $largo-$largocadena;
		return $cadena.=str_repeat(" ", $faltante);
	}
}

// 1. Archivo : 902_SERVICOBRANZA

$sql = "SELECT `IDENTIFICADOR`,  `CNCASENO`,  `CEDOSSIERID`,  `CESSNUM`, `CECOMM`, `CECRTID`, `CELASTAC` , `CELASTRC`, DATE_FORMAT(`CELSTACT`,  '%d%m%Y') AS `CELSTACT`, DATE_FORMAT( `CELSTCON`, '%m%d%Y' ) AS `CELSTCON`, DATE_FORMAT( `CELWSTDT`, '%m%d%Y' ) AS `CELWSTDT`, `CETYPE`, `CESENDDT` ".$var_from."op_info_juicios ".$var_where."`CESENDDT` = 0 OR `CESENDDT` = '{$fecha} 00:00:00'";
$datos = call_select($sql, '');

$archivo= "902_SERVICOBRANZA_".$hoy.".txt";
if (file_exists($archivo)) {
     unlink($archivo);
}

$fp = fopen($archivo, "x");

while ($result = mysql_fetch_array($datos['registros'])) {

    $cantidadNum = strlen($result["CESSNUM"]);
    if ($cantidadNum == 8) {
        $rut = "0".$result["CESSNUM"];
    } else{
        $rut="00".$result["CESSNUM"];
    }

    $linea = "902".
        agregarespacios($result["CNCASENO"],25).
        agregarespacios($result["CEDOSSIERID"],15).
        agregarespacios($rut,25).
        agregarespacios($result["CECOMM"],200).
        agregarespacios($result["CECRTID"],30).$result["CELASTAC"].$result["CELASTRC"].
        agregarespacios($result["CELSTACT"],8).
        agregarespacios($result["CELSTCON"],8).
        agregarespacios($result["CELWSTDT"],8).
        agregarespacios($result["CETYPE"],20);

    fputs($fp, $linea);
    fputs($fp, chr(13).chr(10));
}

fclose($fp) ;

$zip= new ZipArchive();
$filename = "902_SERVICOBRANZA_".$hoy.".zip";

if ($zip->open($filename, ZIPARCHIVE::CREATE) === true) {
    $zip->addFile($archivo);
    $zip->close();
}

if ($UPLOAD_FTP) {
    $sftp = new Net_SFTP('200.53.142.68');
    if (!$sftp->login('servicob', '53rv1c0b_2017')) {
        echo "error de login";
    }   else {
        $sftp->put($filename, $filename, NET_SFTP_LOCAL_FILE);
        $fecha=date("'Y-m-d'");
        $sql = "UPDATE op_info_juicios SET CESENDDT = '{$fecha} 00:00:00' WHERE CESENDDT = 0";
        call_update($sql, '');
    }
    $sftp1 = new Net_SFTP('jupiter.onvision.cl');
    if (!$sftp1->login('sftpclaservbr', 'sftpclaservbr.2021')) {
        echo "error de login";
    }   else {
        $sftp1->put($filename, $filename, NET_SFTP_LOCAL_FILE);
    }
}

// 2. Archivo: etapas_SERVICOBRANZA

$sql = "SELECT `CSCASENO`,  `CSTYPE`,  `CSSTGID`, DATE_FORMAT( `CSSTDT`, '%m%d%Y' ) AS `CSSTDT`, DATE_FORMAT( `CSENDDT`, '%m%d%Y' ) AS `CSENDDT`, `CSSNDT` ".$var_from."op_eta_proce ".$var_where."`CSSNDT` = 0 OR `CSSNDT` = '{$fecha} 00:00:00'";
$datos = array();
$datos = call_select($sql, '');

$archivo = "etapas_SERVICOBRANZA_".$hoy.".txt";
if (file_exists($archivo)) {
        unlink($archivo);
}

$fp = fopen($archivo, "x");
while($result = mysql_fetch_array($datos['registros'])) {
    $linea = agregarespacios($result["CSCASENO"],25).
             agregarespacios($result["CSTYPE"],20)."00".$result["CSSTGID"].
             agregarespacios($result["CSSTDT"],8).
             agregarespacios($result["CSENDDT"],8);

    fputs($fp, $linea);
    fputs($fp, chr(13).chr(10));

}
fclose($fp);

$zip = new ZipArchive();
$filename = "etapas_SERVICOBRANZA_".$hoy.".zip";

if ($zip->open($filename, ZIPARCHIVE::CREATE) === true){
    $zip->addFile($archivo);
    $zip->close();
}

if ($UPLOAD_FTP) {
    $sftp = new Net_SFTP('200.53.142.68');
    if (!$sftp->login('servicob', '53rv1c0b_2017')) {
        echo "error de login";
    } else {
        $sftp->put($filename, $filename, NET_SFTP_LOCAL_FILE);
        $sql = "UPDATE op_eta_proce SET CSSNDT = '{$fecha} 00:00:00' WHERE CSSNDT = 0";
        call_update($sql,"");
    }
    $sftp1 = new Net_SFTP('jupiter.onvision.cl');
    if (!$sftp1->login('sftpclaservbr', 'sftpclaservbr.2021')) {
        echo "error de login";
    }   else {
        $sftp1->put($filename, $filename, NET_SFTP_LOCAL_FILE);
    }
}

// 3. Archivo: gastos_SERVICOBRANZA

$datos = array();
$sql = "SELECT CSCASENO, CETYPE, EXSTGID, EXSUPPLIER, EXINVOICE, EXAGENCY, LPAD(EXAMT,15,'0') AS EXAMT, DATE_FORMAT(EXAUTDT, '%m%d%Y') AS EXAUTDT, EXCOLLID, EXCOLSUP, EXDESC, EXIDESC, EXTYPE, EXSTYPE, EXSENDDT ".$var_from."op_gastos ".$var_where."EXSENDDT = 0 OR EXSENDDT = '{$fecha} 00:00:00'";
$datos = call_select($sql, '');

$archivo= "gastos_SERVICOBRANZA_".$hoy.".txt";
if (file_exists($archivo)) {
    unlink($archivo);
}

$fp = fopen($archivo, "x");

while($result = mysql_fetch_array($datos['registros'])) {
    $linea = agregarespacios($result["CSCASENO"],25).
        agregarespacios($result["CETYPE"],20)."00".$result["EXSTGID"].
        agregarespacios($result["EXSUPPLIER"],80).
        agregarespacios($result["EXINVOICE"],20).$result["EXAGENCY"].
        agregarespacios($result["EXAMT"],15).
        agregarespacios($result["EXAUTDT"],8).$result["EXCOLLID"].$result["EXCOLSUP"].
        agregarespacios($result["EXDESC"],200).
        agregarespacios($result["EXIDESC"],40).
        agregarespacios($result["EXTYPE"],20).
        agregarespacios($result["EXSTYPE"],20);
    fputs($fp, $linea);
    fputs($fp, chr(13).chr(10));
}
fclose($fp) ;

$zip = new ZipArchive();
$filename = "gastos_SERVICOBRANZA_".$hoy.".zip";

if ($zip->open($filename,ZIPARCHIVE::CREATE)===true){
    $zip->addFile($archivo);
    $zip->close();
}

if ($UPLOAD_FTP) {
    $sftp = new Net_SFTP('200.53.142.68');
    if (!$sftp->login('servicob', '53rv1c0b_2017')) {
        echo "error de login";
    } else {
        $sftp->put($filename, $filename, NET_SFTP_LOCAL_FILE);
        $sql_update = "UPDATE op_gastos SET EXSENDDT = '{$fecha}' WHERE EXSENDDT = 0";
        call_update($sql_update,"");
    }
    $sftp1 = new Net_SFTP('jupiter.onvision.cl');
    if (!$sftp1->login('sftpclaservbr', 'sftpclaservbr.2021')) {
        echo "error de login";
    }   else {
        $sftp1->put($filename, $filename, NET_SFTP_LOCAL_FILE);
    }

}

// 4. Archivo: 200J_SERVICOBRANZA

$datos = array();
$sql = "SELECT CONSTANTE, ACACCT, DATE_FORMAT(DATE, '%m%d%Y') AS DATE, ACSEQNUM, ACACCODE, ACRCCODE, ACLCCODE, ACCIDMAN, ACCOMN, ACPHONE, ACEXT, ACSNDATE ".$var_from."op_200_gestiones ".$var_where."ACSNDATE = 0 OR ACSNDATE = '{$fecha} 00:00:00'";
$datos = call_select($sql,"");

$archivo= "200J_SERVICOBRANZA_".$hoy.".txt";
if (file_exists($archivo)) {
     unlink($archivo);
}

$fp = fopen($archivo, "x");
while($result=mysql_fetch_array($datos['registros'])){
    $linea="200".
        agregarespacios($result["ACACCT"],26).
        agregarespacios($result["DATE"],8)." 00:00:00001".$result["ACACCODE"].$result["ACRCCODE"]."  SERVICOB".
        agregarespacios(utf8_decode($result["ACCOMN"]),56).
        agregarespacios($result["ACPHONE"],13).
        agregarespacios($result["ACEXT"],8);
    fputs($fp,$linea);
    fputs($fp,chr(13).chr(10));
}
fclose($fp) ;

$zip= new ZipArchive();
$filename = "200J_SERVICOBRANZA_".$hoy.".zip";

if($zip->open($filename,ZIPARCHIVE::CREATE)===true){
    $zip->addFile($archivo);
    $zip->close();
}

if ($UPLOAD_FTP) {
    $sftp = new Net_SFTP('200.53.142.68');
    if (!$sftp->login('servicob', '53rv1c0b_2017')) {
       echo "error de login.\n";
    } else {
        $sftp->put($filename, $filename, NET_SFTP_LOCAL_FILE);
        $sql_update = "UPDATE op_200_gestiones SET ACSNDATE = '{$fecha} 00:00:00' WHERE ACSNDATE = 0";
        call_update($sql_update, '');
    }
    $sftp1 = new Net_SFTP('jupiter.onvision.cl');
    if (!$sftp1->login('sftpclaservbr', 'sftpclaservbr.2021')) {
        echo "error de login";
    }   else {
        $sftp1->put($filename, $filename, NET_SFTP_LOCAL_FILE);
    }
}

echo "Terminado";

?>