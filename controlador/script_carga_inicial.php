<?php
	require_once "../PHPExcel.php";
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
	for ($sheetRow = 0; $sheetRow <= $sheetCount-1; $sheetRow++) {				
		$worksheet = $excelObj->getSheet($sheetRow);
		$lastRow = $worksheet->getHighestRow();
		echo "<table>";
		for ($row = 2; $row <= $lastRow; $row++) {
			
			echo "<tr><td>";
			echo $worksheet->getCell('A'.$row)->getValue();
			echo "</td><td>";
			echo $worksheet->getCell('C'.$row)->getValue();
			echo "</td><td>";
			echo $worksheet->getCell('AA'.$row)->getValue();
			echo "</td><tr>";
		}
		echo "</table>";	
	}	
?>