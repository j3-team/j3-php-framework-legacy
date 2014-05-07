<?php
#include("libs/phpmailer/class.phpmailer.php");
#include("libs/phpmailer/class.smtp.php");
require_once 'conf/mail.php';

 /*********************************************************
 * Author    : Jean Nizama
 * Function  : randomText
 * Parameters: $length longitud de la cadena, $type tipo de dato 0=alfanumerico 1=albabetico 2=numerico
 * Return    : Cadena de caracteres aleatorios
 * Example   : cadena=randomText(8,0)  cadena => "m475y1q6"
 **********************************************************/
function randomText($length,$type) 
{
	switch ($type)
	{
		case 0: 
				$pattern = "1234567890abcdefghijklmnopqrstuvwxyz"; 
				break;
		case 1: 
				$pattern = "abcdefghijklmnopqrstuvwxyz"; 
				break;
		case 2: 
				$pattern = "1234567890";
				break;
	}
	$max = strlen($pattern)-1;
	for($i=0;$i<$length;$i++)
	{ 
		$key .= $pattern{mt_rand(0,$max)};
	}
	return $key;
}

/*********************************************************
 * Author    : Jean Nizama
 * Function  : enviarCorreo
 * Parameters: $email email del destinatario, $usuario nombre del destinatario
			   $asunto asunto del email, $mensaje cuerpo del mensaje, $archivos array de rutas de archivos adjuntos
 * Return    : Estatus del envio
 * Example   : enviarCorreo("destinatario@dominio.com","Carmen Perez","Datos de Acceso al Sistema","<html>...</html>","");
 **********************************************************/
function enviarCorreo($email,$usuario,$asunto,$mensaje,$archivos=array())
{
	$mail=new PHPMailer();
	
	$mail->SMTPDebug  = MAIL_DEBUG;
	
	$mail->IsSMTP();
	$mail->SMTPAuth=true;
	
	$mail->Host= MAIL_HOST;
	$mail->Port= MAIL_PORT;
	
	$mail->Username= MAIL_USER;
	$mail->Password= MAIL_PASS;
	
	if (defined("MAIL_SECURE_SMTP")) {
		$mail->SMTPSecure= MAIL_SECURE_SMTP;
	}
	
	if (defined("MAIL_FROM")) {
		$mail->SetFrom(MAIL_FROM, MAIL_FROMNAME);
		
		if ( MAIL_REPLYTO == 1 ) {
			$mail->AddReplyTo(MAIL_FROM, MAIL_FROMNAME);
		}
	}
	
	$mail->SMTPKeepAlive = true;
	
	$mail->WordWrap   = MAIL_WORDWRAP; // set word wrap
	
	
	$mail->Subject=$asunto;
	$mail->AddAddress($email,$usuario);
	$mail->IsHTML(true); // send as HTML
	$mail->MsgHTML($mensaje);
	
	//attachments
	$totalElementos = count($archivos);
	for ($i = 0; $i < $totalElementos; $i++) 
	{
		$mail->AddAttachment($array[$i], $array[$i+1]); // attachment
		$i++;
	}
	
	//send
	if(!$mail->Send()) 
	{
		throw new Exception("Error al Enviar Correo Electr&oacute;nico.- ".$mail->ErrorInfo);
	} 
	else
	{
		$mail->ClearAddresses();
		$mail->ClearReplyTos();
		$mail->SmtpClose();
		
		return true;
	}
}
?>