<?php
	header('Content-Type: text/html; charset=UTF-8');
	session_start();	
	#inicia la conexion con la base
	include("../db/api_db.php");
	$db_manager = new DB();
	
	if(!empty($_GET['Entregar'])){
		if((is_numeric($_GET['valor'])) and ($_GET['valor'] >= 0))
		{
			$db_manager->update_pedido('pedido_p',$_GET['id_pedido'],$_SESSION["id_user"],$_GET['valor'],0,$db_manager->clear_string($_GET['obser']));
			$fec_entrega = getdate();
			$f_venta = $fec_entrega['year'].'-'.$fec_entrega['mon'].'-'.$fec_entrega['mday'];
			$id_pedido = $_GET['id_pedido'];
			$db_manager->add_pedido_entregado($id_pedido);
			$where = 'id_pedido='.$id_pedido;
			$db_manager->remove_pedido('pedido_p',$where);
			$element="FechaEntregado='".$f_venta."'";
			$db_manager->update_element('pedido_e',$id_pedido,$element);
			header('Location: pedidos.php?m=true'); 	
		}else{
			header('Location: detalle.php?me=true&v='.base64_encode($_GET['id_pedido'])); 	
		}
	}else{
		if(!empty($_GET['Actualizar'])){
			if((is_numeric($_GET['valor'])) and ($_GET['valor'] >= 0))
			{
				if($_GET['pago'] == "pago")
				{
					$pago=true;
				}else{
					$pago=0;
				}
				$db_manager->update_pedido('pedido_p',$_GET['id_pedido'],$_SESSION["id_user"],$_GET['valor'],$pago,$db_manager->clear_string($_GET['obser']));	
				header('Location: detalle.php?m=true&v='.base64_encode($_GET['id_pedido'])); 	
			}else{
				header('Location: detalle.php?me=true&v='.base64_encode($_GET['id_pedido'])); 
			}
		}else{
			if(!empty($_GET['Actualizar_ac'])){
				if((is_numeric($_GET['valor'])) and ($_GET['valor'] >= 0))
				{
					if($_GET['pago'] == "pago")
					{
						$pago=true;
					}else{
						$pago=0;
					}
					$where = "id_pedido = ".$_GET['id_pedido'];
					$pedido = $db_manager->get_element('*','pedido_e',$where);
					$db_manager->update_pedido('pedido_e',$_GET['id_pedido'],$_SESSION["id_user"],$_GET['valor'],$pago,$db_manager->clear_string($_GET['cant_producto']),$pedido['detalle_ped'],$db_manager->clear_string($_GET['cant_devo']),$pedido['detalle_devuelto'],$db_manager->clear_string($_GET['obser']));	
					header('Location: pedidosac.php?m=true&v='.base64_encode($_GET['id_pedido'])); 	
				}else{
					header('Location: detalle.php?ac=true&me=true&v='.base64_encode($_GET['id_pedido'])); 
				}
			}else{
				print('Entro al else del GET');
			}
		}
	}

			
?> 

