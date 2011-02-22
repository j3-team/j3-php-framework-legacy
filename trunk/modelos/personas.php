<?php
require_once("libs/dbmodel.php");

class Personas extends DbModel {
	
	/**
	 * Guarda el archivo dado en el campo id_foto. Si el campo ya existe, lo elimina primero antes de agregar le nuevo.
	 * @param path Ruta del archivo
	 */
	public function guardarFoto($path) {
		$id = $this->getId();
		/*if ($id != null) {
			$foto = $this->getValue("id_foto");
			if ($foto != 0) {
				try {
					$this->execQuery("SELECT lo_unlink($foto)");
				} catch(Exception $e) {
				}
			}
			$this->execQuery("UPDATE personas SET id_foto=lo_import('$path') WHERE id=$id");
		}*/
		
		$data = $this->escapeBlob($path);
		//$this->setValue("foto", "{$data}");
		//$this->doUpdate();
		$this->doUpdateBlob("foto", $data);
		//$this->execQuery("UPDATE personas SET foto='{$data}' WHERE id=$id");
	}
	
	/**
	 * Consula y guarda en la ruta especificada, la foto de la persona.
	 * @param path Ruta del archivo
	 * @return String
	 */
	public function cargarFoto($path) {
		$id = $this->getId();
		if ($id != null) {
			//$foto = $this->getValue("id_foto");
			$foto = $this->getValue("foto");
			//if ($foto != 0) {
			if ($foto != null) {
				/*$this->execQuery("SELECT lo_export( $foto ,'$path".$id."_foto.jpg')");
				if ($this->next())
					return "recursos/temp/".$id."_foto.jpg";
				else
					return "recursos/imgs/no-foto.jpg";*/
				if ($this->unescapeBlob("recursos/temp/".$id."_foto.jpg", $foto)) {
					return "recursos/temp/".$id."_foto.jpg";
				} else {
					return "recursos/imgs/no-foto.jpg";
				}
			} else
				return "recursos/imgs/no-foto.jpg";
		}
		return "recursos/imgs/no-foto.jpg";
	}
}

?>
