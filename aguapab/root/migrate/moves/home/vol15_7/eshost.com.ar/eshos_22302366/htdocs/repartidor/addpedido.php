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
		$where="id_persona = ".$_SESSION["id_user"];
		#$where_ac="id_persona = ".$_SESSION["id_user"].' AND pago=0';
		$total = $db_manager->total('pedido_p',$where);
		#$total_ac = $db_manager->total('pedido_e',$where_ac);
		$longitud = count($_SESSION['prod']);
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
  				<li><a href="pedidos.php">Pedidos <span> ( <?php echo $total;?> )</span></a></li>
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
					<h3>Dirección del cliente</h3>
					<form class="form-signin" action="check_addpedido.php" method="post">
        					<input type="text" id="direccion" name="direccion" class="form-control" placeholder="Dirección del pedido" value="" required>
						<input type="number" id="altura" name="altura" class="form-control" placeholder="Altura de la dirección" value="" required>						
						<br/>
						<input class="btn btn-lg btn-primary btn-block" id="siguiente_dir" name="siguiente_dir" value="Siguiente" type="submit" style="background: rgb(43, 166, 203) linear-gradient(to bottom, rgb(43, 166, 203) 5%, rgb(43, 166, 203) 100%) repeat scroll 0% 0%; border-radius: 28px; border: 1px solid rgb(43, 166, 203); display: inline-block; cursor: pointer; color: rgb(255, 255, 255); font-family: Arial; font-size: 13px; padding: 7px 20px; text-decoration: none; text-shadow: 0px 1px 0px rgb(43, 166, 203);">	

					</form>
					<h3>Nombre del cliente</h3>
					<form class="form-signin" action="check_addpedido.php" method="post">
        					<input type="text" id="apellido" name="apellido" class="form-control" placeholder="Nombre / Apellido" value="" required>				
						<br/>
						<input class="btn btn-lg btn-primary btn-block" id="siguiente_apellido" name="siguiente_apellido" value="Siguiente" type="submit" style="background: rgb(43, 166, 203) linear-gradient(to bottom, rgb(43, 166, 203) 5%, rgb(43, 166, 203) 100%) repeat scroll 0% 0%; border-radius: 28px; border: 1px solid rgb(43, 166, 203); display: inline-block; cursor: pointer; color: rgb(255, 255, 255); font-family: Arial; font-size: 13px; padding: 7px 20px; text-decoration: none; text-shadow: 0px 1px 0px rgb(43, 166, 203);">	

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
