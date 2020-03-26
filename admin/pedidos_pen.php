<!DOCTYPE html>

<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>AguaPab</title>

  <!-- Included CSS Files -->
  <link rel="stylesheet" href="../stylesheets/foundation.css">
  <link rel="stylesheet" href="../stylesheets/app.css">

  <script src="../javascripts/modernizr.foundation.js"></script>

	<script type="text/javascript">
	  function filtrar(){
		var resultado="ninguno";
		var posicion=document.getElementById('repa').options.selectedIndex; //posicion
		var repa=document.getElementById('repa').options[posicion].value;
		var sStr1 = "?fil=true&repa="+repa;
		window.location.href = sStr1;
	   }	
	   function remove_filtro(){
		var sStr1 = "../admin/pedidos_pen.php";
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

	 	$registros = 500000;

	  	$pagina = $_GET ['pagina'];
		$filtro_act = $_GET ['fil'];
		$repa=0;
		$where="1=1";
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
		}


		//CONSULTAS
                $total = $db_manager->total('pedido_p',$where);
		$pedidos = $db_manager->get_element_order('pedido_p',$where,'FechaPedido,DirEntrega ASC',$inicio,$total);
		$total_paginas = ceil($total / $registros); 

		$fec_entrega = getdate();
		if($fec_entrega['mday'] < 10){
			$dia='0'.$fec_entrega['mday'];
		}else{
			$dia=$fec_entrega['mday'];
		}
		if($fec_entrega['mon'] < 10){
			$mes='0'.$fec_entrega['mon'];
		}else{
			$mes=$fec_entrega['mon'];
		}

		$f_entrega = $fec_entrega['year'].'-'.$mes.'-'.$dia;
		$where_hoy = "FechaEntregado LIKE '%".$f_entrega."%'";
		$total_e = $db_manager->total('pedido_e',$where_hoy);	
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
			<ul class="nav-bar">
 		 		<li><a href="../admin/">Dashboard</a></li>
  				<li><a href="pedidos_ent.php">Pedidos Entregados <span> ( <?php echo $total_e;?> )</span></a></li>
				<li class="active"><a href="#">Pedidos Pendientes</span></a></li>
				<li><a href="addpedido.php">Agregar Pedido</a></li>
				<li><a href="../login.php?l=true">Salir</a></li>
			</ul>
			
		</div>
	</div>
	
	<div class="row">
		<div class="twelve columns">
			<div class="row">
				<div class="eight columns">
					<h3> Filtro</h3>
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
					<h3> Lista de pedidos</h3>
					<table class="table table-striped">
					      <thead>
						<tr>
						  <th>Dirección</th>
						  <th>Fecha</th>
                                                  <th>Repartidor</th>
						 </tr>
					      </thead>
					      <tbody>
						<?php
							if($total > 0){
								while($row = $pedidos->fetch_assoc()) {
									$w_name = "vendedor = ".$row['id_persona'];
									$id_repar = $db_manager->get_element('name','personal',$w_name);
									$fecha_v = explode('-', $row['FechaPedido']);
									$f_venta = $fecha_v[2].'-'.$fecha_v[1].'-'.$fecha_v[0];
									echo '<tr>';
							  		echo '<td><a href="detalle.php?v='.base64_encode($row['id_pedido']).'">'.str_replace("-","",$row['DirEntrega']).'</a></td>';
							  		echo '<td>'.$f_venta.'</td>';
                                                                       echo '<td>'.$id_repar['name'].'</td>';
                                                                        echo '<tr>
											<td colspan="1">Observaciones:</td>
											<td colspan="2"><i>'.$row['observ'].'</i></td>
											</tr>';		
							  		echo'</tr>';
                                                                        
								}
							} else {
								echo '<p><strong>No hay pedidos pendientes.</strong></p>';
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
								echo'<td ><a href="?fil=true&repa='.$repa.'&pagina='.($pagina-1).'">&laquo; Anterior</a></td>';
							}
							if(($pagina + 1)<=$total_paginas) {
								echo'<td ><a href="?fil=true&repa='.$repa.'&pagina='.($pagina+1).'"> Siguiente &raquo;</a></td>';
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
	
