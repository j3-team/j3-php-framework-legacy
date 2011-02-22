<?php

require_once("libs/controller.php");

class ProbandoController extends Controller {
	
	public function __construct() {
		$this->layout = "principal";		
	}
	
	public function index() {
		$this->variable = "El gato";
	}
	
	public function perro() {
		$this->variable = "<br/>MI MAMA ME MIMA";
	}
	
}

?>
