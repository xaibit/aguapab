<!DOCTYPE html>
<html class="no-js" lang="es">
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
  </script>

</head>

<body>
	<?php
		# Session lifetime of 3 hours
		session_start();	
		$_SESSION['expire'] = time() + (30*60);
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

	 	$where="1=1";
		
		//CONSULTAS
		$total_p = $db_manager->total('pedido_p',$where);
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
			<h3> Bienvenido <?php echo $_SESSION['n_user'];?></h3>
			<ul class="nav-bar">
 		 		<li class="active"><a href="#">Dashboard</a></li>
  				<li><a href="pedidos_ent.php">Pedidos Entregados <span> ( <?php echo $total_e;?> )</span></a></li>
				<li><a href="pedidos_pen.php">Pedidos Pendientes <span> ( <?php echo $total_p;?> )</span></a></li>
  				<li><a href="addpedido.php">Agregar Pedido</a></li>
				<li><a href="../login.php?l=true">Salir</a></li>
			</ul>
			
		</div>
	</div>
	
	<div class="row" style="width:85%;">
	<div class="twelve columns" style="width:85%; float:right;">
        <?php		
		if(!empty($_GET['md'])){
			$mensaje = 'El pedido fue cancelado correctamente.';
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
		<p align="center">©2016 - Diseñado e implementado por <a href="http://degla.com.ar/" target="_blank">DEGLA</a></p>
		</div>
	</div>
</body>
