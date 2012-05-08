<?php

/**
 * 
 */

namespace Dreamblaze\SqlS;
use Exception;

class Database_Exception extends Exception {
    
    function __construct($message, $code=0,Exception $previous=null) {
        if(empty($message) && isset($previous)){
            $message = $previous->getMessage();
        }
        parent::__construct($message, $code, $previous);
    }

}
