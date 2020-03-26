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


	<script type="text/javascript">
	  function filtrar(){
		var resultado="ninguno";
		var posicion=document.getElementById('repa').options.selectedIndex; //posicion
		var repa=document.getElementById('repa').options[posicion].value;
		var fec=document.getElementsByName('fecha');
		for(var i=0;i<fec.length;i++)
		{
			if(fec[i].checked)
				resultado=fec[i].value;

        	}
		var sStr1 = "?fil=true&repa="+repa+"&f="+resultado;
		window.location.href = sStr1;
	   }	
	   function remove_filtro(){
		var sStr1 = "../admin/pedidos_ent.php";
		window.location.href = sStr1;
	   }	
	   function pasar_pedido(id){
		var resultado="ninguno";
		var posicion=document.getElementById('repa').options.selectedIndex; //posicion
		var repa=document.getElementById('repa').options[posicion].value;
		var fec=document.getElementsByName('fecha');
		for(var i=0;i<fec.length;i++)
		{
			if(fec[i].checked)
				resultado=fec[i].value;

        }  
		var sStr1 = "?fil=true&repa="+repa+"&f="+resultado+"&pasar=true&ip="+id;
		window.location.href = sStr1;
	   }
	   </script>

</head>

<body>
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
                $registros = 10;

	  	$pagina = $_GET ['pagina'];
		$filtro_act = $_GET ['fil'];
		$pasar_act = $_GET ['pasar'];
		$repa=0;
		$f_hoy = getdate();
		$fecha = $f_hoy['year'].'-'.$f_hoy['mon'].'-'.$f_hoy['mday'];
		$dia=$fecha;
		//Al entrar por primera vez trae los entregados en el día o los que tienen deudas
		$where="(FechaEntregado='".$dia."')";
		if (!isset($pagina))
		{
			$pagina = 1;
			$inicio = 0;
		}
		else
		{
			$inicio = ($pagina-1) * $registros;
		} 

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
		
		if (isset($pasar_act))
		{
			$element='pasado=true';
			$db_manager->update_element('pedido_e',$_GET['ip'],$element);
		}
	
		//CONSULTAS
		$pedidos = $db_manager->get_element_order('pedido_e',$where,'FechaEntregado,DirEntrega DESC',$inicio,$registros);
		$suma = $db_manager->get_suma('pedido_e',$where);
		$total = $db_manager->total('pedido_e',$where);
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
	
	<div class="row" id="menu">
		<div class="twelve columns">
			<ul class="nav-bar">
 		 		<li><a href="../admin/">Dashboard</a></li>
  				<li class="active"><a href="pedidos_ent.php">Pedidos Entregados</a></li>
				<li><a href="pedidos_pen.php">Pedidos Pendientes <span> ( <?php echo $total_p;?> )</span></span></a></li>
				<li><a href="addpedido.php">Agregar Pedido</a></li>
				<li><a href="../login.php?l=true">Salir</a></li>
			</ul>
			
		</div>
	</div>
	
	<div class="row">
		<div class="twelve columns">
			<div class="row">
				<div class="eight columns">
					<?php
					echo'<a href="imprimir_lista.php?fil=true&repa='.$repa.'&f='.$dia.'" class="btn btn-lg btn-primary btn-block" target="_blank" id="imprimir" name="imprimir" style="background: rgb(43, 166, 203) linear-gradient(to bottom, rgb(43, 166, 203) 5%, rgb(43, 166, 203) 100%) repeat scroll 0% 0%; border-radius: 28px; border: 1px solid rgb(43, 166, 203); display: inline-block; cursor: pointer; color: rgb(255, 255, 255); font-family: Arial; font-size: 13px; padding: 7px 20px; text-decoration: none; text-shadow: 0px 1px 0px rgb(43, 166, 203);">
						Imprimir </a>';
					?>	
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
					<input class="btn btn-lg btn-primary btn-block" id="Filtrar" name="Filtrar" value="Filtrar" type="submit" style="background: rgb(43, 166, 203) linear-gradient(to bottom, rgb(43, 166, 203) 5%, rgb(43, 166, 203) 100%) repeat scroll 0% 0%; border-radius: 28px; border: 1px solid rgb(43, 166, 203); display: inline-block; cursor: pointer; color: rgb(255, 255, 255); font-family: Arial; font-size: 12px; padding: 3px 15px; text-decoration: none; text-shadow: 0px 1px 0px rgb(43, 166, 203);" onclick='filtrar();'>						
					<input class="btn btn-lg btn-primary btn-block" id="Limpiar" name="Limpiar" value="Limpiar" type="submit" style="background: rgb(228, 104, 93) linear-gradient(to bottom, rgb(228, 104, 93) 5%, rgb(237, 34, 23) 100%) repeat scroll 0% 0%; border-radius: 28px; border: 1px solid rgb(255, 255, 255); display: inline-block; cursor: pointer; color: rgb(255, 255, 255); font-family: Arial; font-size: 13px; padding: 3px 15px; text-decoration: none; text-shadow: 0px 1px 0px rgb(178, 62, 53);" onclick='remove_filtro()'>
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
						  <th>Acción</th>	
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
									$pasado = $row['pasado'];
									$producto_pedido=$db_manager->get_all_element_order('producto_pedido',$where_id,'detalle_ped ASC');
									echo '<tr>';
										if($pasado == 1){
											echo '<td bgcolor="#58FA58"><a href="detalle.php?e=true&v='.base64_encode($row['id_pedido']).'"><b>'.$cliente['Nombre'].'</b></a></td>';
									    }else{
											echo '<td><a href="detalle.php?e=true&v='.base64_encode($row['id_pedido']).'"><b>'.$cliente['Nombre'].'</b></a></td>';
									    }
									    if($pasado == 1){
											echo '<td bgcolor="#58FA58"><b>'.str_replace("-","",$row['DirEntrega']).'</b></td>';
										}else{
											echo '<td><b>'.str_replace("-","",$row['DirEntrega']).'</b></td>';
										}
										if($pasado == 1){
											echo '<td bgcolor="#58FA58"><b>'.$f_entrega.'</b></td>';
										}else{
											echo '<td><b>'.$f_entrega.'</b></td>';
										}	
										if($pasado == 1){
											echo '<td bgcolor="#58FA58"><b> $'.$row['valor'].'</b></td>';
										}else{
											echo '<td><b> $'.$row['valor'].'</b></td>';
										}
										if($pasado == 1){
											echo '<td bgcolor="#58FA58"><b>'.$id_repar['name'].'</b></td>';
										}else{
											echo '<td><b>'.$id_repar['name'].'</b></td>';
										}	
										if($row['pasado'] == 0){
											echo '<td><input class="btn btn-lg btn-primary btn-block" id="'.base64_encode($row['id_pedido']).'_pasar" name="'.base64_encode($row['id_pedido']).'_pasar" value="Pasar" type="submit" style="background: rgb(43, 166, 203) linear-gradient(to bottom, rgb(43, 166, 203) 5%, rgb(43, 166, 203) 100%) repeat scroll 0% 0%; border-radius: 28px; border: 1px solid rgb(43, 166, 203); display: inline-block; cursor: pointer; color: rgb(255, 255, 255); font-family: Arial; font-size: 12px; padding: 3px 15px; text-decoration: none; text-shadow: 0px 1px 0px rgb(43, 166, 203);" onclick="pasar_pedido('.$row['id_pedido'].');"></td>';
										}else{
											echo '<td bgcolor="#58FA58">PASADO</td>';
										}
										while($row2 = $producto_pedido->fetch_assoc()) {	
											$where_pro = "CODPROD=".$row2['detalle_ped'];
											$detalle_pedido = $db_manager->get_element('Descripcion','productos',$where_pro);
											echo '<tr>
													<td colspan="1">'.$row2['cant_ped'].'</td>
													<td colspan="5"><i>'.$detalle_pedido['Descripcion'].'</i></td>
												</tr>';
										}
											echo '<tr>
													<td colspan="1">Observaciones:</td>
													<td colspan="5"><i>'.$row['observ'].'</i></td>
												</tr>';
									echo'</tr>';
										
									
								}
							} else {
								echo '<p><strong>No hay pedidos entregados de hoy ó con deuda.</br> Redefina la búsqueda y vuelva a intentarlo.</strong></p>';
							}
						?>		
						</tbody>	
					     </thead>
					</table>
					<?php
					if($total>$registros){	
						echo '<table class="table table-striped">
						<tr>';
							if(($pagina - 1) > 0) {
								echo'<td ><a href="?fil=true&repa='.$repa.'&f='.$dia.'&pagina='.($pagina-1).'">&laquo; Anterior</a></td>';
							}
							if(($pagina + 1)<=$total_paginas) {
								echo'<td ><a href="?fil=true&repa='.$repa.'&f='.$dia.'&pagina='.($pagina+1).'"> Siguiente &raquo;</a></td>';
							}
						echo '</tr>
						</table>';
					}
					?>
				</div>
		
			</div>
		</div>
	</div>
	<div class="row">
		<div class="twelve columns">
		<hr />
		<p align="center">©2016 - Diseñado e implementado por <a href="http://degla.com.ar/" target="_blank">DEGLA</a></p>
		</div>
	</div>
</body>
