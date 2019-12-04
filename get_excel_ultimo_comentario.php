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

$v = trim($_GET['v']);
if ($v == "1") {
    $v = True;
} else {
    $v = False;
}

$sql =  "SELECT DISTINCT(op_200_gestiones.ACACCT) AS ACACCT ";
$sql .= "FROM op_200_gestiones ";
$sql .= "INNER JOIN informe_datos ";
$sql .= "ON op_200_gestiones.id = informe_datos.ID_200_GESTION ";
$sql .= "WHERE True ";

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

$q = call_select($sql, "") or die(mysql_error());

$title = "Reporte Documentos - Fecha de Inicio: {$min}, Termino: {$max}";

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
            ->setCellValue('E1', 'Codigo Accion')
            ->setCellValue('F1', 'Accion')
            ->setCellValue('G1', 'Codigo Respuesta')
            ->setCellValue('H1', 'Respuesta')
            ->setCellValue('I1', 'Comentario')
            ->setCellValue('J1', 'Fecha Comentario');
            
$spreadsheet->getActiveSheet()->getStyle("J1")->getNumberFormat()->setFormatCode("dd-mm-yyyy");
            
$pos = 1;
while($row = mysql_fetch_array($q['registros'])) { 
    
    $sql = "SELECT op_200_gestiones.ACACCODE AS CODIGO_ACCION,codigo_accion.DESCRIPCION AS ACCION, ";
    $sql .= "op_200_gestiones.ACRCCODE AS CODIGO_RESPUESTA, ";
    $sql .= "codigo_result.DESCRIPCION AS RESPUESTA, op_200_gestiones.ACCOMN AS COMENTARIO,op_200_gestiones.DATE ";
    $sql .= "FROM op_200_gestiones ";
    $sql .= "INNER JOIN codigo_accion ";
    $sql .= "ON op_200_gestiones.ACACCODE = codigo_accion.CODIGO ";
    $sql .= "INNER JOIN codigo_result ";
    $sql .= "ON op_200_gestiones.ACRCCODE = codigo_result.CODIGO ";
    $sql .= "WHERE ACACCT='".$row["ACACCT"]."' ";
    $sql .= "ORDER BY DATE DESC ";
    $sql .= "LIMIT 1;";        
    $datoComentario = call_select($sql, "");
    while($resulComentario=mysql_fetch_array($datoComentario['registros'])){        
        $sql = "SELECT NUM_JUICIO,ID_CLIENTE,CECRTID,CEDOSSIERID ";
        $sql .= "FROM relacion_cliente_juicio  ";
        $sql .= "WHERE NUM_JUICIO=".substr($row["ACACCT"],1);
        $datoJuicio = call_select($sql, "");
        while($resulJuicio=mysql_fetch_array($datoJuicio['registros'])){
            $fia = explode("-", $resulComentario["DATE"]);
            $date = $fia[2]."-".$fia[1]."-".$fia[0];
            if ($date == "--"){
                $date = "";
            }
            else{
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($date);
            }
            $pos++;
            $spreadsheet->getActiveSheet()->getStyle("J".$pos)->getNumberFormat()->setFormatCode("dd-mm-yyyy");    
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A".$pos, substr($row["ACACCT"],1))
                ->setCellValue("B".$pos, $resulJuicio["ID_CLIENTE"])
                ->setCellValue("C".$pos, $resulJuicio["CECRTID"])
                ->setCellValue("D".$pos, $resulJuicio["CEDOSSIERID"])
                ->setCellValue("E".$pos, $resulComentario["CODIGO_ACCION"])
                ->setCellValue("F".$pos, $resulComentario["ACCION"])
                ->setCellValue("G".$pos, $resulComentario["CODIGO_RESPUESTA"])
                ->setCellValue("H".$pos, $resulComentario["RESPUESTA"])
                ->setCellValue("I".$pos, $resulComentario["COMENTARIO"])
                ->setCellValue("J".$pos, $date);         
        }
    }
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

