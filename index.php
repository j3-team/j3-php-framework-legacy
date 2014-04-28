<?php

###################################
# Archivo principal. Se ejecuta en todas las peticiones y dependiendo de los parametros, 
# redirije al controlador necesario.  
###################################

require_once 'conf/app.php';


/** Funcion que muestra el mensaje de bienvenida.
 */
function wellcome_page() {
	if (!defined('APP_DEFAULT') || strcmp(trim(APP_DEFAULT), "") == 0) {
		include 'libs/wellcome.php';
	} else {
		header("Location: " . APP_DEFAULT); 
	}
}


/** Continua la carga del controlador especificado.
  * @param c. Nombre del Controlador.
 */
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
			require_once("controladores/generic_controller.php");
			$clase = new GenericController();
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


/** Verifica si la redireccion agrego un parametro mas al final de la URL.
 */
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


/** Verifica si se especifico un controlador.
 */
if (isset($_GET["controlador"])) {
	$c = $_GET["controlador"];
	if (strcmp($c, "phpinfo") == 0) {
		echo phpinfo();
		//print_r( get_loaded_extensions() );
	} elseif (strcmp($c, "") != 0) {
			continue_load($c);
	} else {
		wellcome_page();
	}
} else {
	wellcome_page();
}

?>
