<?php
include("libs/phpmailer/class.phpmailer.php");
include("libs/phpmailer/class.smtp.php");

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
	$mail->IsSMTP();
	//$mail->SMTPAuth=true;				//enable SMTP authentication
	//$mail->SMTPSecure="ssl";          //sets the prefix to the servier
	$mail->Host="172.16.16.91";      	//sets the SMTP server
	$mail->Port=25;                   	//set the SMTP port
	$mail->Username= "jean.nizama"; 	//username
	$mail->Password= "12345678";        //password
	$mail->From= "escuela.fiscales@mp.gob.ve";
	$mail->FromName="Escuela Nacional de Fiscales";
	$mail->Subject=$asunto;
	//$mail->AltBody    = "This is the body when user views in plain text format"; //Text Body
	$mail->WordWrap   = 80; // set word wrap
	$mail->MsgHTML($mensaje);
	$mail->AddReplyTo("escuela.fiscales@mp.gob.be","Escuela Nacional de Fiscales");
	$totalElementos = count($archivos);
	for ($i = 0; $i < $totalElementos; $i++) 
	{
		$mail->AddAttachment($array[$i], $array[$i+1]); // attachment
		$i++;
	}
	$mail->AddAddress($email,$usuario);
	$mail->IsHTML(true); // send as HTML
	if(!$mail->Send()) 
	{
		throw new Exception("Error al Enviar Correo Electr&oacute;nico.- ".$mail->ErrorInfo);
	} 
	else
	{
		return true;
	}
}
?>