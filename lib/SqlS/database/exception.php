<?php

/**
 * 
 */
class SqlS_DatabaseException extends Exception {
    
    function __construct($message, $code=null, $previous=null) {
        if(empty($message) && isset($previous)){
            $message = $previous->getMessage();
        }
        parent::__construct($message, $code, $previous);
    }

}
