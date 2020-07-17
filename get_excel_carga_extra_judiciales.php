<?php 

ini_set('memory_limit','512M');
//error_reporting(0);
date_default_timezone_set('America/Santiago');



use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet as Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Xlsx;

require 'vendor/autoload.php';


include("modelo/conectarBD.php");
include("modelo/consultaSQL.php");
$mysqli = new mysqli($host, $usuario, $password, $basedatos);

$sql = "SELECT * FROM  servicobranza.plano200 as sp inner join servicobranza.vcdials as vc ON sp.VCDIAL = vc.cod_vcdial WHERE ";

if (trim($_GET['min']) != "") {

    $arr = explode("/", $_GET['min']);
    $min = $arr[2]."-".$arr[1]."-".$arr[0];

    $sql .= "DATE(FECHINGRESO) >= '{$min}' ";
}

if (trim($_GET['max']) != "") {
    $arr = explode("/", $_GET['max']);
    $max = $arr[2]."-".$arr[1]."-".$arr[0];
    $sql .= "AND DATE(FECHINGRESO) <= '{$max}' ";
}

if($_GET['accion'] != "" && $_GET['accion'] != "undefined" && $_GET['accion'] != "null"){
    $sql .= "AND CODIGOACCION = '{$_GET['accion']}' ";
}

if($_GET['respuesta'] != "" && $_GET['respuesta'] != "undefined" && $_GET['accion'] != "null"){
    $sql .= "AND RESULTADO = '{$_GET['respuesta']}' ";
}



$title = "Reporte Carga extra Judicial - Fecha de Inicio: {$min}, Termino: {$max}";

$resultado = $mysqli->query($sql);




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
            ->setCellValue('A1', 'CUENTA')
            ->setCellValue('B1', 'FECHA')
            ->setCellValue('C1', 'HORA')
            ->setCellValue('D1', 'CODIGOACCION')
            ->setCellValue('E1', 'NOM_ACCION')
            ->setCellValue('F1', 'RESULTADO')
            ->setCellValue('G1', 'NOM_RESPUESTA')
            ->setCellValue('H1', 'CODIGOCARTA')
            ->setCellValue('I1', 'COMENTARIO')
            ->setCellValue('J1', 'TELEFONO')
            ->setCellValue('K1', 'IDGESTOR')
            ->setCellValue('L1', 'FECHINGRESO');

            
$spreadsheet->getActiveSheet()->getStyle("F1")->getNumberFormat()->setFormatCode("dd-mm-yyyy");




$pos = 1;
while ($resul = $resultado->fetch_assoc()) {    
    $fia = explode("-", $resul['FECHINGRESO']);
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
        ->setCellValue("A".$pos, $resul['CUENTA'])
        ->setCellValue("B".$pos, $resul['FECHA'])
        ->setCellValue("C".$pos, $resul['HORA'])
        ->setCellValue("D".$pos, $resul['CODIGOACCION'])
        ->setCellValue("E".$pos, $resul['nom_accion'])
        ->setCellValue("F".$pos, $resul['RESULTADO'])
        ->setCellValue("G".$pos, $resul['nom_respuesta'])
        ->setCellValue("H".$pos, $resul['CODIGOCARTA'])
        ->setCellValue("I".$pos, $resul['COMENTARIO'])
        ->setCellValue("J".$pos, $resul['TELEFONO'])
        ->setCellValue("K".$pos, $resul['IDGESTOR'])
        ->setCellValue("L".$pos, $resul['FECHINGRESO']);         
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
