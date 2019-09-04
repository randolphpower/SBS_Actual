<?php

ini_set('memory_limit','512M');
error_reporting(0);
date_default_timezone_set('America/Santiago');
session_start();

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet as Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Xlsx;

require 'vendor/autoload.php';

// require_once __DIR__ . '/tmp/PhpSpreadsheet/src/Bootstrap.php';

include("modelo/conectarBD.php");
// include("modelo/consultaSQL.php");

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

$sql =  "SELECT ";
if ($v) {
    $sql .= "MAX(informe_datos.id),";
}
$sql .= "relacion_cliente_juicio.NUM_JUICIO,";
$sql .= "relacion_cliente_juicio.ID_CLIENTE,";
$sql .= "relacion_cliente_juicio.CECRTID,";
$sql .= "relacion_cliente_juicio.CEDOSSIERID,";
$sql .= "(SELECT etapas_procesales.CD_DESC ";
$sql .= "	FROM etapas_procesales ";
$sql .= "	WHERE etapas_procesales.CD_TYPE = op_eta_proce.CSTYPE AND etapas_procesales.CD_STGID = op_eta_proce.CSSTGID) AS DESC_STGID, ";    
$sql .= "op_eta_proce.CSSTDT,"; // fecha inicio
$sql .= "op_eta_proce.CSENDDT,"; // fecha fin
$sql .= "(SELECT codigo_accion.DESCRIPCION FROM codigo_accion WHERE codigo_accion.CODIGO = op_200_gestiones.ACACCODE) AS DESC_CODE,";
$sql .= "op_200_gestiones.ACCOMN,";
$sql .= "op_200_gestiones.DATE,";
$sql .= "op_gastos.EXDESC,";
$sql .= "op_gastos.EXAMT,";
$sql .= "op_gastos.EXINVOICE,";
$sql .= "op_gastos.EXAUTDT,";
$sql .= "informe_datos.FECHA_INSERT,";
$sql .= "op_gastos.EXSUPPLIER ";
$sql .= "FROM informe_datos "; // table base
$sql .= "LEFT JOIN op_eta_proce ON informe_datos.ID_ETA_PROCE = op_eta_proce.id ";
$sql .= "LEFT JOIN op_200_gestiones ON informe_datos.ID_200_GESTION = op_200_gestiones.id ";
$sql .= "LEFT JOIN op_gastos ON informe_datos.ID_GASTOS = op_gastos.id ";
$sql .= "LEFT JOIN relacion_cliente_juicio ON informe_datos.ID_JUICIO = relacion_cliente_juicio.NUM_JUICIO ";
// $sql .= "ORDER BY informe_datos.FECHA_INSERT DESC";

// filter by min & max
$sql .= "WHERE True ";

if ($reg_fil == 0) {
    $sql .= "AND (";
    $sql .= "op_eta_proce.USUSUARIO = '".$_SESSION['username']."' ";
    $sql .= "OR op_200_gestiones.USUSUARIO = '".$_SESSION['username']."' ";
    $sql .= "OR op_gastos.USUSUARIO ='".$_SESSION['username']."') ";
}

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

if ($v) {
    $sql .= "GROUP BY informe_datos.ID_JUICIO ";
}

// $sql .= "ORDER BY informe_datos.FECHA_INSERT DESC";
$sql .= "ORDER BY informe_datos.FECHA_INSERT DESC, op_gastos.EXSUPPLIER ASC";

// $sql = "LIMIT 0,100";
// echo $sql;
$q = mysql_query($sql, $conexion) or die(mysql_error());

// echo mysql_errno($conexion) . ": " . mysql_error($conexion) . "\n";
// exit;

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
            ->setCellValue('E1', 'Iden. Etapa')
            ->setCellValue('F1', 'Fecha Inicio')
            ->setCellValue('G1', 'Fecha Fin')
            ->setCellValue('H1', 'Codigo Accion')
            ->setCellValue('I1', 'Comentario')
            ->setCellValue('J1', 'Fecha')
            ->setCellValue('K1', 'Desc. del Gasto')
            ->setCellValue('L1', 'Monto Gasto')
            ->setCellValue('M1', 'Nro Factura')
            ->setCellValue('N1', 'Fecha Autorizacion')
            ->setCellValue('O1', 'Fecha Inserción')
            ->setCellValue('P1', 'Nombre Proveedor');
            
            
$pos = 1;
while($row = mysql_fetch_array($q)) { 

    $fia = explode("-", $row["FECHA_INSERT"]);
    $fecha_insert = $fia[2]."/".$fia[1]."/".$fia[0];

    $fia = explode("-", $row["CSSTDT"]);
    $csstdt = $fia[2]."/".$fia[1]."/".$fia[0];

    $fia = explode("-", $row["CSENDDT"]);
    $csenddt = $fia[2]."/".$fia[1]."/".$fia[0];

    $fia = explode("-", $row["DATE"]);
    $date = $fia[2]."/".$fia[1]."/".$fia[0];

    $pos++;
    $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A".$pos, $row["NUM_JUICIO"])
            ->setCellValue("B".$pos, $row["ID_CLIENTE"])
            ->setCellValue("C".$pos, $row["CECRTID"])
            ->setCellValue("D".$pos, $row["CEDOSSIERID"])
            ->setCellValue("E".$pos, $row["DESC_STGID"])
            ->setCellValue("F".$pos, $csstdt)
            ->setCellValue("G".$pos, $csenddt)
            ->setCellValue("H".$pos, $row["DESC_CODE"])
            ->setCellValue("I".$pos, $row["ACCOMN"])
            ->setCellValue("J".$pos, $date)
            ->setCellValue("K".$pos, $row["EXDESC"])
            ->setCellValue("L".$pos, $row["EXAMT"])
            ->setCellValue("M".$pos, $row["EXINVOICE"])
            ->setCellValue("N".$pos, $row["EXAUTDT"])
            ->setCellValue("O".$pos, $fecha_insert)
            ->setCellValue("P".$pos, $row["EXSUPPLIER"]);
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