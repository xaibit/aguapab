<!DOCTYPE html>

<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <meta name="viewport" content="width=device-width" />

  <title>AguaPab</title>

  <link rel="stylesheet" href="../stylesheets/foundation.css">
  <link rel="stylesheet" href="../stylesheets/app.css">


  <script src="../javascripts/modernizr.foundation.js"></script>


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
		$dir = $db_manager->clear_string($_SESSION['direc'][0][1]);
		$alt = $db_manager->clear_string($_SESSION['direc'][1][1]);	

		if($dir != "NO")
		{
			$where = "Direccion LIKE '%".$dir.' '.$alt."%' AND (BORRADO='FALSO' OR BORRADO='0')";
		}else{
			$where = "Nombre LIKE '%".$alt."%' AND (BORRADO='FALSO' OR BORRADO='0')";
		}
		$clientes = $db_manager->get_all_element_order('Clientes',$where,'Nombre ASC');
		$total = $db_manager->total('Clientes',$where); 

		$where_p="id_persona = ".$_SESSION["id_user"];
		$total_p = $db_manager->total('pedido_p',$where_p);
		#$where_ac="id_persona = ".$_SESSION["id_user"].' AND pago=0';
		#$total_ac = $db_manager->total('pedido_e',$where_ac); 
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
	
	<div class="row">
		<div class="twelve columns">
			<div class="row">
				<div class="eight columns">
					<h3>Seleccionar cliente</h3>
					<form class="form-signin" action="addcompleto.php" method="post">
        					 <table class="table table-striped">
					      <thead>
						<tr>
						  <th></th>
						  <th>Nombre</th>
						  <th>Dirección</th>	
						</tr>
					      </thead>
					      <tbody>
						<?php
							if($total > 0){
								$index=0;
								while($row = $clientes->fetch_assoc()) {
									echo '<tr>';
									if($index==0){
										echo '<td><input type="radio" name="nro" value="'.$row['Nro'].'" checked="checked"></td>';		
									}else{
										echo '<td><input type="radio" name="nro" value="'.$row['Nro'].'"></td>';		
									}		
							  		echo '<td>'.$row['Nombre'].'</a></td>';
							  		echo '<td>'.$row['Direccion'].'</a></td>';
							  		echo'</tr>';
									$index+=1;
								}
							} else {
								echo '<p><strong>No hay clientes con esa dirección.</strong></p>';
							}
						?>		
						</tbody>	
					     </thead>
					</table>		
						<br/>
						<a class="btn btn-lg btn-primary btn-block" id="Atras" name="Atras" value="Atras" href="addpedido.php" style="background: rgb(228, 104, 93) linear-gradient(to bottom, rgb(228, 104, 93) 5%, rgb(237, 34, 23) 100%) repeat scroll 0% 0%; border-radius: 28px; border: 1px solid rgb(255, 255, 255); display: inline-block; cursor: pointer; color: rgb(255, 255, 255); font-family: Arial; font-size: 13px; padding: 9px 20px; text-decoration: none; text-shadow: 0px 1px 0px rgb(178, 62, 53);">Atras</a>
						<input class="btn btn-lg btn-primary btn-block" id="siguiente_dir" name="siguiente_dir" value="Siguiente" type="submit" style="background: rgb(43, 166, 203) linear-gradient(to bottom, rgb(43, 166, 203) 5%, rgb(43, 166, 203) 100%) repeat scroll 0% 0%; border-radius: 28px; border: 1px solid rgb(43, 166, 203); display: inline-block; cursor: pointer; color: rgb(255, 255, 255); font-family: Arial; font-size: 13px; padding: 7px 20px; text-decoration: none; text-shadow: 0px 1px 0px rgb(43, 166, 203);">	
					</form>
					
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
