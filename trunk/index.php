<?php
	require_once 'conf/app.php';
	
	function wellcome_page() {
		if (!defined('APP_DEFAULT') || strcmp(trim(APP_DEFAULT), "") == 0) {
			echo '<h1>..:: J3 PHP FRAMEWORK RULEZ ::... :D</h1>';
		} else {
			header("Location: " . APP_DEFAULT); 
		}
	}
	
	function continue_load($c) {
		$m = "";
		if (isset($_GET["metodo"])) {
			$m = $_GET["metodo"];
		}
		
		$c = strtolower($c);
		$c = strtoupper($c[0]) . substr($c,1);
		//$m = strtolower($m);

		if (file_exists("controladores/".strtolower($c)."_controller.php")) {
			require_once("controladores/".strtolower($c)."_controller.php");
			
			$cc = $c."Controller";
			$clase = new $cc;
			//$clase = $cc::getInstance($cc);
			
			if (APP_STATUS == 2 && strcmp($m, "notificacion") != 0) {
				require_once("controladores/usuario_controller.php");
				$clase = new UsuarioController();
				$clase->ejecutar(strtolower($c),"mantenimiento");
			}
			else
			{
				$clase->ejecutar(strtolower($c),$m);
			}
		} else {
			echo "ERROR: Archivo <strong>".strtolower($c)."_controller.php</strong> no existe.  :(";
		}

	}
	
	if (isset($_GET["tres"])) {
		$c = $_GET["controlador"];
		$m = $_GET["metodo"];
		$t = $_GET["tres"];
		
		if (strcmp($t, "data") == 0)
			continue_load($c);
		else
			header("location:".APP_URL."$m/$t");
		exit(0);
	}
	
	if (isset($_GET["controlador"])) {
		$c = $_GET["controlador"];
		if (strcmp($c, "") != 0) {
				continue_load($c);
		} else {
			wellcome_page();
		}
	} else {
		wellcome_page();
	}
	
?>
