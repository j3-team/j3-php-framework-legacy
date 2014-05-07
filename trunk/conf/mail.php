<?php

#----------------------------------------------------------------------
# Archivo de configuracion
#
# Contiene configuraciones relacionadas con el envio de correos
#----------------------------------------------------------------------

#----------------------------------------------------------------------
# Section [MODE]
# LOGS
# [Variables]
#   MAIL_DEBUG  : SI/NO: 1/0
#----------------------------------------------------------------------
define("MAIL_DEBUG", "0");

#----------------------------------------------------------------------
# Section [GENERAL]
# Configuracion general
# [Variables]
#   MAIL_HOST  : Servidor de correo
#   MAIL_PORT  : Puerto
#   MAIL_USER  : Usuario
#   MAIL_PASS  : Password
#----------------------------------------------------------------------
define("MAIL_HOST", "p3plcpnl0357.prod.phx3.secureserver.net");
define("MAIL_PORT", 465);
define("MAIL_USER", "info@bartickers.com");
define("MAIL_PASS", "1nf02014");
define("MAIL_SECURE_SMTP", "ssl");


#----------------------------------------------------------------------
# Section [GENERAL]
# Configuracion general
# [Variables]
#   MAIL_FROM      : Correo "from"
#   MAIL_FROMNAME  : Nombre "from"
#   MAIL_WORDWRAP  : Word wrap
#   MAIL_REPLYTO   : Responder a:
#                    [Posibles valores]
#                       0 : NO
#                       1 : SI 
#----------------------------------------------------------------------
define("MAIL_FROM", "info@bartickers.com");
define("MAIL_FROMNAME", "Bartickers");
define("MAIL_WORDWRAP", 80);
define("MAIL_REPLYTO", 1);

?>
