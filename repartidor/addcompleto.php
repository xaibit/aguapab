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
		var sStr1 = "check_addpedido.php?add_prod=true&v="+num+"&detalle="+detalle+"&codprod="+codprod+"&cant_producto="+cant;
		window.location.href = sStr1;
	  }
	  function add_devo(num) {
		var posicion=document.getElementById('detalle_devo').options.selectedIndex;
		if(posicion>0){
			var detalle=document.getElementById('detalle_devo').options[posicion].text
			var codprod=document.getElementById('detalle_devo').options[posicion].value
		}
		var cant=document.getElementById("cant_devo").value;
		var sStr1 = "check_addpedido.php?add_devo=true&v="+num+"&detalle="+detalle+"&codprod="+codprod+"&cant_producto="+cant;
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
		if(!empty($_GET['v'])){
			$nro=base64_decode($_GET['v']);
		}else{
			$nro=$_POST['nro'];
		}
		$where="Nro=".$nro;	
		$cliente = $db_manager->get_element('Nombre, calleZ, Altura','Clientes',$where);
		$where_calle = "Id=".$cliente['calleZ'];
		$calle = $db_manager->get_element('Calle','Calles',$where_calle);

		$fec_entrega = getdate();

		$total_p = $db_manager->total('pedido_p',"1=1");
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
		
		$productos = $db_manager->get_all_element_order('productos','borrado=0','descripcion ASC');
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
 		 		<li><a href="index.php">Dashboard</a></li>
  				<li><a href="pedidos.php">Pedidos <span> ( <?php echo $total_p;?> )</span></a></li>
  				<!--<li><a href="pedidosac.php">Pedidos a cuenta <span> ( <?php echo $total_ac;?> )</span></a></li>-->
				<li class="active"><a href="#">Agregar Pedido</a></li>
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
			$mensaje = 'El costo del pedido debe ser numérico mayor o igual a cero. La cantidad de productos del pedido y devueltos debe ser mayor o igual a cero.';
			echo"<a id='close' onclick='delete_message();' href='#'><img SRC='../images/close.png' WIDTH=25 HEIGHT=25 ALIGN=right ALT=''></a>";			
			echo '<div id="message" class="message" style="background-color: red;">
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
					<h3>Nuevo pedido</h3>
					<form class="form-signin" action="check_addpedido.php" method="get">
        					<input type="text" id="nro_cliente" name="nro_cliente" class="form-control" value="<?php echo $nro; ?>" style="display:none;">
        					<input type="text" id="direccion" name="direccion" class="form-control" placeholder="" value="<?php echo $calle['Calle'].' Nro '.$cliente['Altura']; ?>" readonly>
						<input type="text" id="fec_ori" name="fec_ori" class="form-control" value="<?php echo $fec_entrega['year'].'-'.$fec_entrega['mon'].'-'.$fec_entrega['mday']; ?>" style="display:none;">
						<input type="text" id="fecha" name="fecha" class="form-control" placeholder="" value="<?php echo $fec_entrega['mday'].'-'.$fec_entrega['mon'].'-'.$fec_entrega['year']; ?>" readonly>
						<input type="text" id="cliente" name="cliente" class="form-control" placeholder="" value="<?php echo $cliente['Nombre']; ?>" readonly>
						<div style="width:85%; float:left;">
							<label><font size=2>Productos</font></label>
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
								echo '<a href="javascript:;" onclick="add_prod('.$nro.');" title="Agregar producto"> Agregar Producto</a>'
								 ?>
							</div>
						</div>
						<!-- Tabla de productos del pedido -->
						<div style="width:85%; float:left;">
						<table class="table table-striped" style="width:100%; float:left;">
						      <thead>
							<tr>
							  <th>Producto</th>
							  <th><center>Cantidad</center></th>
							  <th>Acción</th>
							</tr>
						      </thead>
						      <tbody>
							<?php
								$vacio=true;
								$num=0;
								$longitud = count($_SESSION['prod']);
								for($i=0; $i<=$longitud; $i++)
								{
								   if($_SESSION['prod'][$i][0] == 'producto'){
								   	   $vacio=false;	
									   echo'<tr>';
										echo'<td>'.$_SESSION['prod'][$i][2].'</td>';
										echo'<td><center>'.$_SESSION['prod'][$i][1].'</center></td>'; 
										echo'<td>
											<div class="col-xs-6 col-sm-3 placeholder" style="width:3%; float:left;">
											<a id="'.$_SESSION['prod'][$i][2].'_delete" href="check_addpedido.php?element='.$i.'&prod_delete=true&v='.$nro.'" title="Quitar producto">Eliminar</a>
											</div></td>'; 		
									   echo'</tr>';	
									   $num+=1;
								    }	
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
						<div style="width:40%; float:left;">
						<br/>
						<div style="width:100%; float:left;display:none">
							<select class="form-control" id="pago" name="estadopago">
								<option value="pago" selected>Pago</option>
								<option value="nopago">A cuenta</option>';
							</select>
						</div>
						<!--<br/><br/><br/>-->
						</div>
						<div style="width:85%; float:left;">
							<label><font size=2>Devoluciones</font></label>
							<div style="width:60%; float:left;">
								<select class="form-control" id="detalle_devo" name="detalle_devo">
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
								echo '<a href="javascript:;" onclick="add_devo('.$nro.');" title="Agregar devolución"> Agregar Devolución</a>'
								 ?>
							</div>
						</div>
						<!-- Tabla de productos de devolución -->
						<div style="width:85%; float:left;">
						<table class="table table-striped" style="width:100%; float:left;">
						      <thead>
							<tr>
							  <th>Producto</th>
							  <th><center>Cantidad</center></th>
							  <th>Acción</th>
							</tr>
						      </thead>
						      <tbody>
							<?php
								$vacio=true;
								$num=0;
								$longitud = count($_SESSION['devo']);
								for($i=0; $i<=$longitud; $i++)
								{
								   if($_SESSION['devo'][$i][0] == 'producto'){
								   	   $vacio=false;	
									   echo'<tr>';
										echo'<td>'.$_SESSION['devo'][$i][2].'</td>';
										echo'<td><center>'.$_SESSION['devo'][$i][1].'</center></td>'; 
										echo'<td>
											<div class="col-xs-6 col-sm-3 placeholder" style="width:3%; float:left;">
											<a id="'.$_SESSION['devo'][$i][2].'_delete" href="check_addpedido.php?element='.$i.'&devo_delete=true&v='.$nro.'" title="Quitar producto">Eliminar</a>
											</div></td>'; 		
									   echo'</tr>';	
									   $num+=1;
								    }	
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
							<select class="form-control" id="estado" name="estado">
								<option value="entregado" selected>Entregado</option>
								<option value="pendiente">Pendiente</option>';
							</select>
						<br/><br/>
						</div>
						<div style="width:40%; float:left;">
							<label><font size=2>Entrega $</font></label>
							<input type="text" id="valor" name="valor" class="form-control" placeholder="Entrega $" value="0.0">
						</div>
						</div>
						<div style="width:100%; float:left;">
						<textarea class="form-control" rows="3" id="obser" name="obser" value="" style="width:100%; float:left;">Sin Observ.</textarea>							
						<br/><br/><br/><br/>
						<a class="btn btn-lg btn-primary btn-block" id="Atras" name="Atras" value="Atras" href="addcliente.php" style="background: rgb(228, 104, 93) linear-gradient(to bottom, rgb(228, 104, 93) 5%, rgb(237, 34, 23) 100%) repeat scroll 0% 0%; border-radius: 28px; border: 1px solid rgb(255, 255, 255); display: inline-block; cursor: pointer; color: rgb(255, 255, 255); font-family: Arial; font-size: 13px; padding: 9px 20px; text-decoration: none; text-shadow: 0px 1px 0px rgb(178, 62, 53);">Atras</a>

						<input class="btn btn-lg btn-primary btn-block" id="Guardar" name="Guardar" value="Guardar" type="submit" style="background: rgb(119, 181, 90) linear-gradient(to bottom, rgb(119, 181, 90) 5%, rgb(114, 179, 82) 100%) repeat scroll 0% 0%; border-radius: 28px; border: 1px solid rgb(75, 143, 41); display: inline-block; cursor: pointer; color: rgb(255, 255, 255); font-family: Arial; font-size: 13px; padding: 7px 20px; text-decoration: none; text-shadow: 0px 1px 0px rgb(91, 138, 60);">	
						</div>
					</form>
				</div>
		
			</div>
		</div>
	</div>
	<div class="row">
		<div class="twelve columns">
		<hr />
		<p align="center">©2016 - Diseñado e implementado por <a href="http://degla.com.ar/" target="_blank">DEGLA</a>.</p>
		</div>
	</div>
</body>
