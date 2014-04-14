<?php
require_once("dbconnection.php");

define("DB_EQUAL",      1);
define("DB_LESS",       2);
define("DB_MORE",       3);
define("DB_LESS_EQUAL", 4);
define("DB_MORE_EQUAL", 5);
define("DB_NULL",       6);
define("DB_NOT_NULL",   7);
define("DB_LIKE",       8);
define("DB_ILIKE",      9);
define("DB_IN",        10);
define("DB_NOT_IN",    11);
define("DB_ASC",       12);
define("DB_DESC",      13);


/** Clase para los modelos
	 @author Jhon Freitez
	 @date 2010-11-17
*/
class DbModel {
	private $conn;	
	private $id;
	private $tableName;
	private $fieldsByName;
	private $fieldsByPos;
	private $result;
	private $cursor;
	private $conditionOperators;
	private $conditionFields;	
	private $orderField;	
	private $endIN;
	
	/** Constructor.
	 * @param id. ID del registro (Si existe en la BD)
	 */
	public function __construct($id = null) {
		try {
			$this->conn = new DbConnection();
			$this->conn->conectar();
		} catch (Exception $e) {
			throw $e;
		}

		$this->tableName = strtolower(get_class($this));
		$this->clear();
		
		if ($id != null) {
			$this->id = $id;
		}
	}
	
	
	/** Retorna el ID de la consulta ejecutada anteriormente.
	 */
	public function getId() {
		return $this->id;
	}
	
	
	/** Limpia. XD
	*/
	public function clear() {
		$this->cursor = 0;
		$this->id = null;
		unset($this->conditionOperators);
		unset($this->conditionFields);
		unset($this->fieldsByName);
		unset($this->fieldsByPos);
		unset($this->orderFields);
		
		$this->conditionOperators = array();
		$this->conditionFields = array();
		$this->orderFields = array();
		
		$this->endIN = false;
	}
	
	
	private function getOperator($i, $k, $o) {
		if (strcmp($o, "##") != 0) {
			$cond = $k . $o;
			if ($i > 0) {
				if ($this->endIN == true) {
					$this->endIN = false;
					return ") AND " . $cond;
				} else {
					return " AND " . $cond;
				}
			} else {
				return $cond;
			}
		} else {
			$this->endIN = true;
			return ", ";
		}
	}
	
	/** Genera la tira SQL con las condiciones
	*/
	private function getConditions() {
		
		$condiciones = null;

		$i = 0;
		if (is_array($this->conditionFields))
			foreach ($this->conditionFields as $key => $value) {
				$condiciones = $condiciones . $this->getOperator($i, $key, $this->conditionOperators[$i]) . $this->conn->getPreparedStatementVar($i+1);
				$i = $i+1;
			}
			
		if ($this->endIN == true) {
			$this->endIN = false;
			$condiciones = $condiciones . ")";
		}
			
		return $condiciones;
	}
	
	private function getOrders() {
		$orders = null;
		$i = 0;
		if (is_array($this->orderFields))
			foreach ($this->orderFields as $key => $value) {
				if ($i > 0) {
					$orders = $orders . ", " . $key . " " . $value;
				} else {
					$orders = "ORDER BY " . $key . " ". $value;
				}
				$i = $i+1;
			}
		
		return $orders;
	}
	
	/** Realiza un SELECT en la BD. Retorna todos los que coincidan.
       @return bool.
	*/
	public function doSelectAll() {
		if ($this->tableName == null) {
			throw new Exception ("Tabla no especificada");
			return false;
		}
	
		$condiciones = $this->getConditions();
		
		$orders = $this->getOrders();
		
		if ($condiciones == null) {
			try {
				$this->result = $this->conn->ejecutarSQL("SELECT * FROM $this->tableName $orders");
			} catch (Exception $e) {
				throw $e;
				return false;
			}
		} else {
		
			try {
				$this->conn->preparar("SELECT * FROM $this->tableName WHERE $condiciones $orders");
				$this->result = $this->conn->ejecutar(array_values($this->conditionFields));
			} catch (Exception $e) {
				throw $e;
				return false;
			}
		}

		$this->clear();
		return true;
	}
	
	
	/** Realiza un SELECT en la BD con el id especificado. Retorna sólo uno.
       @param id Valor entero con el id
       @return bool
   */
	public function doSelectOne($id) {
		try {
			$this->result = $this->conn->ejecutarSQL("SELECT * FROM $this->tableName WHERE id=$id");
		} catch (Exception $e) {
			throw $e;
			return false;
		}
		$this->clear();
		return true;
	}


