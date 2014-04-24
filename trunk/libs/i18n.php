<?php
 /*********************************************************
 * Author    : JJFP
 * Para i18n
  **********************************************************/

require_once("libs/log4php/Logger.php");
require_once("i18n/db/j3_i18n.php");
require_once("libs/FirePHPCore/FirePHP.class.php");

class i18n {
	private $myFirePhp;
	private $logger;
	
	
	public function __construct() {
		//Firebug
		ob_start();
		$this->myFirePhp = FirePHP::getInstance(true);
		
		//Logger
		Logger::configure('conf/log.xml');
		$this->logger = Logger::getRootLogger();
	}
	
	public function translate($text, $lang) {
		$this->logger->debug("traslating to '$lang': ".$text);
		$this->myFirePhp->log($text, "traslating to '$lang'");
		
		if (I18N_METHOD == "DB") {
		
			$i = new J3_i18n();
			$i->addCondition("text_code", $text);
			$i->addCondition("lang_code", $lang);
			if ($i->doSelectAll() && $i->next()) {
				return $i->getValue("text_trans");
			} else {
				$this->logger->debug("By DB: String not found!");
				$this->myFirePhp->log("String not found!", "By DB");
			}
		
		} else if (I18N_METHOD == "XML") {
		
			if (file_exists("i18n/xml/$lang.xml")) {
				$xmlContent = file_get_contents("i18n/xml/$lang.xml");
				if ($xmlContent != null) {
					$j3_i18n= new SimpleXMLElement($xmlContent);
					$namespaces = $j3_i18n->getNameSpaces(true);
					$j3 = $j3_i18n->children($namespaces['j3']);
					 
					/*Revisamos los tags*/
					foreach ($j3->phrases->phrase as $phrase) {
						if ((string)$phrase->text == $text) {
							return $phrase->trans;
						}
					}
				} else {
					$this->logger->debug("By XML: XML null.");
					$this->myFirePhp->log("XML null.", "By XML");
				}
			} else {
				$this->logger->debug("By XML: File not exists");
				$this->myFirePhp->log("String not found!", "By XML");
			}
		
		}
		
		return $text;
	}
	
	public function translate2($text) {
		if (isset($_SESSION["j3_jang"])) {
			return i18n($text, $_SESSION["j3_jang"]);
		}
		return $text;
	}
	
	public static function setJ3Lang($lang) {
		$_SESSION["j3_jang"] = $lang;
	}
	
	public static function getJ3Lang() {
		if (isset($_SESSION["j3_jang"])) {
			return $_SESSION["j3_jang"];
		} else {
			return DEFAULT_LANG; 
		}
	}
	
	public static function setDefaultJ3Lang() {
		$_SESSION["j3_lang"] = DEFAULT_LANG;
	}
	
	public static function getDefaultJ3Lang() {
		return DEFAULT_LANG;
	}
}

?>