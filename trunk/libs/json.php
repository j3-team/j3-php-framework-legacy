<?php
 /*********************************************************
 * Author    : José Rodríguez (Realmente un Copie y Pega jejejeje)
 * Function  : array_to_json
 * Parameters: arreglo PHP
 * Return    : Cadena de caracteres en formato json
 * Example   : array_to_json(array('label'=>'foo','value'=>'bar',...))  cadena => [{"label":"foo","value"=>"bar"},...]
 * Example   : array_to_json(array('foo','bar',...))  cadena => [{"foo"},{"bar"},...] 
 **********************************************************/
function array_to_json($array){
	    if(!is_array( $array )) return false;
	
	    $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
	    if( $associative ){
	        $construct = array();
	        foreach( $array as $key => $value ){
	            // Format the key:
	            if( is_numeric($key) ){
	                $key = "key_$key";
	            }
	            $key = "\"".addslashes($key)."\"";
	
	            // Format the value:
	            if( is_array( $value )){
	                $value = array_to_json( $value );
	            } else if( !is_numeric( $value ) || is_string( $value ) ){
	                $value = "\"".addslashes($value)."\"";
	            }
	
	            // Add to staging array:
	            $construct[] = "$key: $value";
	        }
	        // Then we collapse the staging array into the JSON form:
	        $result = "{ " . implode( ", ", $construct ) . " }";
	    } else { // If the array is a vector (not associative):
	        $construct = array();
	        foreach( $array as $value ){
	            // Format the value:
	            if( is_array( $value )){
	                $value = array_to_json( $value );
	            } else if( !is_numeric( $value ) || is_string( $value ) ){
	                $value = "'".addslashes($value)."'";
	            }
	            // Add to staging array:
	            $construct[] = $value;
	        }
	        // Then we collapse the staging array into the JSON form:
	        $result = "[ " . implode( ", ", $construct ) . " ]";
	    }
	    return $result;
	}
?>