	/** Realiza un SELECT en la BD. Retorna todos los que coincidan.
       @param tableForeign Nombre de la tabla foranea
       @param fieldName Nombre del campo que contiene la clave foranea. Si se omite se asume como "id_nombreTabla" en singular.
       @return bool
	*/
	public function doSelectAllWithForeign($tableForeign, $fieldName = null) {
		if ($fieldName == null) {
			$fieldName = "id_$tableForeign";
			if ($tableForeign[strlen($tableForeign)-1] == "s")
				$fieldName = substr($fieldName, 0, strlen($fieldName)-1);
		}
		
		if ($this->tableName == null) {
			throw new Exception ("Tabla no especificada");
			return false;
		}
	
		$condiciones = $this->getConditions();
		
		$orders = $this->getOrders();

		if ($condiciones == null) {
			try {
				$this->result = $this->conn->ejecutarSQL("SELECT * FROM $this->tableName, $tableForeign WHERE $fieldName=$tableForeign.id $orders");
			} catch (Exception $e) {
				throw $e;
				return false;
			}
		} else {
			$this->result = $this->conn->preparar("SELECT * FROM $this->tableName, $tableForeign WHERE $fieldName=$tableForeign.id AND $condiciones $orders");
		
			try {
				$this->result = $this->conn->ejecutar(array_values($this->conditionFields));
			} catch (Exception $e) {
				throw $e;
				return false;
			}
		}

		$this->clear();
		return true;
	}

   
	/** Realiza un SELECT en la BD con el campo y valor especificado. Retorna todos los que coincidan.
       @param field Campo a evaluar.
       @param value Valor a comparar.
       @param compType Tipo de comparación (IGUAL, MENOR, MAYOR, MENOR_IGUAL, MAYOR_IGUAL).
       @return bool
	*/
	public function doSelectAllWithCondition($field, $value, $compType = DB_EQUAL) {
		if ($this->tableName == null) {
			throw new Exception ("Tabla no especificada");
			return false;
		}

		$comp;
		switch ($compType) {
			case DB_EQUAL:
				$comp = "=";
				break;
			case DB_LESS:
				$comp = "<";
				break;
			case DB_MORE:
				$comp = ">";
				break;
			case DB_LESS_EQUAL:
				$comp = "<=";
				break;
			case DB_MORE_EQUAL:
				$comp = ">=";
				break;
			case DB_LIKE:
				$comp = "LIKE";
				break;
			case DB_ILIKE:
				$comp = "ILIKE";
				break;
			case DB_IN:
				$comp = "IN";
				break;
			case DB_NOT_IN:
				$comp = "NOT IN";
				break;
		}

		$this->result = $this->conn->preparar("SELECT * FROM $this->tableName WHERE $field $comp $1");
		try {
			$this->result = $this->conn->ejecutar(array($value));
		} catch (Exception $e) {
			throw $e;
			return false;
		}
		
		$this->clear();
		return true;
	}

	
	/** Realiza un INSERT en la BD.
		@return bool
    */
    public function doSave() {
		if ($this->tableName == null) {
			throw new Exception ("Tabla no especificada");
			return false;
		}

		$campos = "";
		$valores = "";
		$keys = array_keys($this->fieldsByName);
		for ($i=0; $i<count($keys); $i=$i+1) {
			if ($i > 0) {
				$campos = $campos . ", " . $keys[$i];
				$valores = $valores . ", $" . ($i+1);
			} else {
				$valores = "$" . ($i+1);
				$campos = $keys[$i];
			}
		}

		$this->result = $this->conn->preparar("INSERT INTO $this->tableName ($campos) VALUES ($valores)");
		
		try {
			$this->result = $this->conn->ejecutar(array_values($this->fieldsByName));
		} catch (Exception $e) {
			throw $e;
			return false;
		}

		$this->clear();
		$this->id = $this->getLastId();
		return true;
	}


    /** Realiza un UPDATE en la BD.
        @return bool
    */
    public function doUpdate() {
		if ($this->tableName == null) {
			throw new Exception ("Tabla no especificada");
			return false;
		} else if ($this->id == null) {
			throw new Exception ("ID no especificado");
			return false;
		}

		$campos = "";
		$valores = "";
		$keys = array_keys($this->fieldsByName);
		for ($i=0; $i<count($keys); $i=$i+1) {
			if ($i > 0) {
				$campos = $campos . ", " . $keys[$i] . "=$" . ($i+1);
			} else {
				$campos = $keys[$i] . "=$" . ($i+1);
			}
		}

		$this->result = $this->conn->preparar("UPDATE $this->tableName SET $campos WHERE id=$this->id");
		
		try {
			$this->result = $this->conn->ejecutar(array_values($this->fieldsByName));
		} catch (Exception $e) {
			throw $e;
			return false;
		}
	
		$this->clear();
		return true;
	}


