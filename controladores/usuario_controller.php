<?php
require_once("libs/controller.php");
require_once("modelos/usuarios.php");
require_once("modelos/personas.php");
require_once("modelos/excluidos.php");
require_once("libs/funciones.php");

class UsuarioController extends Controller {
	
	public function __construct() {
		$this->layout = "principal";
		$this->pageTitle = "Escuela Nacional de Fiscales";	
	}
	
	public function index() 
	{
		$this->titleShowed ="Escuela Nacional de Fiscales.- Login";
	}
	
	public function requisitos() 
	{
		$this->titleShowed ="Escuela Nacional de Fiscales.- Requisitos";
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
		$this->titleShowed ="Escuela Nacional de Fiscales.- Registro";
		$cadena=randomText(6,0);
		$_SESSION["codCaptcha"]=$cadena;
	}
	
	public function registroadd()
	{
		try
		{
			extract($_REQUEST);
			if(strcmp(trim(md5($codSeguridad)),trim($codigo))!= 0)
			{
				$_SESSION["alias"]=$alias;
				$_SESSION["cedula"]=$cedula;
				$_SESSION["nombres"]=$nombres;
				$_SESSION["apellidos"]=$apellidos;
				$_SESSION["email"]=$email;
				$_SESSION["telefonoHab"]=$telefonoHab;
				$_SESSION["telefonoCel"]=$telefonoCel;	
				$_SESSION["numImpreAbogado"]=$numImpreAbogado;
				$this->continuar=false;
				$this->showNotificacion("","C&oacute;digo de Seguridad Incorrecto","Regresar","usuario/registro","principal","Escuela Nacional de Fiscales.- Registro");
			}
			else
			{
				$excluidos = new Excluidos();
				$excluidos->doSelectAllWithCondition("cedula", $cedula);
				if($excluidos->next()){
					$this->logger->info($cedula." Usuario en lista de Excluidos.");
					$mensaje="Estimado Usuario, por favor comuniquese con nosotros a trav&eacute;s de los siguientes tel&eacute;fonos: ";
					$mensaje.=" 0212-7316513, &oacute; a trav&eacute;s del correo escuela.fiscales@mp.gob.ve ";
					$this->continuar=false;
					$this->showNotificacion("",$mensaje,"Regresar","usuario/registro","principal","Escuela Nacional de Fiscales.- Registro");
				}
				$clave=randomText(8,0);
				$persona=new Personas();
				$persona->getConnection()->iniciarTransaccion();
				$persona->setValue("cedula",$cedula);
				$persona->setValue("nombres",$nombres);
				$persona->setValue("apellidos",$apellidos);
				$persona->setValue("email",$email);
				$persona->setValue("telefono_hab",$telefonoHab);
				$persona->setValue("telefono_cel",$telefonoCel);
				$persona->setValue("num_imp_abogado",$numImpreAbogado);
				$persona->doSave();
				$id_persona=$persona->getLastId();
				$this->logger->info($alias." ".$id_persona." Persona Insertada");
				$usuario = new Usuarios();
				$usuario->setValue("alias",$alias);
				$usuario->setValue("clave",md5($clave));
				$usuario->setValue("estatus","0");
				$usuario->setValue("id_nivel_usuario","1");
				$usuario->setValue("id_persona",$id_persona);
				$usuario->doSave();
				$this->logger->info($alias." ".$id_persona." Usuario Insertado");
				$nombreUsuario=$nombres." ".$apellidos;
				$mensajeMail="Usted se ha registrado exitosamente en nuestro sistema. Sus datos de acceso son los siguientes:";
				$html=htmlDatosDeAcceso($nombreUsuario,$mensajeMail,$alias,$clave,1);
				enviarCorreo($email,$nombreUsuario,"Escuela Nacional de Fiscales.- Datos de Acceso",$html);
				$persona->getConnection()->confirmarTransaccion();
				$this->logger->info($alias." ".$id_persona." Correo con Datos de acceso enviado a: ".$email);
				$mensaje="Se ha registrado exitosamente. Sus datos de ingreso al sistema han sidos enviados a su Correo Electr&oacute;nico.";
				$this->continuar=false;
				$this->showNotificacion("",$mensaje,"Regresar","usuario/","principal","Escuela Nacional de Fiscales.- Registro");
			}
		}
		catch(Exception $e)
		{
			$this->logger->error("Error en Registro de Usuarios: ".$e->getMessage());
			$persona->getConnection()->revertirTransaccion();
			$mensaje="Ha ocurrido un error al procesar su registro. Por favor intente m&aacute;s tarde.";
			$_SESSION["alias"]=$alias;
			$_SESSION["cedula"]=$cedula;
			$_SESSION["nombres"]=$nombres;
			$_SESSION["apellidos"]=$apellidos;
			$_SESSION["email"]=$email;
			$_SESSION["telefonoHab"]=$telefonoHab;
			$_SESSION["telefonoCel"]=$telefonoCel;
			$_SESSION["numImpreAbogado"]=$numImpreAbogado;
			$this->continuar=false;
			$this->showNotificacion("",$mensaje,"Regresar","usuario/registro","principal","Escuela Nacional de Fiscales.- Registro");
		}
	}
	
