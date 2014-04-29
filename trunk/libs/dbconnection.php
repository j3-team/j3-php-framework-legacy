<?php
require_once("libs/log4php/Logger.php");
require_once("libs/FirePHPCore/FirePHP.class.php");
/** Clase que gestiona la conexión a la Base de Datos
	 @author Jhon Freitez
	 @date 2010-11-11
*/
class DbConnection {
	private $connection;
	private $driver;	//MYSQL, PGSQL, DB2, SQLITE, ODBC, ORACLE
	private $hostName;
	private $dbName;
	private $port;
	private $userName;
	private $password;
	private $prepareStatement;
	private $myFirePhp;
	private $logger;
	
	private $arrayFields; //Only MYSQLI prepared stmts
	
	
	/** Constructor parametrizable
		 @param driver Nombre del driver a utilizar
		 @param host Servidor de la base de datos
		 @param port Puerto de conexión
		 @param userName Nombre de usuario
		 @param password Clave
		 @param dbName Nombre de la base de datos a conectarse		 
	*/
//	public function __construct($driver = "PGSQL", $host = "localhost", $port = 5432, $userName = "postgres", $password = "postgres", $dbName = "prueba") {
	public function __construct() {
		require_once("conf/db.php");		
		
		//Firebug
		ob_start();
		$this->myFirePhp = FirePHP::getInstance(true);
		
		//Logger
		Logger::configure('conf/log.xml');
		$this->logger = Logger::getRootLogger();

		$this->connection = "";
		$this->driver = DB_DRIVER;
		$this->hostName = DB_HOST;
		$this->dbName = DB_SCHEMA;
		$this->port = DB_PORT;
		$this->userName = DB_USER;
		$this->password = DB_PASSWORD;
 	}


	/** Retorna la conexion
	*/
	public function getConnection() {
		return $this->connection;
	}
	
	
 	/** Obtiene el último error arrojado por la Base de Datos
 		 @param String
 	*/
	public function getLastError() {
		if ($this->driver == "PGSQL") {
 			
 			return pg_last_error($this->connection);
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			return $this->connection->error . "(" . $this->connection->errno . ")";
 			
 		} elseif ($this->driver == "DB2") {
 			
			//return db2_stmt_errormsg() . "(" . db2_stmt_error() . ")";
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			//return sqlite_last_error(); 			
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			//return odbc_errormsg($this->connection) . "(" . odbc_error($this->connection) . ")";
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			//return ora_error();
 		}
	}
 	
	
 	/** Intenta conectarse a la Base de datos
 	*/
 	public function conectar()	{
 		$notImplemented = false;
 		
 		if ($this->driver == "PGSQL") {

 			$this->connection = pg_connect("host=$this->hostName port=$this->port user=$this->userName password=$this->password dbname=$this->dbName");
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			$this->connection = new mysqli($this->hostName, $this->userName, $this->password, $this->dbName, $this->port);
 			
 		} elseif ($this->driver == "DB2") {
 			
 			$notImplemented = true;
 			
 		} elseif ($this->driver == "SQLITE") {
 			
 			$notImplemented = true;
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			$notImplemented = true;
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			$notImplemented = true;
 			
 		}
 		
		if ($notImplemented) {
			throw new Exception("ERROR: Base de Datos no soportada.");
		} 		
 		
		if (!$this->connection)	{
			$this->logger->error("ERROR: No se pudo conectar a la Base de Datos. - ".$this->getLastError());
			throw new Exception("ERROR: No se pudo conectar a la Base de Datos. - ".$this->getLastError());
		}
 	}
 	
 	
 	/** Intenta desconectarse de la Base de datos
 	*/
	public function desconectar()	{
		
		if ($this->driver == "PGSQL") {
 			
 			$this->connection = pg_close($this->connection);
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			$this->connection->close();
 			
 		} elseif ($this->driver == "DB2") {
 			
 		} elseif ($this->driver == "SQLITE") {
 			
 		} elseif ($this->driver == "ODBC") {
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 		}
		
		if(!$this->conn) 
		{
			throw new Exception("ERROR: No se pudo cerrar la conexión a $this->host:$this->port. - " . $this->getLastError() );
    	}
    	
 	}
 	
 	
	/** Inicia una serie de transacciones. 
		 @return Resulset
	*/ 	
 	public function iniciarTransaccion() {
		$result = false; 		
 		
		if ($this->driver == "PGSQL") {
 			
 			$result = pg_query($this->connection, "begin");
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			$this->connection->autocommit(FALSE);
 			$result = $this->connection->query("START TRANSACTION");
 			
 		} elseif ($this->driver == "DB2") {
 			
 		} elseif ($this->driver == "SQLITE") {
 			
 		} elseif ($this->driver == "ODBC") {
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 		} 		
 		
 		
		if (!$result) {
			throw new Exception( "ERROR: No se pudo iniciar secuencia de transacciones. - " . $this->getLastError() );
		}
		return $result;
	}