    /** Realiza un DELETE en la BD.
        @return bool
    */
    public function doDelete() {
		if ($this->tableName == null) {
			throw new Exception ("Tabla no especificada");
			return false;
		} else if ($this->id == null) {
			throw new Exception ("ID no especificado");
			return false;
		}

		try {
			$this->result = $this->conn->ejecutarSQL("DELETE FROM $this->tableName WHERE id=$this->id");
		} catch (Exception $e) {
			throw $e;
			return false;
		}
	
		$this->clear();
		return true;
	}


    /** Realiza un DELETE en la BD para todos los registros que cumplan con la condicion dada.
        @param field Campo a evaluar.
        @param value Valor a comparar.
        @param compType Tipo de comparación (Igual, Menor, Mayor, Menor o igual, Mayor o igual).
        @return bool
    */
    public function doDeleteAll($field, $value, $compType = DB_EQUAL) {
		if ($this->tableName == null) {
			throw new Exception ("Tabla no especificada");
			return false;
		}

		$comp;
		switch ($compType) {
			case DB_EQUAL:
				$comp = "=";
				break;
			case DB_LESS:
				$comp = "<";
				break;
			case DB_MORE:
				$comp = ">";
				break;
			case DB_LESS_EQUAL:
				$comp = "<=";
				break;
			case DB_MORE_EQUAL:
				$comp = ">=";
				break;
			case DB_LIKE:
				$comp = " LIKE ";
				break;
			case DB_ILIKE:
				$comp = " ILIKE ";
				break;
			case DB_IN:
				$comp = " IN ";
				break;
			case DB_NOT_IN:
				$comp = " NOT IN ";
				break;
		}
		
		$this->result = $this->conn->preparar("DELETE FROM $this->tableName WHERE $field $comp $1");
		try {
			$this->result = $this->conn->ejecutar(array($value));
		} catch (Exception $e) {
			throw $e;
			return false;
		}
		
		$this->clear();
		return true;
	}


    /** Ejecuta el query especificado en la BD.
        @param query String con el query a ejecutar.
        @return Resultser
    */
    public function execQuery($query) {
		try {
			$this->result = $this->conn->ejecutarSQL($query);
		} catch (Exception $e) {
			throw $e;
			return false;
		}
	
		$this->clear();
		return true;
	}


    /** Retorna la descripción del error (En caso de haberlo).
        @return String
    */
    public function lastError() {
		return $this->conn->getLastError();
	}


    /** Agrega una condicion para la BD.
        @param field Campo a evaluar.
        @param value Valor a comparar.
        @param compType Tipo de comparación (Igual, Menor, Mayor, Menor o igual, Mayor o igual).
        @return bool
    */
    public function addCondition($field, $value, $compType = DB_EQUAL) {
		$comp;
		$array = false;
		switch ($compType) {
			case DB_EQUAL:
				$comp = "=";
				break;
			case DB_LESS:
				$comp = "<";
				break;
			case DB_MORE:
				$comp = ">";
				break;
			case DB_LESS_EQUAL:
				$comp = "<=";
				break;
			case DB_MORE_EQUAL:
				$comp = ">=";
				break;
			case DB_LIKE:
				$comp = " LIKE ";
				break;
			case DB_ILIKE:
				$comp = " ILIKE ";
				break;
			case DB_IN:
				$comp = " IN (";
				$array = true;
				break;
			case DB_NOT_IN:
				$comp = " NOT IN (";
				$array = true;
				break;
		}
		
		if ($array == true) {
			$i = 0;
			$arr = explode( ",", $value );
			foreach ($arr as $val) {
				if ($i > 0) {
					$this->conditionFields[$field . $i] = $val;
					array_push($this->conditionOperators, "##");
				} else {
					$this->conditionFields[$field] = $val;
					array_push($this->conditionOperators, $comp);
				}
			}
		} else {
			$this->conditionFields[$field] = $value;
			array_push($this->conditionOperators, $comp);
		}
		
		
	}
	
	
	/** Agrega un criterio de orden para la consulta.
        @param field Campo a traves del cual se ordenara.
        @param type Tipo de ordenacion (ASC, DESC).
    */
    public function addOrderBy($field, $type = DB_ASC) {
		$typeS;
		switch ($type) {
			case DB_ASC:
				$typeS = "ASC";
				break;
			case DB_DESC:
				$typeS = "DESC";
				break;
		}
		$this->orderFields[$field] = $typeS;
	}


