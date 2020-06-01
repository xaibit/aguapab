<?php
//Formato del csv esperado: campos separados por "," y sin la primer fila con los nombres de los campos.

$filename = $_GET['f'];

    //como el user no está logueado, no me queda otra que crear una nueva connection a la DB.
    include("../db/api_db.php");
  	$_SESSION["db"][0] = "sql205.eshost.com.ar";//"localhost"; //host
	$_SESSION["db"][1] = "eshos_25581025";//"aguapabr_aguapab"; //user db
	$_SESSION["db"][2] = "aguapab"; //pass db
	$_SESSION["db"][3] = "eshos_25581025_aguapabr_aguapab"; //base name
	$db_manager2 = new DB();

    $handleLogFile = fopen("logs/".date('Y.m.d')."_".date('h.i.sa')."_csvImportLogFile.txt", "w") or die("Unable to open file!");     
    fwrite($handleLogFile, "Today is " . date('Y/m/d'). " The time is " . date('h:i:sa') . "\n");
    fwrite($handleLogFile, "Importando clientes desde el archivo: $filename.");
    
        

    	$tableName = "Clientes_tmp";
        $backupTableName = "Clientes_back";
        $destinyTableName = "Clientes";
        echo "<br>"; echo "Importando clientes desde el archivo: $filename.";
		$row = 0;
        $resIden;
		if (($handle = fopen($filename, "r")) !== FALSE) {

          $db_manager2->limpiar_tabla($tableName);		  
          while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
			if($data[0] == "") 
                break;
            $row++;
            $num = count($data);
			fwrite($handleLogFile, "Procesing row number $row. \n");
            fwrite($handleLogFile, "$num fields in line $row. \n");
			
            //esto viene en el csv:
            //"Nombre","Direccion","CUIT","IVA","descuento","Localidad","CodPostal","Nro","BORRADO","email","lista","calleZ","Altura","Obs","Credito","PERIB","PER_IB_FECHA_EXENTO","RETIB","RET_IB_FECHA_EXENTO"

            
			fwrite($handleLogFile, "A procesar =>>>>>>>>>>>  \n Nombre: ".$data[0].
            "\n Direccion=".$data[1].
            "\n cuit=".$data[2].
            "\n iva=".$data[3].
            "\n descuento=".$data[4].
            "\n localidad=".$data[5].
            "\n codPostal=".$data[6].
            "\n nro_cliente=".$data[7].
            "\n borrado=".$data[8].
            "\n mail=".$data[9].
            "\n lista=".$data[10].
            "\n calleZ=".$data[11].
            "\n altura=".$data[12].
            "\n obs=".$data[13].
            "\n credito=".$data[14].
            "\n perib=".$data[15].
            "\n perIbFechaExento=".$data[16].
            "\n retrib=".$data[17].
            "\n retIbFechaExento=".$data[18].
            "\n");
            

            //esto espera el addClient de la DB:
            //$tableName, $nombre, $dir, $cuit, $iva, $descuento, $localidad, $codPostal, $nro_cliente, $borrado, $mail, $lista, $calleZ, $altura, 
			//		   $obs, $credito, $perib, $perIbFechaExento, $retrib, $retIbFechaExento
            //ejemplo
            //"CONSUMIDOR FINAL","CONSUMIDOR FINAL","00-00000000-0",5,0,00,"9 de julio","00000",1,0,"no tiene",0,1,0,,,"0",2/7/2015 10:22:05,"0",2/7/2015 10:22:05
           
            $insertionResult = $db_manager2->addClient($resIden, $tableName, $data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9]
				,$data[10],$data[11],$data[12],$data[13],$data[14],$data[15],$data[16],$data[17],$data[18]);
            fwrite($handleLogFile,"Insertion result. $insertionResult \n");
            fwrite($handleLogFile,"Row processed. \n");
            
		  }
          echo "<br>"; echo "Procesp terminado: $row clientes ingresados.";
          fwrite($handleLogFile, "Process finished: $row rows procesed. \n");
            
          fwrite($handleLogFile, "Haciendo backup de la tabla $destinyTableName en la tabla $backupTableName. \n");
          $db_manager2->limpiar_tabla($backupTableName);	
          $resultadoBackup = $db_manager2->copy_table($destinyTableName, $backupTableName);
          fwrite($handleLogFile, "Backup finalizado con estado: ". $resultadoBackup ." \n");

          fwrite($handleLogFile, "Moviendo info de la tabla $tableName a $destinyTableName . \n");
          $db_manager2->limpiar_tabla($destinyTableName);	
          $resultadoMovimiento = $db_manager2->copy_table($tableName, $destinyTableName);
          fwrite($handleLogFile, "Movimiento finalizado con estado: ". $resultadoMovimiento ."\n");
            
		  fclose($handle);
		  unlink($filename);
		  fwrite($handleLogFile, "CSV file deleted. \n");
		}else{
            fwrite($handleLogFile, "el archivo $filename no existe. \n");
			echo "<br>";echo "el archivo $filename no existe.";
 		}
    fclose($handleLogFile);    

?>