<?php
include("libs/log4php/Logger.php");
require_once("libs/FirePHPCore/FirePHP.class.php");
require_once("libs/localuser.php");
require_once("libs/DomPDF/dompdf_config.inc.php");
require_once("libs/j3_utils.php");

/** Clase principal de los controladores.
	Incluye metodos utilizados frecuentemente.
*/
class Controller {
	protected $layout;
	protected $contentPage;
	protected $pageTitle;
	protected $titleShowed;
	static public $instancia = null;
	protected $myFirePhp;
	protected $logger;
	protected $continuar;
	protected $usuariosPermitidos;
	protected $nivelesPermitidos = null;
	
	/** Constructor
	*/
	public function __construct() {
		$this->layout = "";
		$this->contentPage = "";
		$this->pageTitle = "";
		$this->titleShowed = "";
		$this->usuariosPermitidos = "*";
	}
	
	
	/** Toma la instancia actual para no tener que reinstanciar la clase.
		param clase Nombre de la clase.
	*/
	public static function getInstance($clase){ 
          if (self::$instancia == null) { 
                self::$instancia = new $clase; 
          } else {
                echo "Existente";
          }
          return self::$instancia;  
	} 

	
	/** Metodo que redirecciona a la pagina dada.
        @param page Pagina a la que se desea redireccionar
        @param permanent hace la redireccion permanente
     */
     public function redirect($page, $permanent = false) {
          if($permanent) {
                header( "HTTP/1.1 301 Moved Permanently" ); 
          }
          header("location:".$page);
          exit(0);
     }    
	
     public function put_response($text) {
     	header("HTTP/1.1 200 OK");
     	header('Content-Type: text/html; charset=utf-8');
     	echo $text;
     }
	
	/** Metodo que ejecuta una vista especifica
		@param name Nombre del controlador
		@param vista Nombre de la vista
	*/
	public function ejecutar($name,$vista) {
		require_once 'conf/app.php';
		//Firebug
		ob_start();
		$this->myFirePhp = FirePHP::getInstance(true);
		//Logger
		Logger::configure('conf/log.xml');
		$this->logger = Logger::getRootLogger();
		$whatsB = $vista;
		$vista = strtolower($vista);
		$this->continuar = true;
		
		session_start();
		setcookie(session_name(),session_id(),time()+SESSION_TIME);
		
		if ($vista) {
			if (method_exists($this, $vista) == true) {
				call_user_func(array($this, $vista));
				
				if ($this->usuarioPermitido() == false && strcmp($vista,"notificacion") != 0) {
					$this->continuar = false;
					$this->userNotAllowed();
				} else {
					if ($this->sesionExpirada() == true) {
						$this->continuar = false;
						$this->sessionExpired();
					}
				}
				
				if (strcmp($vista[0], "x") != 0) {
					if ($this->continuar == true) {
						if (strcmp($vista,"notificacion") == 0) {
							$this->showView("vistas/defaults/notificacion.php");
						} else
							if (file_exists("vistas/$name/$vista.php")) {
								$this->showView("vistas/$name/$vista.php");
							} else {
								echo "ERROR: Vista <strong>$vista.php</strong> no definida.  :(";
							}
					}
				}
			} else {			
				$cc = get_class($this);
				echo "ERROR: Metodo <strong>$cc/$whatsB</strong> no definido.  :(";
			} 
			
		} else if ($this->continuar == true){
			$this->index();
			
			if ($this->usuarioPermitido() == false) {			
				$this->continuar = false;
				$this->userNotAllowed();
			} else {
				if ($this->sesionExpirada() == true) {
					$this->continuar = false;
					$this->sessionExpired();
				}
			}
			
			if ($this->continuar == true) {
				if (file_exists("vistas/$name/index.php")) {
					$this->showView("vistas/$name/index.php");
				} else {
					echo "ERROR: Vista <strong>index.php</strong> no definida.  :(";
				}				
			}

		}		
	}
	
	
	/** Metodo que muestra el contenido de la pagina dada en "contentPage".
		Usado principalmente en layouts.
	*/
	public function showContent() {
		require($this->contentPage);		
	}
	
	
	/** Metodo que muestra el contenido de una pagina dada
		@param page Pagina a mostrar
	*/
	public function showView($page) {
		if (strcmp($this->layout, "") == 0 ) {
			require($page);
		} else {
			if (file_exists("vistas/layouts/".$this->layout.".php")) {
				$this->contentPage = $page;
				require("vistas/layouts/".$this->layout.".php");		
			} else {
				echo "ERROR: Layout <strong>".$this->layout.".php</strong> no definido.  :(";
			}	
		}
	}
	
	
	/** Metodo con la vista por default (index).
	*/
	public function index() {
	}
	
	
	/** Vista por defecto de notificaciones
	*/
	public function notificacion() {
		$this->titulo = $_SESSION["notifTitle"];
		$this->mensaje = $_SESSION["notifMessage"];
		$this->boton = $_SESSION["notifButton"];
		$this->enlace = $_SESSION["notifLink"];
		$this->titleShowed = $_SESSION["notifBarra"];
		$this->layout = $_SESSION["notifLayout"];
		
		unset($_SESSION["notifTitle"]);
		unset($_SESSION["notifMessage"]);
		unset($_SESSION["notifButton"]);
		unset($_SESSION["notifLink"]);
		unset($_SESSION["notifBarra"]);
		unset($_SESSION["notifLayout"]);
	}
	
	
	/** Funcion que muestra la vista de notificaciones
	*/
	public function showNotificacion($titulo, $mensaje, $boton, $enlace, $layout = "default", $barra = "") {
		$_SESSION["notifTitle"] = $titulo;
		$_SESSION["notifMessage"] = $mensaje;
		$_SESSION["notifButton"] = $boton;
		$_SESSION["notifLink"] = $enlace;
		$_SESSION["notifBarra"] = $barra;
		$_SESSION["notifLayout"] = $layout;
		
		$clase = strtolower(get_class($this));
		$this->redirect(substr($clase, 0, strlen($clase)-10)."/notificacion");
	}
	
	
	/** Funcion que verifica si el usuario actualestá permitido para acceder a la funcionalidad
		@return bool
	*/
	public function usuarioPermitido() {
		$nivel = LocalUser::getcurrentUser()->getNivel();
		/*if ($nivel == -1) {
			return true;
		}*/
		
		if (is_array($this->nivelesPermitidos)) {
			foreach ($this->nivelesPermitidos as $niv) {
				if ($nivel == $niv)
					return true;
			}
		} else if ($this->nivelesPermitidos == $nivel) {
			return true;
		} else if ($this->nivelesPermitidos == null) {
			return true;
		}
		
		return false;
	}
	

