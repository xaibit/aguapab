<?php
  class DB
  {
    var $iden;

    function __construct()
    {
     $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
     $iden->set_charset("utf8");
    }

   function conect()
   {
     return mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
   }

    function clear_string($element)
    {	 	
        $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
        $iden->set_charset("utf8");
	return $iden->escape_string($element);
    }	

    ################################## PERSONAL ########################################################################### 	
    function check_login($where)
    {
     $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
     $iden->set_charset("utf8");
     $sentencia = "SELECT *  FROM personal WHERE $where;"; 
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
      if(!$resultado) 
       die("Error: no se pudo realizar la consulta". " ");
      return $resultado->num_rows;
    }

    function get_element($element,$table,$where)
    {
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
      $iden->set_charset("utf8");
      $sentencia = "SELECT $element FROM $table WHERE $where LIMIT 1;"; 
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
      if(!$resultado) 
       die("Error: no se pudo realizar la consulta". " ");
      return $resultado->fetch_assoc();
    }  
    
    function get_suma($table,$where)
    {
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]); 
      $iden->set_charset("utf8");
      $sentencia = "SELECT SUM(valor) AS total FROM $table WHERE $where;"; 
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
      if(!$resultado) 
       die("Error: no se pudo realizar la consulta". " ");
      return $resultado->fetch_assoc();
    } 	

    function get_element_order($table,$where,$order,$inicio,$registros)
    {
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
      $iden->set_charset("utf8");
      $sentencia = "SELECT * FROM $table WHERE $where ORDER BY $order LIMIT ".$inicio." , ".$registros.";";  
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
      if(!$resultado) 
       die("Error: no se pudo realizar la consulta". " ");
      return $resultado;
    } 	

    function get_all_element_order($table,$where,$order)
    {
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
      $iden->set_charset("utf8");
      $sentencia = "SELECT * FROM $table WHERE $where ORDER BY $order;";  
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
      if(!$resultado) 
       die("Error: no se pudo realizar la consulta". " ");
      return $resultado;
    } 
	

    function get_max_pedido($table,$where)
    {
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
      $iden->set_charset("utf8");
      $sentencia = "SELECT MAX(id_pedido) AS id_pedido FROM $table WHERE $where;";  
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
      if(!$resultado) 
       die("Error: no se pudo realizar la consulta de max". " ");
      return $resultado->fetch_assoc();
    } 

    function total($table,$where)
    {
        $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
        $iden->set_charset("utf8");
 	$sentencia = "SELECT * FROM $table WHERE $where";
	$resultado = $iden->query($sentencia);
      	if(!$resultado) 
       		die("Error: no se pudo realizar la consulta". " ");
      	return  $resultado->num_rows;
    }	

    function update_pedido($tabla,$id,$idpersona,$valor,$pago,$obser)
    {
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
      $iden->set_charset("utf8");
      $sentencia = "UPDATE $tabla SET id_persona=$idpersona,valor=$valor, pago=$pago, observ='$obser'  WHERE id_pedido=$id LIMIT 1;"; 
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
      if(!$resultado) 
       die("Error: no se pudo realizar la actualizacion". " ");
    } 	

    function update_element($tabla,$id,$element)
    {
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
      $iden->set_charset("utf8");
      $sentencia = "UPDATE $tabla SET $element WHERE id_pedido=$id LIMIT 1;"; 
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
      if(!$resultado) 
       die("Error: no se pudo realizar la actualizacion". " ");
    } 	  	

    function remove_pedido($table,$where)
    {	
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
      $iden->set_charset("utf8");
      $sentencia = "DELETE FROM $table WHERE $where LIMIT 1;"; 
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
      if(!$resultado) 
       die("Error: no se pudo realizar el delete". " ");
    }
    
    function remove_info_pedido($table,$where)
    {	
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
      $iden->set_charset("utf8"); 
      $sentencia = "DELETE FROM $table WHERE $where;"; 
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
      if(!$resultado) 
       die("Error: no se pudo realizar el delete". " ");
    }

    function add_pedido_entregado($id_pedido)
    {
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
      $iden->set_charset("utf8");
      $sentencia = "INSERT INTO pedido_e (id_pedido,id_persona,FechaPedido,FechaEntregado,DirEntrega,nrocliente,valor,pago,observ) SELECT * FROM pedido_p WHERE id_pedido=$id_pedido;"; 
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
      if(!$resultado) 
       die("Error: no se pudo ejecutar ". " ");
      return $resultado; 
    }	

    function add_pedido($tabla,$repartidor,$nro,$direccion,$valor,$fecha,$pago,$obser)
    {
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
      $iden->set_charset("utf8");
      if($tabla == 'pedido_e'){	
	$sentencia = "INSERT INTO pedido_e (id_persona,FechaPedido,FechaEntregado,DirEntrega,nrocliente,valor,pago,observ) VALUES ($repartidor,'$fecha','$fecha','$direccion',$nro,$valor,$pago,'$obser')"; 
      }else{
	$sentencia = "INSERT INTO pedido_p (id_persona,FechaPedido,DirEntrega,nrocliente,valor,pago,observ) VALUES ($repartidor,'$fecha','$direccion',$nro,$valor,$pago,'$obser')";
      }	 
      // Ejecuta la sentencia SQL 
        $resultado = $iden->query($sentencia);
        if(!$resultado) 
         die("Error: no se pudo ejecutar el add". " ");
        return $resultado;
    } 

    function add_info_pedido($tabla,$id_pedido,$cantidad,$detalle)
    {
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
       $iden->set_charset("utf8");
      if($tabla == 'producto_pedido'){	
	$sentencia = "INSERT INTO $tabla (id_pedido,cant_ped,detalle_ped) VALUES ($id_pedido,$cantidad,'$detalle')"; 
      }else{
	$sentencia = "INSERT INTO $tabla (id_pedido,cant_devuelto,detalle_devuelto) VALUES ($id_pedido,$cantidad,'$detalle')"; 
      }	 
	// Ejecuta la sentencia SQL 
        $resultado = $iden->query($sentencia);
        if(!$resultado) 
         die("Error: no se pudo ejecutar el add de info". " ");
        return $resultado;
    }  				

    ##############################################################################################################################	
    function limpiar_tabla($tabla)
    {
      $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
      $iden->set_charset("utf8");
      $sentencia = "truncate $tabla;"; 
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
      if(!$resultado) 
       die("\n Error: no se pudo reiniciar la tabla corriendo $tabla". " ");
      return $resultado; 
    }	

    function close_conexion()					
    {
        $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
	$iden->close($iden);
    }	

    function addClient(&$oldIden,$tableName, $nombre, $dir, $cuit, $iva, $descuento, $localidad, $codPostal, $nro_cliente, $borrado, $mail, $lista, $calleZ, $altura, 
					   $obs, $credito, $perib, $perIbFechaExento, $retrib, $retIbFechaExento)
    {
        if ($oldIden == null){
             $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
             $iden->set_charset("utf8");
             $oldIden = $iden;
        } else {
            $iden = $oldIden;
        }

		//mysql example
		//INSERT INTO clientes (Nombre, Direccion, CUIT, IVA, descuento, Localidad, CodPostal, Nro, BORRADO, email, lista, calleZ, Altura, Obs, Credito, PERIB, PER_IB_FECHA_EXENTO, RETIB, //RET_IB_FECHA_EXENTO) VALUES ("CONSUMIDOR FINAL", "CONSUMIDOR FINAL", 00-00000000-0, 5, 0, "9 de julio", 00000, 10001,0, "no tiene", 0, 1, 0, "SIN OBS", 0, 0, "2/7/2015 10:22:05", 0, "2/7/2015 //10:22:05")

        if ($nombre == "")
            $nombre = "CONSUMIDOR FINAL";
        if ($dir == "")
            $dir = "SIN CALLE 0";
        if ($cuit == "")
            $cuit = "00-00000000-0";
        if ($iva == "")
            $iva = "1";
        if ($descuento == "")
            $descuento = "0";
        if ($Loclocalidadalidad == "")
            $localidad = "Falta";
        if ($codPostal == "")
            $codPostal = "Falta";
        if ($nro_cliente == "")
            $nro_cliente = "Falta";
        if ($borrado == "")
            $borrado = "FALSO";
        if ($email == "")
            $email = "NO TIENE";
        if ($lista == "")
            $lista = "0";
        if ($calleZ == "")
            $calleZ = 0; 
        if ($altura == "")
            $altura = "0";
        if (empty($obs) or strlen($obs) == 0)
            $obs = "SIN OBS";
        if (empty($credito) or strlen($credito) == 0)
            $credito = "0";
        if ($perib == "")
            $perib = "0";
        if ($PER_IB_FECHA_EXENTO == "")
            $perIbFechaExento = "42187 432";
        if ($retrib == "")
            $retrib = "0";
        if ($retIbFechaExento == "")
            $retIbFechaExento = "42187 432";
        

	    $sentencia = "INSERT INTO $tableName (Nombre, Direccion, CUIT, IVA, descuento, Localidad, CodPostal, Nro, BORRADO, email, lista, calleZ, Altura, Obs, Credito, PERIB, PER_IB_FECHA_EXENTO, RETIB, RET_IB_FECHA_EXENTO) VALUES         ('$nombre', '$dir', '$cuit', '$iva', '$descuento', '$localidad', '$codPostal', '$nro_cliente', '$borrado', '$mail', '$lista', $calleZ, '$altura', '$obs', '$credito', '$perib', '$perIbFechaExento', '$retrib', '$retIbFechaExento') "; 
		//echo para debug:			   
		//echo "query: --------  $sentencia ---------- ";
      
      // Ejecuta la sentencia SQL 
      $resultado = $iden->query($sentencia);
	  	
      if(!$resultado)
         die("\n Error: no se pudo agregar el cliente ". $nombre . ". La base de datos no ha sido modificada. ");
      return $resultado;
    }	
	
    function table_backup($table)					
    {
        if($this->copy_table($table, $table.'_back')) {
            echo "Backup at table: " . $table ." -> Success";
            return "success";
        }
        else {
            echo "Backup at table: " . $table ." -> FAIL!";
            return "fail";
        }
    }

    function copy_table($from, $to) 
    {
        $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
        $iden->set_charset("utf8");
        /*
        $from = "Clientes";
        $to = "Clientes_BKP";

        if(! $this->table_exists($to)) {
             $sentencia = "CREATE TABLE $to LIKE $from;"; 
             $resultado = $iden->query($sentencia);
        }*/
        $sentencia = "INSERT INTO $to SELECT * FROM $from;"; 
        $resultado = $iden->query($sentencia);
        if(!$resultado) 
            die("\n Error: no se pudo hacer backup de la tabla: ". $from );
        return $resultado; 
        
    }

    function table_exists($tablename) 
    {
        $iden = mysqli_connect($_SESSION["db"][0], $_SESSION["db"][1], $_SESSION["db"][2],$_SESSION["db"][3]);
        $iden->set_charset("utf8");

        //$sentencia = "SELECT DATABASE();"; 
        $database = $_SESSION["db"][3];//$iden->query($sentencia);
        echo "datbasename:" . $database;
        $sentencia = "SELECT COUNT(*) AS count
            FROM information_schema.tables
            WHERE table_schema = '$database'
            AND table_name = '$tablename'";
        $res = $iden->query($sentencia);
    
        return ($res == 1);
    }


	
  
  } // class DB
?>
