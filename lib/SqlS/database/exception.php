<?php

/**
 * 
 */
class SqlS_DatabaseException extends Exception {
    
    function __construct($message, $code=0, $previous=null) {
        if(empty($message) && isset($previous)){
            $message = $previous->getMessage();
        }
        parent::__construct($message, $code, $previous);
    }

}
