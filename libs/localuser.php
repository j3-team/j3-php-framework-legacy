<?php

class LocalUser {
	private $id;
	private $id_persona;
	private $alias;
	private $nivel;
	private $nombres;
	private $apellidos;
	private $numero;

	
	public function __construct($id = 0, $id_persona = 0, $alias = "", $nivel = -1, $nombres = "", $apellidos = "", $numero = 0) {
		$this->id = $id;
		$this->id_persona = $id_persona;
		$this->nombres = $nombres;
		$this->apellidos = $apellidos;
		$this->alias = $alias;
		$this->numero = $numero;
		$this->nivel = $nivel;
	}
	
	
	public function getId() {
		return $this->id;
	}

	
	public function getIdPersona() {
		return $this->id_persona;
	}
	
	
	public function getNombres() {
		return $this->nombres;
	} 
	
	
	public function getApellidos() {
		return $this->apellidos;
	} 
	
	
	public function getAlias() {
		return $this->alias;
	} 
	
	
	public function getNumero() {
		return $this->numero;
	} 
	
	
	public function getNivel() {
		return $this->nivel;
	} 
	
	public function getTime() {
		return $_SESSION["luser_time"];
	}
	
	
	public function setId($id) {
		$this->id = $id;
		$_SESSION["luser_id"] = $id;
	}

	
	public function setIdPersona($id_persona) {
		$this->id_persona = $id_persona;
		$_SESSION["luser_idPersona"] = $id_persona;
	}
	
	
	public function setNombres($nombres) {
		$this->nombres = $nombres;
		$_SESSION["luser_nombres"] = $nombres;
	} 
	
	
	public function setApellidos($apellidos) {
		$this->apellidos = $apellidos;
		$_SESSION["luser_apellidos"] = $apellidos;
	} 
	
	
	public function setAlias($alias) {
		$this->alias = $alias;
		$_SESSION["luser_alias"] = $alias;
	} 
	
	
	public function setNumero($numero) {
		$this->numero = $numero;
		$_SESSION["luser_numero"] = $numero;
	} 
	
	
	public function setNivel($nivel) {
		$this->nivel = $nivel;
		$_SESSION["luser_nivel"] = $nivel;
	} 
	
	public function setTime($time) {
		$_SESSION["luser_time"] = $time;
	}
	
	
	public static function setCurrentUser($id = 0, $id_persona = 0, $alias = "", $nivel = -1, $nombres = "", $apellidos = "", $numero = 0) {
		$_SESSION["luser_id"] = $id;
		$_SESSION["luser_idPersona"] = $id_persona;
		$_SESSION["luser_nombres"] = $nombres;
		$_SESSION["luser_apellidos"] = $apellidos;
		$_SESSION["luser_alias"] = $alias;
		$_SESSION["luser_numero"] = $numero;
		$_SESSION["luser_nivel"] = $nivel;
		
		$_SESSION["luser_time"] = time();
	}
	
	
	public static function getCurrentUser() {
		if (isset($_SESSION["luser_id"]))
			return new LocalUser(
				$_SESSION["luser_id"],
				$_SESSION["luser_idPersona"],
				$_SESSION["luser_alias"],
				$_SESSION["luser_nivel"],
				$_SESSION["luser_nombres"],
				$_SESSION["luser_apellidos"],				
				$_SESSION["luser_numero"]				
			);
		else
			return new LocalUser();
	}
	
	
	public static function clearCurrentUser() {
		if (isset($_SESSION["luser_id"])) {
			unset($_SESSION["luser_id"]);
			unset($_SESSION["luser_idPersona"]);
			unset($_SESSION["luser_nombres"]);
			unset($_SESSION["luser_apellidos"]);
			unset($_SESSION["luser_alias"]);
			unset($_SESSION["luser_numero"]);
			unset($_SESSION["luser_nivel"]);
		}
	}
	
	public function setAsCurrentUser() {
		$_SESSION["luser_id"] = $this->id;
		$_SESSION["luser_idPersona"] = $this->id_persona;
		$_SESSION["luser_nombres"] = $this->nombres;
		$_SESSION["luser_apellidos"] = $this->apellidos;
		$_SESSION["luser_alias"] = $this->alias;
		$_SESSION["luser_numero"] = $this->numero;
		$_SESSION["luser_nivel"] = $this->nivel;
	}
}

?>