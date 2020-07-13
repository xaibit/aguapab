<!DOCTYPE html>

<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>AguaPab</title>

  <link rel="stylesheet" href="../stylesheets/foundation.css">
  <link rel="stylesheet" href="../stylesheets/app.css">

  <script src="../javascripts/modernizr.foundation.js"></script>

</head>

<body onLoad="window.print()">
	<?php
	  	session_start();	
	  	if($_SESSION['admin'] != "LOGGED")
	  	{
			header('Location: index.html');	 
	  	}
	  	$now = time();
		if($now > $_SESSION['expire'])
		{
			session_unset();	
			header('Location: ../index.html');
		}
	  	include("../db/api_db.php");
	 	$db_manager = new DB();

	  	$pagina = $_GET ['pagina'];
		$filtro_act = $_GET ['fil'];
		$repa=0;
		$f_hoy = getdate();
		$fecha = $f_hoy['year'].'-'.$f_hoy['mon'].'-'.$f_hoy['mday'];
		$dia=$fecha;
		if (isset($filtro_act))
		{	
			$where="1=1";
			if(strcmp($_GET['repa'], '0') !== 0){
				$where.=" AND id_persona=".$_GET['repa'];
				$repa=$_GET['repa'];
			}
			if((strcmp($_GET['f'], "Todos") !== 0) and (strcmp($_GET['f'], "spasar") !== 0)){
				//Al filtrar por sólo los de hoy sigue trayendo los que tienen deuda
				$where.=" AND (FechaEntregado='".$dia."')";
				$dia="Hoy";
			}else{
				if(strcmp($_GET['f'], "spasar") !== 0){
					$dia="Todos";
				}else{
					$dia="spasar";
					$where.=" AND (pasado=0)";
				}
			}
		}
		$total = $db_manager->total('pedido_e',$where);
		$registros = $total;

		if (!isset($pagina))
		{
			$pagina = 1;
			$inicio = 0;
		}
		else
		{
			$inicio = ($pagina-1) * $registros;
		}
	
		//CONSULTAS
		$pedidos = $db_manager->get_element_order('pedido_e',$where,'FechaEntregado DESC',$inicio,$registros);
		$suma = $db_manager->get_suma('pedido_e',$where);
		$total_paginas = ceil($total / $registros); 
		$total_p = $db_manager->total('pedido_p','1=1');
		$repartidores = $db_manager->get_all_element_order('personal','vendedor > 0','name ASC');
		$longitud = count($_SESSION['prod']);
		for($i=0; $i<=$longitud; $i++)
		{
			//Limpio el array de productos
			unset($_SESSION['prod'][$i]);
		}
		$longitud = count($_SESSION['devo']);
		for($i=0; $i<=$longitud; $i++)
		{
			//Limpio el array de productos
			unset($_SESSION['prod'][$i]);
		}
		if(! isset($suma['total'])){
			$suma['total']=0;
		}	
	?>

	<div class="row">
		<div class="twelve columns">
                 <div style="width:100%; float:left;">
                     <div style="width:20%; float:left;">
		     	<img src="../images/aguapab.jpeg" align="left" width="70" height="70" alt=""/>
		     </div>
		     <div style="width:75%; float:left;">
		     	<img src="../images/logo-n.png" align="left" width="260" height="250" alt=""/>
		     </div>
                  </div>
		</div>
	</div>
	<div class="row">
		<div class="twelve columns">
			<div class="row">
				<div class="eight columns">
					<h3> Filtros</h3>
					<div style="width:40%; float:left;">
						<select class="form-control" id="repa" name="repa">
							<?php						
							echo'<option value="0" selected>-- Todos --</option>';
							while($row2 = $repartidores->fetch_assoc()) {
								if($repa == $row2['vendedor']){
									echo'<option value="'.$row2['vendedor'].'" selected>'.$row2['name'].'</option>';
								}else{
									echo'<option value="'.$row2['vendedor'].'">'.$row2['name'].'</option>';
								}
							}
							?>
						</select>
					</div>
					</br></br>
					<?php if ($dia == 'Todos'): ?>
						<input type="radio" id="fecha" name="fecha" value="Hoy">De hoy <input type="radio" id="fecha" name="fecha" value="Todos" checked="checked"> Todos 
					<?php else: ?>
						<input type="radio" id="fecha" name="fecha" value="Hoy" checked="checked">De hoy <input type="radio" id="fecha" name="fecha" value="Todos"> Todos 
					<?php endif ?>
					<?php if ($dia == 'spasar'): ?>
						<input type="radio" id="fecha" name="fecha" value="spasar" checked="checked"> Sin Pasar
					<?php else: ?>
						<input type="radio" id="fecha" name="fecha" value="spasar" > Sin Pasar
					<?php endif ?>
					<h3> Total de Caja: $ <?php echo $suma['total']; ?></h3>
					<h3> Lista de pedidos</h3>
					<table class="table table-striped">
					      <thead>
						<tr>
						  <th>Cliente</th>	 	
						  <th>Dirección</th>
						  <th>Entregado</th>
						  <th>Valor</th>
						  <th>Repartidor</th>	
						</tr>
					      </thead>
					      <tbody>
						<?php
							if($total > 0){
								while($row = $pedidos->fetch_assoc()) {
									$w_name = "vendedor = ".$row['id_persona'];
									$id_repar = $db_manager->get_element('name','personal',$w_name);
									$fecha_v = explode('-', $row['FechaEntregado']);
									$f_entrega = $fecha_v[2].'-'.$fecha_v[1].'-'.$fecha_v[0];
									$where_c = "Nro=".$row['nrocliente'];
									$cliente = $db_manager->get_element('Nombre','Clientes',$where_c);
									$where_id='id_pedido='.$row['id_pedido'];
									$producto_pedido=$db_manager->get_all_element_order('producto_pedido',$where_id,'detalle_ped ASC');
									echo '<tr>';
									    echo '<td><b>'.$cliente['Nombre'].'</b></a>
									    </td>';
									    echo '<td><b>'.str_replace("-","",$row['DirEntrega']).'</b></td>';
										echo '<td><b>'.$f_entrega.'</b></td>';
										echo '<td><b> $'.$row['valor'].'</b></td>';
										echo '<td><b>'.$id_repar['name'].'</b></td>';
										while($row2 = mysql_fetch_assoc($producto_pedido)) {	
											$where_pro = "CODPROD=".$row2['detalle_ped'];
											$detalle_pedido = $db_manager->get_element('Descripcion','productos',$where_pro);
											echo '<tr>
													<td colspan="1">'.$row2['cant_ped'].'</td>
													<td colspan="4"><i>'.$detalle_pedido['Descripcion'].'</i></td>
												</tr>';
                                                                                        echo '<tr>
													<td colspan="1">Observaciones:</td>
													<td colspan="4"><i>'.$row['observ'].'</i></td>
												</tr>';
										}	
									echo'</tr>';
									
								}
							} else {
								echo '<p><strong>No hay pedidos entregados de hoy ó con deuda.</br> Redefina la búsqueda y vuelva a intentarlo.</strong></p>';
							}
						?>		
						</tbody>	
					     </thead>
					</table>
					
				</div>
		
			</div>
		</div>
	</div>
	<div class="row">
		<div class="twelve columns">
		<hr />
		<p align="center">©2016 - Diseñado e implementado por DEGLA.</p>
		</div>
	</div>
</body>
	
