<?php

#----------------------------------------------------------------------
# Archivo de configuracion
#
# Contiene configuraciones relacionadas con el acceso a base de datos
#----------------------------------------------------------------------


#----------------------------------------------------------------------
# Section [TITULO]
# Titulo inicial de las ventanas
# [Variables]
#   DB_DRIVER   : Driver a utilizar. Depende del manejador de base de datos
#                 [Posibles valores]
#                    "PGSQL"  : PostgreSQL
#                    "MYSQL"  : MySQL   (No implementado completamente)
#                    "DB2"    : DB2     (No implementado completamente)
#                    "ORACLE" : Oracle  (No implementado completamente)
#                    "ODBC"   : ODBC    (No implementado completamente)
#                    "SQLITE" : Sqlite  (No implementado completamente)
#   DB_HOST     : Host de Base de datos
#   DB_PORT     : Puerto
#   DB_USER     : Nombre de usuario
#   DB_PASSWORD : Clave (Se deja en blanco en caso de no utilizar)
#   DB_SCHEMA   : Nombre de database/schema a utilizar
#----------------------------------------------------------------------

define("DB_DRIVER",   "PGSQL");
define("DB_HOST",     "localhost");
define("DB_PORT",     5432);
define("DB_USER",     "postgres");
define("DB_PASSWORD", "postgres");
define("DB_SCHEMA",   "postgres");

?>
