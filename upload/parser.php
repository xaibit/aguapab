<?php


$filename = $_GET['f'];

    //como el user no está logueado, no me queda otra que crear una nueva connection a la DB.
    include("../db/api_db.php");
  	$_SESSION["db"][0] = "sql205.eshost.com.ar";//"localhost"; //host
	$_SESSION["db"][1] = "eshos_25581025";//"aguapabr_aguapab"; //user db
	$_SESSION["db"][2] = "aguapab"; //pass db
	$_SESSION["db"][3] = "eshos_25581025_aguapabr_aguapab"; //base name
	$db_manager2 = new DB();


    	$tableName = "Clientes";
		echo "<br>"; echo "Importando clientes desde el archivo: $filename.";
		$row = 0;
        
		if (($handle = fopen($filename, "r")) !== FALSE) {
		  while (($data = fgetcsv($handle, ";")) !== FALSE) {
			if($data[0] == "") 
                break;
            $row++;
            $num = count($data);
			echo "<br>"; echo "Procesing row number $row.";
            echo "<br>"; echo "$num fields in line $row.";
			
			echo "<br>";echo 'A procesar =>>>>>>>>>>>  \n Nombre: '.$data[0].'\n Direccion='.$data[1].'\n Nro='.$data[8];
            //echos para debug:
            //echo '\n Nro='.$data[3].'\n Nro='.$data[4].'\n Nro='.$data[5].'\n Nro='.$data[6].'\n Nro='.$data[7];
            //echo '\n Nro='.$data[8].'\n Nro='.$data[9].'\n Nro='.$data[10].'\n Nro='.$data[11].'\n Nro='.$data[12];
            //echo '\n Nro='.$data[13].'\n Nro='.$data[14].'\n Nro='.$data[15].'\n Nro='.$data[11].'\n Nro='.$data[16];
            //cho '\n Nro='.$data[17].'\n Nro='.$data[18];

			$db_manager2->limpiar_tabla($tableName);
			$db_manager2->addClient($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]
				,$data[10],$data[11],$data[12],$data[13],$data[14],$data[15],$data[16],$data[17],$data[18]);
            echo "<br>";echo "Row processed. "."\n";
		  }
          echo "<br>";echo "Process finished: $row rows procesed. ";
		  fclose($handle);
		  unlink($filename);
		  echo "<br>";echo "CSV file deleted.";
		}else{
			echo "<br>";echo "el archivo $filename no existe.";
		}
        

?>