<?php

require_once("libs/controller.php");

class GenericController extends Controller {
	
	public function __construct() {
	}

	public function mantenimiento()
	{
		$this->continuar=false;
		$this->showNotificacion("",APP_STATUS_MESSAGE,"","","noMenu","En mantenimiento");
	}
}

?>
