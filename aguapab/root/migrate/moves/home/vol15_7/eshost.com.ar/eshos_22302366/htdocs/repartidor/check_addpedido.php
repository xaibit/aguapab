<?php
	header('Content-Type: text/html; charset=UTF-8');
	session_start();	
	#inicia la conexion con la base
	include("../db/api_db.php");
	$db_manager = new DB();
	
	if(!empty($_POST['siguiente_dir'])){
		$_SESSION['direc'][0] = array("direccion", $_POST['direccion']);
		$_SESSION['direc'][1] = array("altura", $_POST['altura']);
		header('Location: addcliente.php'); 	
	}else{
		if(!empty($_POST['siguiente_apellido'])){
			$_SESSION['direc'][0] = array("direccion", 'NO');
			$_SESSION['direc'][1] = array("altura", $_POST['apellido']);
			header('Location: addcliente.php'); 	
		}else{
			if(!empty($_GET['add_prod'])){	
				if(!empty($_GET['det'])){	
					$db_manager->add_info_pedido('producto_pedido',base64_decode($_SESSION['id_ped']),$db_manager->clear_string($_GET['cant_producto']),$_GET['codprod']);	
					header('Location: detalle.php?v='.$_SESSION['id_ped']); 
				}else{
					$_SESSION['prod'][]=array( "producto",$db_manager->clear_string($_GET['cant_producto']), $_GET['detalle'], $_GET['codprod']);	
				
					header('Location: addcompleto.php?v='.base64_encode($_GET['v'])); 
				}	
			}else{
				if(!empty($_GET['Guardar'])){
					if((is_numeric($_GET['valor'])) and ($_GET['valor'] >= 0))
					{
						$repartidor=$_SESSION["id_user"];
	
						$db_manager->add_pedido('pedido_p',$repartidor,$_GET['nro_cliente'],$_GET['direccion'].' - ',$_GET['valor'],$_GET['fec_ori'],0,$db_manager->clear_string($_GET['obser']));
						$where='id_persona='.$repartidor.' AND nrocliente='.$_GET['nro_cliente'];
						$id_pedido = $db_manager->get_max_pedido('pedido_p',$where);
						if($_GET['estado'] == "entregado"){
							$db_manager->add_pedido_entregado($id_pedido['id_pedido']);
							$where = 'id_pedido='.$id_pedido['id_pedido'];
							$db_manager->remove_pedido('pedido_p',$where);
							$fec_entrega = getdate();
							$element="FechaEntregado='".$_GET['fec_ori']."'";
							$db_manager->update_element('pedido_e',$id_pedido['id_pedido'],$element);
						}
						$longitud = count($_SESSION['prod']);
						for($i=0; $i<=$longitud; $i++)
						{
							if($_SESSION['prod'][$i][0] == 'producto'){
								$db_manager->add_info_pedido('producto_pedido',$id_pedido['id_pedido'],$_SESSION['prod'][$i][1],$_SESSION['prod'][$i][3]);
							}	
						}
						$longitud = count($_SESSION['devo']);
						for($i=0; $i<=$longitud; $i++)
						{
							if($_SESSION['devo'][$i][0] == 'producto'){
								$db_manager->add_info_pedido('producto_devo',$id_pedido['id_pedido'],$_SESSION['devo'][$i][1],$_SESSION['devo'][$i][3]);
							}	
						}
						#Limpio los arreglos
						$longitud = count($_SESSION['prod']);
						for($i=0; $i<=$longitud; $i++)
						{
							unset($_SESSION['prod'][$i]);	
						}
						$longitud = count($_SESSION['devo']);
						for($i=0; $i<=$longitud; $i++)
						{
							unset($_SESSION['devo'][$i]);	
						}
						header('Location: index.php?m=true'); 	
					}else{
						header('Location: addcompleto.php?me=true&v='.base64_encode($_GET['nro_cliente'])); 
					}
			
				}else{
					if(!empty($_GET['prod_delete'])){	
						unset($_SESSION['prod'][$_GET['element']]);
						if(!empty($_GET['det'])){	
							header('Location: detalle.php?v='.$_SESSION['id_ped']); 
						}else{
							header('Location: addcompleto.php?v='.base64_encode($_GET['v'])); 
						}	
					}else{
						if(!empty($_GET['add_devo'])){	
							if(!empty($_GET['det'])){
								$db_manager->add_info_pedido('producto_devo',base64_decode($_SESSION['id_ped']),$db_manager->clear_string($_GET['cant_producto']),$_GET['codprod']);	
								header('Location: detalle.php?v='.$_SESSION['id_ped']); 
							}else{
								$_SESSION['devo'][]=array( "producto",$db_manager->clear_string($_GET['cant_producto']), $_GET['detalle'], $_GET['codprod']);
								header('Location: addcompleto.php?v='.base64_encode($_GET['v'])); 
							}		
						}else{
							if(!empty($_GET['devo_delete'])){	
								unset($_SESSION['devo'][$_GET['element']]);
								if(!empty($_GET['det'])){	
									header('Location: detalle.php?v='.$_SESSION['id_ped']); 
								}else{
									header('Location: addcompleto.php?v='.base64_encode($_GET['v'])); 
								}
							}else{
								if(!empty($_GET['prod_delete_tabla'])){	
									$where='id_prod='.base64_decode($_GET['element']);
									$db_manager->remove_pedido('producto_pedido',$where);
									header('Location: detalle.php?v='.$_SESSION['id_ped']); 	
								}else{	
									if(!empty($_GET['devo_delete_tabla'])){	
										$where='id_prod_devo='.base64_decode($_GET['element']);
										$db_manager->remove_pedido('producto_devo',$where);
										header('Location: detalle.php?v='.$_SESSION['id_ped']); 	
									}else{							
										print('Entro al else del GET');
									}
								}
							}
						}
					}
				}
			}
		}
	}

			
?> 