	public function olvidoclave()
	{
		$this->titleShowed ="Escuela Nacional de Fiscales.- Olvido de Contrase&ntilde;a";
	}
	
	public function recuperar()
	{
		try {
			extract($_REQUEST);
			$persona = new Personas();
			$persona->getConnection()->iniciarTransaccion();
			$persona->addCondition("cedula", $cedula);
			$persona->addCondition("email", $email);
			$persona->doSelectAll();
			if($persona->next()){
				$clave=randomText(8,0);
				$usuario=new Usuarios();
				$usuario->addCondition("id_persona",$persona->getValue("id"));
				$usuario->addCondition("id_nivel_usuario","1");
				$usuario->doSelectAll();
				if($usuario->next()){
					$alias=$usuario->getValue("alias");
					$usuario->setValue("clave",md5($clave));
					$usuario->doUpdate();
				}
				$nombreUsuario=$persona->getValue("nombres")." ".$persona->getValue("apellidos");
				$mensajeMail="Sus datos de acceso a nuestro sistema son los siguientes:";
				$html=htmlDatosDeAcceso($nombreUsuario,$mensajeMail,$alias,$clave,0);
				enviarCorreo($email,$nombreUsuario,"Escuela Nacional de Fiscales.- Datos de Acceso",$html);
				$persona->getConnection()->confirmarTransaccion();
				$this->logger->info($cedula." Correo con Datos de acceso enviado a: ".$email);	
				$mensaje="Sus datos de ingreso al sistema han sidos enviados a su Correo Electr&oacute;nico.";
				$this->continuar=false;
				$this->showNotificacion("",$mensaje,"Regresar","usuario/","principal","Escuela Nacional de Fiscales.- Olvido de Contrase&ntilde;a");
			}
			else{
				$this->logger->warn($cedula." "." Datos para recuperacion de usuario incorrectos");
				$mensaje="C&eacute;dula y/o Correo Electr&oacute;nico incorrectos.";
				$this->continuar=false;
				$this->showNotificacion("",$mensaje,"Regresar","usuario/olvidoclave","principal","Escuela Nacional de Fiscales.- Olvido de Contrase&ntilde;a");
			}
		}
		catch (Exception $e) {
			$persona->getConnection()->revertirTransaccion();
			$this->logger->error($cedula." Error al procesar su peticion. Error: ".$e->getMessage());	
			$mensaje="Ha ocurrido un error al procesar su petici&oacute;n. Por favor, intente m&aacute;s tarde.";
			$this->continuar=false;
			$this->showNotificacion("",$mensaje,"Regresar","usuario/olvidoclave","principal","Escuela Nacional de Fiscales.- Olvido de Contrase&ntilde;a");
		}
	}
	
	public function salir()
	{
		LocalUser::clearCurrentUser();
		$this->redirect("index");
	}
	
	public function mantenimiento()
	{
		$this->continuar=false;
		$this->showNotificacion("","<br/><br/><br/><br/><br/><br/>".APP_STATUS_MESSAGE,"","","principal","Escuela Nacional de Fiscales.- Login");
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
			else{
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