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
	  function delete_message() {
		document.getElementById('message').style.display='none';
		document.getElementById('close').style.display='none';
	  }
	  function add_prod(num) {
		var posicion=document.getElementById('detalle').options.selectedIndex;
		if(posicion>0){
			var detalle=document.getElementById('detalle').options[posicion].text
			var codprod=document.getElementById('detalle').options[posicion].value
		}
		var cant=document.getElementById("cant_producto").value;
		var sStr1 = "check_addpedido.php?add_prod=true&v="+num+"&detalle="+detalle+"&codprod="+codprod+"&cant_producto="+cant+"&det=true=";
		window.location.href = sStr1;
	  }
	  function add_devo(num) {
		var posicion=document.getElementById('detalle_devo').options.selectedIndex;
		if(posicion>0){
			var detalle=document.getElementById('detalle_devo').options[posicion].text
			var codprod=document.getElementById('detalle_devo').options[posicion].value
		}
		var cant=document.getElementById("cant_devo").value;
		var sStr1 = "check_addpedido.php?add_devo=true&v="+num+"&detalle="+detalle+"&codprod="+codprod+"&cant_producto="+cant+"&det=true=";
		window.location.href = sStr1;
	  }	
  </script>	

</head>

<body>
	<?php
	  session_start();	
	  if($_SESSION['admin'] != "LOGGED")
	  {
		header('Location: ../index.html');	 
	  }
	  $now = time();
	  if($now > $_SESSION['expire'])
	  {
		session_unset();	
		header('Location: ../index.html');
	  }
	  include("../db/api_db.php");
	  $db_manager = new DB();
	  $nro=$_GET['v'];
	  $_SESSION['id_ped']=$_GET['v'];
	  $where = "id_pedido = ".base64_decode($_GET['v']);		
	  if (empty($_GET['e'])){ 
		$pedido = $db_manager->get_element('*','pedido_p',$where);
	  }else{	
		$pedido = $db_manager->get_element('*','pedido_e',$where); 
	  }
	  $where_c = "Nro=".$pedido['nrocliente']; 	
	  $cliente = $db_manager->get_element('Nombre','Clientes',$where_c);	

	  $total_p = $db_manager->total('pedido_p','1=1');
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
	  $productos = $db_manager->get_all_element_order('productos','borrado=0','descripcion ASC');
	
	  $where_id='id_pedido='.base64_decode($_GET['v']);
	  $producto_pedido=$db_manager->get_all_element_order('producto_pedido',$where_id,'detalle_ped ASC');
	  $producto_devo=$db_manager->get_all_element_order('producto_devo',$where_id,'detalle_devuelto ASC');
	  $repartidores = $db_manager->get_all_element_order('personal','vendedor > 0','name ASC');
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
				<li><a href="pedidos_pen.php">Pedidos Pendientes <span> ( <?php echo $total_p;?> )</span></a></li>
				<li><a href="addpedido.php">Agregar Pedido</a></li>
				<li><a href="../login.php?l=true">Salir</a></li>
			</ul>
			
		</div>
	</div>
	
	<!-- don't mind this script, I just added them for fun! -->
	<script charset="ISO-8859-1" src="http://fast.wistia.com/static/popover-v1.js"></script>
	<div class="row" style="width:85%;">
	<div class="twelve columns" style="width:85%; float:right;">
        <?php		
		if(!empty($_GET['me'])){
			$mensaje = 'El costo del pedido debe ser numérico mayor o igual a cero.';
			echo"<a id='close' onclick='delete_message();' href='#'><img SRC='../images/close.png' WIDTH=25 HEIGHT=25 ALIGN=right ALT=''></a>";			
			echo '<div id="message" class="message" style="background-color: red;">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  			<p><font color=white><strong>'.$mensaje.'</strong></font></p>
			</div>';	
			}	

		if(!empty($_GET['m'])){
			$mensaje = 'El pedido fue actualizado correctamente.';
			echo"<a id='close' onclick='delete_message();' href='#'><img SRC='../images/close.png' WIDTH=25 HEIGHT=25 ALIGN=right ALT=''></a>";			
			echo '<div id="message" class="message" style="background-color: green;">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  			<p><font color=white><strong>'.$mensaje.'</strong></font></p>
			</div>';	
			}	
		  ?>
	</div>
	</div>
	<div class="row">
		<div class="twelve columns">
			<div class="row">
				<div class="eight columns">
					<h3> Detalles del pedido</h3>
					<form class="form-signin" action="check_entrega.php" method="get">
						<input type="text" id="id_pedido" name="id_pedido" class="form-control" value="<?php echo base64_decode($_GET['v']); ?>" style="display:none;">
						<input type="text" id="repar" name="repar" class="form-control" value="<?php echo $pedido['id_persona']; ?>" style="display:none;">
						<input type="text" id="nro_cliente" name="nro_cliente" class="form-control" value="<?php echo $pedido['nrocliente']; ?>" style="display:none;">
        					<input type="text" id="direccion" name="direccion" class="form-control" placeholder="" value="<?php echo str_replace('-','',$pedido['DirEntrega']); ?>" readonly>
						<input type="text" id="fec_ori" name="fec_ori" class="form-control" value="<?php echo$pedido['FechaPedido']; ?>" style="display:none;">
						<input type="text" id="fecha" name="fecha" class="form-control" placeholder="" value="<?php $fecha_v = explode('-', $pedido['FechaPedido']); $f_venta = $fecha_v[2].'-'.$fecha_v[1].'-'.$fecha_v[0]; echo $f_venta; ?>" readonly>
						<input type="text" id="cliente" name="cliente" class="form-control" placeholder="" value="<?php echo $cliente['Nombre']; ?>" readonly>
						
						<div style="width:85%; float:left;">
							<label><font size=2>Productos</font></label>
							<?php if((empty($_GET['e']))): ?>
								<div style="width:60%; float:left;">
									<select class="form-control" id="detalle" name="detalle">
										<?php							
										while($row2 = $productos->fetch_assoc()) {
											if('2003' == $row2['CODPROD']){
												echo'<option value="'.$row2['CODPROD'].'" selected>'.$row2['Descripcion'].'</option>';
											}else{
												echo'<option value="'.$row2['CODPROD'].'">'.$row2['Descripcion'].'</option>';
											}
										}
										?>
									</select>
								</div>
								<div style="width:20%; float:left;">
									<input type="number" id="cant_producto" name="cant_producto" class="form-control" value="1" requeried>	
								</div>
								<div style="width:5%; float:left;">
									<?php
									echo '<a href="javascript:;" onclick="add_prod();" title="Agregar producto"> Agregar Producto</a>'
									 ?>
								</div>
							<?php endif ?>	
						</div>
						<!-- Tabla de productos del pedido -->
						<div style="width:85%; float:left;">
						<table class="table table-striped" style="width:100%; float:left;">
						      <thead>
							<tr>
							  <th>Producto</th>
							  <th><center>Cantidad</center></th>
							  <?php if((empty($_GET['e']))): ?>
							  <th>Acción</th>
							  <?php endif ?>	
							</tr>
						      </thead>
						      <tbody>
							<?php
								$vacio=true;
								$num=0;
								while($row2 = $producto_pedido->fetch_assoc()) {
									$vacio=false;
									$productos->data_seek(0);
									echo'<tr>';
										while($row_prod = $productos->fetch_assoc()) {
											if($row2['detalle_ped'] == $row_prod['CODPROD']){
												echo'<td>'.$row_prod['Descripcion'].'</td>';
											}
										}
										echo'<td><center>'.$row2['cant_ped'].'</center></td>'; 
										if((empty($_GET['e'])))
										{
											echo'<td>
												<div class="col-xs-6 col-sm-3 placeholder" style="width:3%; float:left;">
													<a id="'.$row2['id_prod'].'_delete" href="check_addpedido.php?element='.base64_encode($row2['id_prod']).'&prod_delete_tabla=true&" title="Quitar producto">Eliminar</a>
												</div>
												</td>'; 
										}		
									   echo'</tr>';	

								}
								if($vacio){
									echo '<div class="message error-message">
										<p><strong>No hay productos seleccionados.</strong></p>
									</div>';
								}
							?>		
							</tbody>	
						     </thead>
						</table>
						</div>
						
						<div style="width:40%; float:left;display:none;">
							<select class="form-control" id="pago" name="pago">
								<?php if ($pedido['pago'] == 1): ?>
								<option value="pago" selected>Pago</option>
								<option value="nopago">A cuenta</option>';
								<?php else: ?>
								<option value="nopago" selected>A cuenta</option>';
								<option value="pago">Pago</option>
								<?php endif ?>
							</select>
						</div>
						<br/><br/><br/>
						
						<div style="width:85%; float:left;">
							<label><font size=2>Devoluciones</font></label>
							<?php if((empty($_GET['e']))): ?>
								<div style="width:60%; float:left;">
									<select class="form-control" id="detalle_devo" name="detalle_devo">
										<option value="0" selected>-- Sin especificar --</option>
										<?php	
									        $productos->data_seek(0);	#Para reutilizar la consulta					
										while($row2 = $productos->fetch_assoc()) {
											if('1004' == $row2['CODPROD']){
												echo'<option value="'.$row2['CODPROD'].'" selected>'.$row2['Descripcion'].'</option>';
											}else{
												echo'<option value="'.$row2['CODPROD'].'">'.$row2['Descripcion'].'</option>';
											}
										}
										?>
									</select>
								</div>
								<div style="width:20%; float:left;">
									<input type="number" id="cant_devo" name="cant_devo" class="form-control" value="1" requeried>	
								</div>
								<div style="width:5%; float:left;">
									<?php
									echo '<a href="javascript:;" onclick="add_devo();" title="Agregar devolución"> Agregar Devolución</a>'
									 ?>
								</div>
								<?php endif ?>	
						</div>
						<!-- Tabla de productos de devolución -->
						<div style="width:85%; float:left;">
						<table class="table table-striped" style="width:100%; float:left;">
						      <thead>
							<tr>
							  <th>Producto</th>
							  <th><center>Cantidad</center></th>
							  <?php if((empty($_GET['e']))): ?>
							  <th>Acción</th>
							  <?php endif ?>
							</tr>
						      </thead>
						      <tbody>
							<?php
								$vacio=true;
								$num=0;
								while($row2 = $producto_devo->fetch_assoc()) {
									$vacio=false;
									$productos->data_seek(0);
									echo'<tr>';
										while($row_prod = $productos->fetch_assoc()) {
											if($row2['detalle_devuelto'] == $row_prod['CODPROD']){
												echo'<td>'.$row_prod['Descripcion'].'</td>';
											}
										}
										echo'<td><center>'.$row2['cant_devuelto'].'</center></td>'; 
										if((empty($_GET['e'])))
										{
											echo'<td>
												<div class="col-xs-6 col-sm-3 placeholder" style="width:3%; float:left;">
													<a id="'.$row2['id_prod'].'_delete" href="check_addpedido.php?element='.base64_encode($row2['id_prod_devo']).'&devo_delete_tabla=true" title="Quitar producto">Eliminar</a>
												</div>
												</td>'; 
										}		
									   echo'</tr>';	
								}
								if($vacio){
									echo '<div class="message error-message">
										<p><strong>No hay devoluciones en el pedido.</strong></p>
									</div>';
								}
							?>		
							</tbody>	
						     </thead>
						</table>
						</div>
						<div style="width:80%; float:left;">	
						<br/>	
						<div style="width:40%; float:left;">
							<label><font size=2>Estado</font></label>
							<select class="form-control" id="estado" name="estado" disabled>
								<?php 
								if(empty($_GET['e']))
								{
									echo'<option value="entregado">Entregado</option>
									<option value="pendiente" selected>Pendiente</option>';
								}else{
									echo'<option value="entregado" selected>Entregado</option>
									<option value="pendiente">Pendiente</option>';
								}?>
							</select>
						<br/><br/>
						</div>
						<div style="width:40%; float:left;">
							<label><font size=2>Entrega $</font></label>
							<?php if (!empty($_GET['e'])): ?>
								<input type="text" id="valor" name="valor" class="form-control" placeholder="Entrega $" value="<?php echo $pedido['valor']; ?>" readonly>
							<?php else: ?>
								<input type="text" id="valor" name="valor" class="form-control" placeholder="Entrega $" value="<?php echo $pedido['valor']; ?>">
							<?php endif ?>	
						</div>
						</div>
						<div style="width:40%; float:left;">
							<?php if (!empty($_GET['e'])): ?>
								<input type="text" id="id_repa" name="id_repa" class="form-control" value="<?php echo $pedido['id_persona']; ?>" style="display:none;">
								<select class="form-control" id="repartidor" name="repartidor" disabled>
							<?php else: ?>
								<select class="form-control" id="repartidor" name="repartidor">
							<?php endif ?>	
							<?php
								while($row2 = $repartidores->fetch_assoc()) {
								if($pedido['id_persona'] == $row2['vendedor']){
									echo'<option value="'.$row2['vendedor'].'" selected>'.$row2['name'].'</option>';
								}else{
									echo'<option value="'.$row2['vendedor'].'">'.$row2['name'].'</option>';
								}
							}
							?>	
							</select>
						<br/><br/>
						</div>
						<div style="width:100%; float:left;">
						<?php if (!empty($_GET['e'])): ?>
							<textarea class="form-control" rows="3" id="obser" name="obser" value="" style="width:100%; float:left;" readonly><?php echo $pedido['observ']; ?></textarea>	
						<?php else: ?>
							<textarea class="form-control" rows="3" id="obser" name="obser" value="" style="width:100%; float:left;"><?php echo $pedido['observ']; ?></textarea>
						<?php endif ?>							
						<br/>
						<?php if (empty($_GET['e'])): ?>
						<input class="btn btn-lg btn-primary btn-block" id="Cancelar" name="Cancelar" value="Cancelar" type="submit" style="background: rgb(228, 104, 93) linear-gradient(to bottom, rgb(228, 104, 93) 5%, rgb(237, 34, 23) 100%) repeat scroll 0% 0%; border-radius: 28px; border: 1px solid rgb(255, 255, 255); display: inline-block; cursor: pointer; color: rgb(255, 255, 255); font-family: Arial; font-size: 13px; padding: 7px 20px; text-decoration: none; text-shadow: 0px 1px 0px rgb(178, 62, 53);">
						<input class="btn btn-lg btn-primary btn-block" id="Actualizar" name="Actualizar" value="Actualizar" type="submit" style="background: rgb(43, 166, 203) linear-gradient(to bottom, rgb(43, 166, 203) 5%, rgb(43, 166, 203) 100%) repeat scroll 0% 0%; border-radius: 28px; border: 1px solid rgb(43, 166, 203); display: inline-block; cursor: pointer; color: rgb(255, 255, 255); font-family: Arial; font-size: 13px; padding: 7px 20px; text-decoration: none; text-shadow: 0px 1px 0px rgb(43, 166, 203);">						
						<?php endif ?>
					</form>
					</div>
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
