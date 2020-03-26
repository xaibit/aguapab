<?php
  session_start();	
if(!empty($_POST['login'])){
	include("db/api_db.php");
  	#$_SESSION["db"][0] = "localhost"; //host
	#$_SESSION["db"][1] = "aguapabr_aguapab"; //user db
	#$_SESSION["db"][2] = "aguapab"; //pass db
	#$_SESSION["db"][3] = "aguapabr_aguapab"; //base name
	
	$_SESSION["db"][0] = "localhost"; //host
	$_SESSION["db"][1] = "aguapab1_aguapab"; //user db
	$_SESSION["db"][2] = "fran2009"; //pass db
	$_SESSION["db"][3] = "aguapab1_aguapab"; //base name
	
    	$db_manager = new DB();
	$user = $_POST['user']; #mysql_real_escape_string($_POST['user']);
	$pass = $_POST['pass']; #mysql_real_escape_string($_POST['pass']);
	$where='name="'.$user.'" AND pass="'.$pass.'"';	
 	$total_login = $db_manager->check_login($where);
        if($total_login == 1){
		$_SESSION["admin"] = "LOGGED";
		$user = $db_manager->get_element('vendedor','personal',$where);
		$_SESSION["n_user"] = $_POST['user'];
		$_SESSION["id_user"] = $user['vendedor'];
		if($_SESSION["id_user"] == 0)
		{
			header('Location: admin/');
		}else{	
			header('Location: repartidor/');
		}
	  }else{
		session_unset();
		header('Location: index.html');
	  }
  }

  if(isset($_GET['l'])){
	session_unset();
        header('Location: index.html');
  }	

$host= $_SERVER["HTTP_HOST"];
	$url= $_SERVER["REQUEST_URI"];
	$url_error = "http://" . $host . $url;
	if(($url_error == "http://aguapabrepartos.eshost.com.ar/login.php?i=1")){
   		header('Location: index.html');
	}		
?>