	/** Funcion que se ejecuta cuando un usuario no está permitido en la funcionalidad
	*/
	public function userNotAllowed() {
		if (DENIED_ACTION == 1) {
			echo DENIED_MSG;
		} else {
			$this->redirect(DENIED_PAGE);
		}
	}
	

	/** Funcion que verifica si la sesión ya ha expirado
		@return bool
	*/
	public function sesionExpirada() {
		$nivel = LocalUser::getcurrentUser()->getNivel();
		if ($nivel == -1) {
			return false;
		}
		
		if ($this->nivelesPermitidos == null) {
			return false;
		}
		
		$tiempo = LocalUser::getcurrentUser()->getTime();
		$actual = time();
		
		//echo "<script type='text/javascript'>alert('$tiempo : $actual');</script>";
		
		if ($actual > $tiempo + SESSION_TIME) {
			$this->logger->debug("SESSION: $actual > $tiempo + ".SESSION_TIME." = TRUE");
			return true;
		} else {
			$this->logger->debug("SESSION: $actual > $tiempo + ".SESSION_TIME." = FALSE");
			LocalUser::getcurrentUser()->setTime( time() );
			return false;
		}
	}
	
	
	/** Funcion que se ejecuta cuando la sesión ha expirado (En caso de MANUAL)
	*/
	public function sessionExpired() {
		if (EXPIRED_ACTION == 1) {
			echo EXPIRED_MSG;
		} else {
			$this->redirect(EXPIRED_PAGE);
		}
	}
	

	/** Funcion que crea un PDF a partir del código HTML que se le indique
		@param filename Nombre del archivo PDF
		@param html Código HTML a escribir en el PDF
	*/
	public function createPDF($filename, $html) {
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render();
		$dompdf->stream($filename . ".pdf");
	}
	
	
	/** Funcion que crea un PDF a partir de la URL indicada (no funciona completamente)
		@param filename Nombre del archivo PDF
		@param url URL de la página a ser convertida en PDF
	*/
	public function createPDFfromURL($filename, $url) {
		$_GET["input_file"] = $url;
		$_GET["output_file"] = $filename;
		//$_GET["base_path"] = APP_URL;

		require_once("libs/DomPDF/dompdf.php");
	}

	
	public function mantenimiento()
	{
		$this->continuar=false;
		$this->showNotificacion("","<br/><br/><br/><br/><br/><br/>".APP_STATUS_MESSAGE,"","",APP_TITLE,"En mantenimiento");
	}
	
	/**
	 * Agrega un nuevo valor al mensaje (recibido por parametro)
	 * Esta función es útil para almacenar en una misma variable varios valores
	 * que dados como respuesta (Al ciente JavaScript) luego son extraídos en JavaScript
	 * a traves de la función getValMsgByPos.
	 */
	public function addValMsg(&$m, $v){
		$m = $m."/,/".$v;
	}
}

?>