	/** Finaliza y confirma una serie de transacciones. 
		 @return Resulset
	*/ 	
	public function confirmarTransaccion() {
		$result = false; 		
 		
		if ($this->driver == "PGSQL") {
 			
 			$result = pg_query($this->connection, "commit");
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			$result = $this->connection->commit();
 			
 		} elseif ($this->driver == "DB2") {
 			
 		} elseif ($this->driver == "SQLITE") {
 			
 		} elseif ($this->driver == "ODBC") {
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 		} 		
 		
		if (!$result) {
			throw new Exception( "ERROR: No se pudo confirmar la secuencia de transacciones. - " . $this->getLastError() );
		}
		return $result;
	}


	/** Realiza el rollback de una serie de transacciones. 
		 @return Resulset
	*/ 	
	public function revertirTransaccion() {
		$result = false; 		
 		
		if ($this->driver == "PGSQL") {
 			
 			$result = pg_query($this->connection, "rollback");
 			
 		} elseif ($this->driver == "MYSQL") {
 			
			$result = $this->connection->rollback();
 			
 		} elseif ($this->driver == "DB2") {
 			
 			$result =  db2_rollback($this->connection);
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			$result =  sqlite_query("rollback"); 			
 			
 		} elseif ($this->driver == "ODBC") {
 			
			$result =  odbc_rollback($this->connection); 			
 			
 		} elseif ($this->driver == "ORACLE") {
 			
			$result =  ora_rollback($this->connection);
 			
 		}
		
		if (!$result) {
			throw new Exception( $this->getLastError() );
		}
		
		return $result;
	}
	
	
	/** Ejecuta una sentencia SQL. 
		 @return Resulset
	*/ 
	public function ejecutarSQL($sql) {
		$result = false; 
 		$this->logger->debug("SQL: ".$sql);
		$this->myFirePhp->log($sql, "SQL");
		if ($this->driver == "PGSQL") {
 			
 			$result = pg_query($this->connection, $sql);
 			
 		} elseif ($this->driver == "MYSQL") {
 			
			$result =  $this->connection->query($sql);
 			
 		} elseif ($this->driver == "DB2") {
 			
 			$result =  db2_exec($this->connection, $sql);
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			$result =  sqlite_query($sql); 			
 			
 		} elseif ($this->driver == "ODBC") {
 			
			$result =  odbc_exec($this->connection, $sql); 			
 			
 		} elseif ($this->driver == "ORACLE") {
 			
			$result =  ora_exec($sql); 			
 			
 		}
		
		if (!$result) {
			throw new Exception( $this->getLastError() );
		}
		
		return $result;
	}


