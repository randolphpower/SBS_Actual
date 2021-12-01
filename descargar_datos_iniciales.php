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
if(!session_id()) session_start();

$fecha = $_GET["fecha"];
if ($fecha != "0"){
   $fecha = split("/", $fecha);
   $fecha = "{$fecha[2]}-{$fecha[1]}-{$fecha[0]}";
   $sql = "SELECT * FROM juicios_dato_inicial WHERE fecha_asignacion = '".$fecha."'";
} else {
   $fecha = "Todas";
   $sql = "SELECT * FROM juicios_dato_inicial";
}

$resultados = mysql_query($sql, $conexion) or die(mysql_error());
mysql_close($conexion);
//$var .= $reg["id_juicio"].";".$reg["rut"].";".$reg["nombre"].";".$reg["cuenta"].";".$reg["tipo_juicio"].";".$reg["fecha_asignacion"].";".$reg["monto"].";\n";

$i++;
$i=1;
$var = "Id Juicio;Rut Deudor;Nombre Deudor;Cuenta;Tipo Juicio;Asignacion;Monto Deuda;\n";


$title = "Carga Asignaciones".$fecha;
$spreadsheet = new Spreadsheet();

$spreadsheet->getProperties()->setCreator("Intranet Servicobranza")
							        ->setTitle($title);                                      

$spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID JUICIO')
            ->setCellValue('B1', 'RUT')
            ->setCellValue('C1', 'NOMBRE')
            ->setCellValue('D1', 'CUENTA')
            ->setCellValue('E1', 'TIPO JUICIO')
            ->setCellValue('F1', 'FECHA ASIGNACION')
            ->setCellValue('G1', 'MONTO');

$spreadsheet->getActiveSheet()->getStyle("F1")->getNumberFormat()->setFormatCode("dd-mm-yyyy");

$pos = 1;
while ($reg = mysql_fetch_array($resultados))
{
   $pos++;
   $spreadsheet->getActiveSheet()->getStyle("F".$pos)->getNumberFormat()->setFormatCode("dd-mm-yyyy");
   $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue("A".$pos, $reg["id_juicio"])
        ->setCellValue("B".$pos, $reg['rut'])
        ->setCellValue("C".$pos, $reg['nombre'])
        ->setCellValue("D".$pos, $reg['cuenta'])
        ->setCellValue("E".$pos, $reg['tipo_juicio'])
        ->setCellValue("F".$pos, $reg['fecha_asignacion'])
        ->setCellValue("G".$pos, $reg['monto']); 
}

$spreadsheet->getActiveSheet()->setTitle('Asignaciones');
$spreadsheet->setActiveSheetIndex(0);
$file = 'Asignaciones'.date("YmdHis").'.xlsx';

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
/*
header("Content-Description: File Transfer");
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=Asignaciones_".$fecha.".xls");
echo $var;
*/
?>
