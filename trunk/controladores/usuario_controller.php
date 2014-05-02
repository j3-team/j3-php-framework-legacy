<?php
require_once("libs/controller.php");
require_once("modelos/usuarios.php");
require_once("modelos/personas.php");
require_once("libs/funciones.php");

class UsuarioController extends Controller {
	
	public function __construct() {
		$this->layout = "principal";
		$this->pageTitle = APP_TITLE;	
	}
	
	public function index() 
	{
		$this->titleShowed =APP_TITLE.".- Login";
	}
	
	public function login() 
	{
		try {
			extract($_REQUEST);
			$usuario = new Usuarios();
			$usuario->addCondition("alias", $alias);
			$usuario->addCondition("clave", md5($clave));
			$usuario->doSelectAllWithForeign("personas","id_persona");
			if($usuario->next()){
				$id_usuario=$usuario->getValueByPos(0);
				$estatus=$usuario->getValue("estatus");
				$id_nivel_usuario=$usuario->getValue("id_nivel_usuario");
				$id_persona=$usuario->getValue("id");
				$nombres=$usuario->getValue("nombres");
				$apellidos=$usuario->getValue("apellidos");
				$cedula=$usuario->getValue("cedula");
				LocalUser::setCurrentUser($id_usuario, $id_persona, $alias, $id_nivel_usuario, $nombres, $apellidos, $cedula);
				$this->logger->info($alias." ".$id_persona." Inicio de Sesion");
				if(strcmp($id_nivel_usuario,"1")== 0)
				{
					$this->redirect("persona/index");
				}
				else if(strcmp($id_nivel_usuario,"2")== 0)
				{
					$this->redirect("secciones/index");
				}
			}
			else{
				$_SESSION["msjError"]="Usuario (alias) y/o Contrase&ntilde;a Inv&aacute;lido";
				$this->logger->warn($alias." "." Inicio de Sesion Fallido");
				$this->redirect("usuario/index");
			}
		} catch (Exception $e) {
			$this->logger->error($alias." "." Excepcion en Login: ".$e->getMessage());
			$this->redirect("usuario/index");
		}
	}
	
	public function registro()
	{
		$this->titleShowed =APP_TITLE.".- Registro";
		$cadena=randomText(6,0);
		$_SESSION["codCaptcha"]=$cadena;
	}

	public function olvidoclave()
	{
		$this->titleShowed =APP_TITLE.".- Olvido de Contrase&ntilde;a";
	}
	
	public function salir()
	{
		LocalUser::clearCurrentUser();
		$this->redirect("index");
	}
	
	public function mantenimiento()
	{
		$this->continuar=false;
		$this->showNotificacion("","<br/><br/><br/><br/><br/><br/>".APP_STATUS_MESSAGE,"","","principal",APP_TITLE.".- Login");
	}
	
	public function xDisponibilidad()
	{
		try {
			extract($_REQUEST);
			$usuario = new Usuarios();
			$usuario->addCondition("alias", $alias);
			$usuario->doSelectAll();
			if($usuario->next()){
				echo "0";
			}
			else{
				echo "1";
			}
		}
		catch (Exception $e) {
			$this->logger->error("Error al consultar disponibilidad de alias: ".$e->getMessage());
		}
	}
	
	public function xConsultarCedula()
	{
		try {
			extract($_REQUEST);
			$persona = new Personas();
			$persona->addCondition("cedula", $cedula);
			$persona->doSelectAll();
			if($persona->next()){
				echo "0";
			}
			else
			{
				echo "1";
			}
		}
		catch (Exception $e) {
			$this->logger->error("Error al consultar cedula: ".$e->getMessage());
		}
	}
	
	public function xGenerarCaptcha()
	{
		extract($_REQUEST);
		$captcha = imagecreatefromgif("recursos/imgs/captcha.gif");
		$colText = imagecolorallocate($captcha, 0, 0, 0);
		imagestring($captcha,5,16,7, $codigo, $colText);
		header("Content-type: image/gif");
		imagegif($captcha);
	}
}
?>