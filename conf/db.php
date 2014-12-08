<?php

#----------------------------------------------------------------------
# Archivo de configuracion
#
# Contiene configuraciones relacionadas con el acceso a base de datos
#----------------------------------------------------------------------


#----------------------------------------------------------------------
# Section [MANAGER]
# Configuracion del DB Manager
# [Variables]
#   DB_DRIVER     : Driver a utilizar. Depende del manejador de base de datos
#                   [Posibles valores]
#                      "PGSQL"  : PostgreSQL
#                      "MYSQL"  : MySQL   (No implementado completamente)
#                      "DB2"    : DB2     (No implementado completamente)
#                      "ORACLE" : Oracle  (No implementado completamente)
#                      "ODBC"   : ODBC    (No implementado completamente)
#                      "SQLITE" : Sqlite  (No implementado completamente)
#   DB_HOST       : Host de Base de datos
#   DB_PORT       : Puerto
#   DB_USER       : Nombre de usuario
#   DB_PASSWORD   : Clave (Se deja en blanco en caso de no utilizar)
#   DB_SCHEMA     : Nombre de database/schema a utilizar
#   DB_PERSISTENT : ¿Usar conexion a bd persistente?
#                   [Posibles valores]
#                      0 : NO
#                      1 : SI
#----------------------------------------------------------------------

define("DB_DRIVER",   "PGSQL");
define("DB_HOST",     "localhost");
define("DB_PORT",     5432);
define("DB_USER",     "postgres");
define("DB_PASSWORD", "postgres");
define("DB_SCHEMA",   "postgres");
define("DB_PERSISTENT",   "1");

#----------------------------------------------------------------------
# Section [IDS]
# Configuracion de los ID's de tablas
# [Variables]
#   DB_ID     : Especifica el modo en que las tablas usan el campo ID:
#               [Posibles valores]
#                  1 : Campo "id"
#                  2 : Campo "id_<nombre_tabla>" (puede ser en singular)
#                  3 : Campo "<nombre_tabla>_id" (puede ser en singular)
#----------------------------------------------------------------------
define("DB_ID", 3);

?>
