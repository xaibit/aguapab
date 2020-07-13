<?php

include("../db/api_db.php");

class ImportCSV
  {
    var $db_manager = new DB();
    
	function importClients($filename)					
    {
		echo "---------- importClients ----------";
        //$filename =  "files/clientExample.csv"; 
		$tableName = "clientes";
		
		echo "filename: $filename.";

		$row = 1;
		
		if (($handle = fopen($filename, "r")) !== FALSE) {
		  while (($data = fgetcsv($handle, ";")) !== FALSE) {
			$num = count($data);
			echo "$num fields in line $row.";
			echo "Procesing row number $row.";
			$row++;
			echo 'A procesar =>>>>>>>>>>>  Nombre: '.$data[0].' Direccion='.$data[1].' Nro='.$data[8]. "\n";
			//$db_manager->limpiar_tabla($tableName);
			$db_manager->addClient($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]
				,$data[10],$data[11],$data[12],$data[13],$data[14],$data[15],$data[16],$data[17],$data[18]);
		  }
		  echo "Process finished: $row rows procesed. ";
		  fclose($handle);
		  unlink($filename);
		  echo "CSV file deleted.";
		}else{
			echo "el archivo $filename no existe.";
		}
    }
}

?>