    /** Asigna el valor dado al campo establecido. Se usa antes de un updateIt y SaveIt.
        @param field Campo a asignar el valor.
        @param value Valor a asignar.
    */
    public function setValue($field, $value) {
		$this->fieldsByName[$field] = $value;
	}


    /** Retorna el valor del campo especificado.
        @param field Campo a retornar su valor.
        @param position Si el nombre del campo se repite, se indica la posicion para saber a cual se refiere.
        @return String: Valor del campo.
    */
    public function getValueTwo($field, $position) {
	
	}

    /** Retorna el valor del campo especificado.
        @param field Campo a retornar su valor.
        @return String
    */
    public function getValue($name) {
		return $this->fieldsByName[$name];
	}

	/** Retorna el valor del campo ubicado en la posición dada.
        @param pos Posición del campo a retornar su valor.
        @return String
    */
	public function getValueByPos($pos) {
		return $this->fieldsByPos[$pos];
	}
	
	
    /** Mueve el apuntador al siguiente registro del resultado de la consulta.
        @return bool
    */
    public function next() {
		$this->fieldsByName = $this->conn->getFetchAssoc($this->result);
		$this->seek($this->cursor);
		$this->fieldsByPos = $this->conn->getFetchArray($this->result);
		$this->cursor = $this->cursor + 1;
		if (isset($this->fieldsByName["id"]))
			$this->id = $this->fieldsByName["id"];
		return $this->fieldsByName;
	}

	
 	public function getResult() {
		return $this->result;
	}

	
	/** Mueve el apuntador a la posicion dada.
		@param pos Posición del cursor (comienzo = 0).
	*/
	public function seek($pos) {
		$this->conn->seek($this->result, $pos);
		$this->cursor = $pos;
	}
	
	
	/** Retorna el numero de filas de la consulta.
		@return int
	*/
	public function rows() {
		return $this->conn->getNumRows( $this->result );
	}
	
	/** Mueve el apuntador al comienzo.
	*/
	public function first() {
		$this->conn->seek($this->result, 0);
		$this->cursor = 0;
	}
	
	
    /** Retorna la lista de los nombres de los campos que me trajo el query.
        @return Array
    */
    public function fieldNames() {
		$count = $this->conn->getNumFields($this->result);
		$array = array();
		for ($i=0; $i<$count; $i=$i+1) {
			$array[$i] = $this->conn->getFieldName($this->result, $i);
		}
		return $array;
	}
	

	/** Retorna una instancia de la clase foránea especificada, con los datos respectivos.
		@param tableName Nombre del Modelo que corresponde a la tabla relacionada
		@param fieldName Nombre del campo que contiene la clave foranea. Si se omite se asume como "id_nombreTabla" en singular.
	*/
	public function getForeign($tableName, $fieldName = null) {
		$tableName = strtolower($tableName);
		if ($fieldName == null) {
			$fieldName = "id_$tableName";
			if ($tableName[strlen($tableName)-1] == "s")
				$fieldName = substr($fieldName, 0, strlen($fieldName)-1);
		}
	
		if (!class_exists($tableName))
			throw new Exception("Tabla no existe");
		else {
			$cc = new $tableName;
			$cc->doSelectOne( $this->getValue( $fieldName ) );
			$cc->next();
			return $cc;
		}		
	}
	
	
	/** Retorna la conexion actual.
		@return Dbconnection
	*/
	public function getConnection() {
		return $this->conn;
	}
	
	
	/** Retorna el id del ultimo registro existente en la tabla.
		@return int
	*/
	public function getLastId() {
		if ($this->execQuery("SELECT MAX(id) FROM ".$this->tableName)) {
			if ($this->next()) {
				return $this->getValueByPos(0);
			} else {
				return 0;
			}
		} else
			return -1;
	}
	
	
	/** Lee un archivo y retorna el contenido de manera que pueda ser guardado como un dato tipo blob.
		@param path Ruta del archivo.
		@return data del archivo o null en caso de error
	*/
	public function escapeBlob($path) {
		return $this->conn->escapeBlob($path);
	}
	
	
	/** Toma el valor de un dato tipo BLOB y lo guarda en un archivo.
		@param path Ruta del archivo.
		@param data Contenido binario.
		@return int.
	*/
	public function unescapeBlob($path, $data) {
		return $this->conn->unescapeBlob($path, $data);
	}


	/** Actualiza un registro agregando el dato blob
		@param field Campo de la tabla.
		@param value Valor a guardar.
	*/
	public function doUpdateBlob($field, $value) {
		try {
			$this->result = $this->conn->ejecutarSQL("UPDATE $this->tableName SET $field='{$value}' WHERE id=$this->id");
		} catch (Exception $e) {
			throw $e;
		}
		$this->clear();
		return true;
	}
}
?>
