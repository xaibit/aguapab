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

	 	$registros = 50000;

	  	$pagina = $_GET ['pagina'];
		$where="id_persona = ".$_SESSION["id_user"];
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
                $total = $db_manager->total('pedido_p',$where);
		$pedidos = $db_manager->get_element_order('pedido_p',$where,'FechaPedido,DirEntrega ASC',$inicio,$total);
		$total_paginas = ceil($total / $registros);
		#$where_ac="id_persona = ".$_SESSION["id_user"].' AND pago=0';
		#$total_ac = $db_manager->total('pedido_e',$where_ac); 
		$_SESSION['id_ped']="";
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
 		 		<li><a href="../repartidor/">Dashboard</a></li>
  				<li class="active"><a href="#">Pedidos</a></li>
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
			$mensaje = 'El pedido fue entregado correctamente.';
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
					<h3> Lista de pedidos</h3>
					<table class="table table-striped">
					      <thead>
						<tr>
						  <th>Dirección</th>
                                                  <th>Fecha</th>
                                                  <th>Observaciones</th>
						</tr>
					      </thead>
					      <tbody>
						<?php
							if($total > 0){
								while($row = $pedidos->fetch_assoc()) {
									$fecha_v = explode('-', $row['FechaPedido']);
									$f_venta = $fecha_v[2].'-'.$fecha_v[1].'-'.$fecha_v[0];
									echo '<tr>';
							  		echo '<td><a href="detalle.php?v='.base64_encode($row['id_pedido']).'">'.str_replace("-","",$row['DirEntrega']).'</a></td>';
							  		echo '<td>'.$f_venta.'</td>';
                                                                        echo '<td>'.$row['observ'].'</td>'; 
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
								echo'<td ><a href="?fil=true&calle='.$calle.'&pagina='.($pagina-1).'">&laquo; Anterior</a></td>';
							}
							if(($pagina + 1)<=$total_paginas) {
								echo'<td ><a href="?fil=true&calle='.$calle.'&pagina='.($pagina+1).'"> Siguiente &raquo;</a></td>';
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
		<p align="center">©2016 - Diseñado e implementado por <a href="http://degla.com.ar/" target="_blank">DEGLA</a>.</p>
		</div>
	</div>
</body>
	
