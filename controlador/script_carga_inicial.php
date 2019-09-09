<?php
	session_start();
	require_once("../modelo/consultaSQL.php");
	require_once("../modelo/conectarBD.php");
	require_once("../PHPExcel.php");
	$tipo = $_FILES['archivo']['type'];
	$tamanio = $_FILES['archivo']['size'];
	$tmpfname = $_FILES['archivo']['tmp_name'];
	
	//$tmpfname = "C:\AppServ\www\servicobranza_actual\Interfaz Servicob 26-07-2019.xlsx";
	//$url = "http://spreadsheetpage.com/downloads/xl/worksheet%20functions.xlsx";
	//$filecontent = file_get_contents($url);
	//$tmpfname = tempnam(sys_get_temp_dir(),"tmpxls");
	//file_put_contents($tmpfname,$filecontent);
	
	$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
	$excelObj = $excelReader->load($tmpfname);
	$sheetCount = $excelObj->getSheetCount();
	$i = 0;
	for ($sheetRow = 0; $sheetRow <= $sheetCount-1; $sheetRow++) {				
		$worksheet = $excelObj->getSheet($sheetRow);
		$lastRow = $worksheet->getHighestRow();

		for ($row = 2; $row <= $lastRow; $row++) {
			
			$id_juicio = $worksheet->getCell('A'.$row)->getValue();
			$tipo_juicio = $worksheet->getCell('AA'.$row)->getValue();
			$rut = $worksheet->getCell('C'.$row)->getValue();
			
			if ($tipo_juicio != "") {
				$sql_search = "SELECT id FROM juicios_dato_inicial WHERE id_juicio = ".$id_juicio." and tipo_juicio = '".$tipo_juicio."';";				
				$datos = call_select($sql_search, "");			
				$id_tabla = mysql_fetch_array($datos['registros'])['id'];
				//echo $id_tabla."</br>";
				if ($datos['num_filas'] == 0) { // INSERT
					$sql = "INSERT INTO juicios_dato_inicial (id_juicio, tipo_juicio, rut)  ";
					$sql .= "VALUES (".$id_juicio.",'".$tipo_juicio."','".$rut."');";
					call_insert($sql, "");

					$arrnumjuicio[$i-1] = $id_juicio;
					$arrrutcliente[$i-1] = $rut;
					$arrtipojuicio[$i-1] = $tipo_juicio;

				} else { // UPDATE
					$sql = "UPDATE juicios_dato_inicial SET ";
					$sql .= "tipo_juicio='".$tipo_juicio."', ";				
					$sql .= "rut='".$rut."' ";				
					$sql .= "WHERE (id='".$id_tabla."') ";
					call_update($sql);

					$arrnumjuicio[$i-1] = $id_juicio;
					$arrrutcliente[$i-1] = $rut;
					$arrtipojuicio[$i-1] = $tipo_juicio;
				 }
			}	
			$i++;		
		}			
	}	

	$print = "<table class='table table-striped table-bordered table-hover'>";
	$print .= "<thead>";
	$print .= "<tr align='center' class='info text-center text-default' style='vertical-align:middle'>";
	$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Nro.</th>";
	$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Identificador</th>";
	$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Rut Cliente</th>";
	$print .= "<th class='col-md-1 text-center' style='vertical-align:middle'>Tipo Juicio</th>";
	$print .= "</tr>";
	$print .= "</thead>";
	$print .= "<tbody>";

	$j=1;
	$count = count($arrnumjuicio);
	echo $count;
	for ($i = 0; $i < $count; $i++) {
		$print .= "<tr class='text-center text-muted' data-placement='top'>";
		$print .= "<td class='text-center' style='vertical-align:middle'>".$j."</td>";
   		$print .= "<td class='text-center' style='vertical-align:middle'>".$arrnumjuicio[$i-1]."</td>";
		$print .= "<td class='text-center' style='vertical-align:middle'>".$arrrutcliente[$i-1]."</td>";
   		$print .= "<td class='text-center' style='vertical-align:middle'>".$arrtipojuicio[$i-1]."</td>";
   		$print .= "</tr>";
		$j=$j+1;
	}   
   	$print .= "</tbody>";
	$print .= "</table>";	
	header('Location: ../carga_inicial_resultado.php?resultado='.$print);
?>
