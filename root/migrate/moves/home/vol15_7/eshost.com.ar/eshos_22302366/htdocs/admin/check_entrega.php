<?php
	header('Content-Type: text/html; charset=UTF-8');
	session_start();	
	#inicia la conexion con la base
	include("../db/api_db.php");
	  $db_manager = new DB();
	
	if(!empty($_GET['Cancelar'])){
		$where='id_pedido='.$_GET['id_pedido'];
		$db_manager->remove_info_pedido('producto_pedido',$where);
		$db_manager->remove_info_pedido('producto_devo',$where);
		$db_manager->remove_pedido('pedido_p',$where);
		header('Location: index.php?md=true'); 	
	}else{
		if(!empty($_GET['Actualizar'])){
			if((is_numeric($_GET['valor'])) and ($_GET['valor'] >= 0) and ($_GET['cant_producto'] >= 0) and ($_GET['cant_devo'] >= 0))
			{
				$pago=0;
				$db_manager->update_pedido('pedido_p',$_GET['id_pedido'],$_GET['repartidor'],$_GET['valor'],$pago,$db_manager->clear_string($_GET['obser']));	
				header('Location: detalle.php?m=true&v='.base64_encode($_GET['id_pedido'])); 	
			}else{
				header('Location: detalle.php?me=true&v='.base64_encode($_GET['id_pedido'])); 
			}
		}else{
			print('Entro al else del GET');
		}
	}

			
?> 