	/** Prepara un statement para ejecutarlo posteriormente con "execute".
		 @param query Cadena con el query
	*/
	public function preparar($query) {
		$this->logger->debug("SQL: ".$query);
		$this->myFirePhp->log($query, "SQL");
		
		if ($this->driver == "PGSQL") {
			
			$this->prepareStatement = $this->randomString();
 			return pg_prepare($this->connection, $this->prepareStatement, $query);
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			$this->arrayFields = null;
 			return ($this->prepareStatement = $this->connection->prepare($query));
 			
 		} elseif ($this->driver == "DB2") {
 			
			//return db2_stmt_errormsg() . "(" . db2_stmt_error() . ")";
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			//return sqlite_last_error(); 			
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			//return odbc_errormsg($this->connection) . "(" . odbc_error($this->connection) . ")";
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			//return ora_error();
 		}
 		
 		return false;
	}
	
	
	/** Ejecuta el query preparado anteriormente.
		 @param values Arregl de valores
		 @return Resulset	
	*/
	public function ejecutar($values) {
		$this->logger->debug("Valores del prepared:".implode(",",$values) );
		$this->myFirePhp->log($values, "Valores del prepared:");

		$this->bindParams($values);
		
		if ($this->driver == "PGSQL") {
 			
 			return pg_execute($this->connection, $this->prepareStatement, $values);
 			
 		} elseif ($this->driver == "MYSQL") {
 			if ($this->prepareStatement->execute()) {
 				$this->myFirePhp->log("OK");
 				//$res = $this->prepareStatement->get_result();
 				//$this->prepareStatement->close();

 				//get field names
 				$meta = $this->prepareStatement->result_metadata();
 				$this->arrayFields = array();
 				$params = array();
 				while($field = $meta->fetch_field()) {
			        $params[] = &$this->arrayFields[$field->name];
			    }
			    call_user_func_array(array($this->prepareStatement, 'bind_result'), $params);
			    
 				return $this->prepareStatement;
 			} else {
 				$this->myFirePhp->log("KO: " . $this->getLastError());
 				return NULL;
 			} 
 			
 		} elseif ($this->driver == "DB2") {
 			
			//return db2_stmt_errormsg() . "(" . db2_stmt_error() . ")";
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			//return sqlite_last_error(); 			
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			//return odbc_errormsg($this->connection) . "(" . odbc_error($this->connection) . ")";
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			//return ora_error();
 		}
 		
 		return false;
	}
	
	
	/** genera una cadena aleatoria.
	*/
	function randomString($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE) {
		$source = 'abcdefghijklmnopqrstuvwxyz';
		if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if($n==1) $source .= '1234567890';
		if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
		if($length>0){
			$rstr = "";
			$source = str_split($source,1);
			for($i=1; $i<=$length; $i++){
				mt_srand((double)microtime() * 1000000);
				$num = mt_rand(1,count($source));
				$rstr .= $source[$num-1];
			}

		}
		return $rstr;
	}
	
	
	/** Lee un archivo y retorna el contenido de manera que pueda ser guardado como un dato tipo blob.
		@param path Ruta del archivo.
		@return data del archivo o null en caso de error
	*/
	public function escapeBlob($path) {
		$this->logger->debug("Escaping BLOB: $path" );
		$this->myFirePhp->log($path, "Escaping BLOB:");
	
		if (file_exists($path) == false) {
			$this->logger->debug("ERROR: Archivo $path no existe." );
			$this->myFirePhp->log($path, "ERROR: Archivo no existe:");
			throw new Exception( "ERROR: Archivo $path no existe." );
			return null;
		}
	
		$data = file_get_contents($path);
		
		if ($this->driver == "PGSQL") {
 			
 			return pg_escape_bytea($data);
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			//return mysql_error() . "(" . mysql_errno(); . ")";
 			
 		} elseif ($this->driver == "DB2") {
 			
			//return db2_stmt_errormsg() . "(" . db2_stmt_error() . ")";
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			//return sqlite_last_error(); 			
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			//return odbc_errormsg($this->connection) . "(" . odbc_error($this->connection) . ")";
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			//return ora_error();
 		}
		
		return null;
	}

	
	/** Toma el valor de un dato tipo BLOB y lo guarda en un archivo.
		@param path Ruta del archivo.
		@param data Contenido binario.
		@return int.
	*/
	public function unescapeBlob($path, $data) {
		$content = null;
		if ($this->driver == "PGSQL") {
 			
 			$content = pg_unescape_bytea($data);
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			//return mysql_error() . "(" . mysql_errno(); . ")";
 			
 		} elseif ($this->driver == "DB2") {
 			
			//return db2_stmt_errormsg() . "(" . db2_stmt_error() . ")";
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			//return sqlite_last_error(); 			
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			//return odbc_errormsg($this->connection) . "(" . odbc_error($this->connection) . ")";
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			//return ora_error();
 		}
		
		return file_put_contents($path, $content);
	}
	

