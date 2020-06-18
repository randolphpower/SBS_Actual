<?php

ini_set('memory_limit','512M');
error_reporting(0);
date_default_timezone_set('America/Santiago');
session_start();

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet as Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Xlsx;

require 'vendor/autoload.php';

include("modelo/conectarBD.php");
include("modelo/consultaSQL.php");

$sql = "SELECT * FROM  usuarios WHERE US_USUARIO = '".$_SESSION['username']."' AND rol = 'administrador'";
$q = mysql_query($sql, $conexion) or die(mysql_error());
$reg_fil = mysql_num_rows($q);

$min = 'N/A';
$max = 'N/A';

$mysqli = new mysqli($host, $usuario, $password, $basedatos);
if (!$mysqli->query("CALL sp_ulitmo_comentario")) {
    echo "Falló CALL: (" . $mysqli->errno . ") " . $mysqli->error;
}

$sql = "SELECT num, ACACCT, DATE, CODIGO_ACCION, COMENTARIO, CODIGO_RESPUESTA, `temp_comentario`.ID_JUICIO, ";
$sql .= "informe_datos.FECHA_INSERT, ";
$sql .= "codigo_accion.DESCRIPCION AS ACCION, ";
$sql .= "codigo_result.DESCRIPCION AS RESPUESTA, "; 
$sql .= "NUM_JUICIO,ID_CLIENTE,CECRTID,CEDOSSIERID,PROCURADOR ";
$sql .= "FROM `temp_comentario`, ";
$sql .= "informe_datos, ";
$sql .= "codigo_accion, ";
$sql .= "codigo_result, ";
$sql .= "relacion_cliente_juicio ";
$sql .= "WHERE `temp_comentario`.num=1 ";
$sql .= "AND `temp_comentario`.id = informe_datos.ID_200_GESTION ";
$sql .= "AND `temp_comentario`.CODIGO_ACCION = codigo_accion.CODIGO ";
$sql .= "AND `temp_comentario`.CODIGO_RESPUESTA = codigo_result.CODIGO ";
$sql .= "AND informe_datos.ID_JUICIO = relacion_cliente_juicio.NUM_JUICIO ";

if (trim($_GET['min']) != "") {

    $arr = explode("/", $_GET['min']);
    $min = $arr[2]."-".$arr[1]."-".$arr[0];
    $sql .= "AND informe_datos.FECHA_INSERT >= '{$min}' ";
}

if (trim($_GET['max']) != "") {
    $arr = explode("/", $_GET['max']);
    $max = $arr[2]."-".$arr[1]."-".$arr[0];
    $sql .= "AND informe_datos.FECHA_INSERT <= '{$max}' ";
}

if (!($resultado = $mysqli->query($sql))) {
    echo "Falló SELECT: (" . $mysqli->errno . ") " . $mysqli->error;
}

$title = "Reporte Ultimo Comentario - Fecha de Inicio: {$min}, Termino: {$max}";

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

$spreadsheet->getProperties()->setCreator("Intranet Servicobranza")
							//  ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle($title);
							//  ->setSubject("Office 2007 XLSX Test Document")
                            //  ->setDescription("Min: {$min}, Max: {$max}")
							//  ->setKeywords("office 2007 openxml php")
                            //  ->setCategory("Test result file");
                            
                                      

$spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nro Juicio')
            ->setCellValue('B1', 'Rut')
            ->setCellValue('C1', 'Juzgado')
            ->setCellValue('D1', 'Rol')
            ->setCellValue('E1', 'Comentario')
            ->setCellValue('F1', 'Fecha Comentario')
            ->setCellValue('G1', 'Código de Acción')
            ->setCellValue('H1', 'Código Resultado')
            ->setCellValue('I1', 'Procurador');
            
$spreadsheet->getActiveSheet()->getStyle("F1")->getNumberFormat()->setFormatCode("dd-mm-yyyy");
            
$pos = 1;
while ($resul = $resultado->fetch_row()) {    
    $fia = explode("-", $resul[2]);
    $date = $fia[2]."-".$fia[1]."-".$fia[0];
    if ($date == "--"){
        $date = "";
    }
    else{
        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($date);
    }
    $pos++;
    $spreadsheet->getActiveSheet()->getStyle("F".$pos)->getNumberFormat()->setFormatCode("dd-mm-yyyy");    
    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue("A".$pos, $resul[10])
        ->setCellValue("B".$pos, $resul[11])
        ->setCellValue("C".$pos, $resul[12])
        ->setCellValue("D".$pos, $resul[13])
        ->setCellValue("E".$pos, $resul[4])
        ->setCellValue("F".$pos, $date)
        ->setCellValue("G".$pos, $resul[8])
        ->setCellValue("H".$pos, $resul[9])
        ->setCellValue("I".$pos, $resul[14]);         
}

$spreadsheet->getActiveSheet()->setTitle('Main');
$spreadsheet->setActiveSheetIndex(0);

// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="'.date("YmdHis").'.xlsx"');
// header('Cache-Control: max-age=0');
// header('Cache-Control: max-age=1');
// header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
// header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
// header('Cache-Control: cache, must-revalidate');
// header('Pragma: public');

// $file = date("YmdHis_".uniqid()).'.xlsx';
$file = date("YmdHis").'.xlsx';

$writer = new Xlsx($spreadsheet);
$writer->save($file);

if (file_exists($file)) {
    ob_clean();
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

exit;

?>

