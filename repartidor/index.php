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

  <script src="../javascripts/modernizr.foundation.js"></script>
  <script type="text/javascript">
	  function delete_message() {
		document.getElementById('message').style.display='none';
		document.getElementById('close').style.display='none';
	  }	
  </script> 	

</head>

<body>
	<?php
	  	session_start();
	  	$_SESSION['expire'] = time() + (30*60);
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

	 	$where="id_persona = ".$_SESSION["id_user"];
		#$where_ac="id_persona = ".$_SESSION["id_user"].' AND pago=0';
		//CONSULTAS
		$total = $db_manager->total('pedido_p',$where);
		#$total_ac = $db_manager->total('pedido_e',$where_ac);
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
		$_SESSION['id_ped']="";
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
			<h3> Bienvenido <?php echo $_SESSION['n_user'];?></h3>
			<ul class="nav-bar">
 		 		<li class="active"><a href="#">Dashboard</a></li>
  				<li><a href="pedidos.php">Pedidos <span> ( <?php echo $total;?> )</span></a></li>
  				<!--<li><a href="pedidosac.php">Pedidos a cuenta <span> ( <?php echo $total_ac;?> )</span></a></li>-->
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
		if(!empty($_GET['m'])){
			$mensaje = 'El pedido fue agregado correctamente.';
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
					<img src="../images/camiones.jpeg" align="center" width="300" height="350" alt=""/>
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