	/** Retorna el fetch_assoc del resultado del query
	 *  @param result. Resultado del query. (Recordset)
	 *  @return Array.
	 */
	public function getFetchAssoc($result) {
		if ($this->driver == "PGSQL") {
 			
 			return pg_fetch_assoc($result);
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			//return $result->fetch_assoc();
 			if (is_null($this->prepareStatement)) { //normal stmt
 				return $result->fetch_assoc();
 			} else { // prepared stmt
 				if ($this->prepareStatement->fetch()) {
 					return $this->arrayFields;
 				} else {
 					return null;
 				}
 			} 			
 			
 		} elseif ($this->driver == "DB2") {
 			
			return db2_fetch_assoc($result);
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			//return sqlite_f		
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			//return odbc_fe
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			//return 
 		}
	}
	
	
	/** Retorna el fetch_array del resultado del query
	 *  @param result. Resultado del query. (Recordset)
	 *  @return Array.
	 */
	public function getFetchArray($result) {
		if ($this->driver == "PGSQL") {
 			
 			return pg_fetch_array($result);
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			//return $result->fetch_array();
 			if (is_null($this->prepareStatement)) { //normal stmt
 				return $result->fetch_array();
 			} else { // prepared stmt
 				if ($this->prepareStatement->fetch()) {
 					return array_values($this->arrayFields);
 				} else {
 					return null;
 				}
 			}
 			
 		} elseif ($this->driver == "DB2") {
 			
			return db2_fetch_array($result);
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			return sqlite_fetch_array($result);		
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			return odbc_fetch_array($result);
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			//return ora_error();
 		}
	}
	
	
	/** Mueve el cursor a la posicion dada dentro del result.
	 * @param result. Resultado del query.
	 * @param pos. Posicion.
	 */ 
	public function seek($result, $pos) {
		if ($this->driver == "PGSQL") {
 			
 			pg_result_seek($result, $pos);
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			$result->data_seek($pos);
 			
 		} elseif ($this->driver == "DB2") {
 			
			//db2_mo
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			sqlite_seek($pos);		
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			//odbc_m
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			//ora
 		}
	}
	
	
	/** Retorna el numero de filas en el result.
	 * @param result. Resultado del query.
	 * @return int.
	 */ 
	public function getNumRows($result) {
		if ($this->driver == "PGSQL") {
 			
 			return pg_num_rows($result);
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			return $this->prepareStatement->num_rows;
 			
 		} elseif ($this->driver == "DB2") {
 			
			return db2_num_rows($result);
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			return sqlite_num_rows();
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			return odbc_num_rows($result);
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			//return ora
 		}
	}
	
	
	/** Retorna el numero de campos en el result.
	 * @param result. Resultado del query.
	 * @return int.
	 */ 
	public function getNumFields($result) {
		if ($this->driver == "PGSQL") {
 			
 			return pg_num_fields($result);
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			return mysql_num_fields($result);
 			
 		} elseif ($this->driver == "DB2") {
 			
			return db2_num_fields($result);
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			return sqlite_num_fields();
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			return odbc_num_fields($result);
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			//return ora
 		}
	}
	
	
	/** Retorna el nombre del campo dado en el result.
	 * @param result. Resultado del query.
	 * @param num. Numero del campo.
	 * @return String.
	 */ 
	public function getFieldName($result, $num) {
		if ($this->driver == "PGSQL") {
 			
 			return pg_field_name($result, $num);
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			return mysql_field_name($result, $num);
 			
 		} elseif ($this->driver == "DB2") {
 			
			return db2_field_name($result, $num);
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			return sqlite_field_name($num);
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			return odbc_field_name($result, $num);
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			//return ora
 		}
	}
	

	/** Retorna la variable utilizada por los prepareStatement
	 * @param num Numero de secuencia.
	 * @return String.
	 */ 
	public function getPreparedStatementVar($num) {
		if ($this->driver == "PGSQL") {
 			
 			return "$" . $num;
 			
 		} elseif ($this->driver == "MYSQL") {
 			
 			return "?";
 			
 		} elseif ($this->driver == "DB2") {
 			
			return "?";
 			
 		} elseif ($this->driver == "SQLITE") {
 			
			return "?";
 			
 		} elseif ($this->driver == "ODBC") {
 			
 			return "?";
 			
 		} elseif ($this->driver == "ORACLE") {
 			
 			//return ora
 		}
	}
	
	
	/** Agrega los valores de los par�metros para el prepareStatement
	 * @param values Array con valores.
	 * @return void.
	 */
	public function bindParams($values) {
		
		if ($this->driver == "PGSQL") {
	
			// N/A
	
		} elseif ($this->driver == "MYSQL") {
			
			if ($this->prepareStatement) {
				$types = "";
				$nuevo = array();
				for ($i=0; $i < count($values); $i++) {
					$keys = array_keys($values); 
					$key = $keys[$i];
					$value = $values[$key];
					
					$is_id = ($key == "id") || (strpos($key, "_id")!== false) || (strpos($key, "id_")!== false);
					if ($is_id || is_int($value) || is_long($value)) {
						$t = "i";
					} elseif (is_double($value) || is_float($value)) {
						$t = "d";
					/*} elseif (is_nan($value)) {
						$t = "s";*/
					} else {
						$t = "s"; // b -> blob
					}
					
					$types = $types . $t;

					$nuevo = array_merge($nuevo, array(& $values[$key] ));
				}
				array_unshift($nuevo, $types);
				
				
				if (!call_user_func_array(array($this->prepareStatement, 'bind_param'), $nuevo)) {
					$this->myFirePhp->log("ERROR EN BIND");
				}
				//$this->prepareStatement->bind_param("s", $value);
			}
	
		} elseif ($this->driver == "DB2") {
	
			return "?";
	
		} elseif ($this->driver == "SQLITE") {
	
			return "?";
	
		} elseif ($this->driver == "ODBC") {
	
			return "?";
	
		} elseif ($this->driver == "ORACLE") {
	
			//return ora
		}
	}
}
?>
