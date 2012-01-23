<?php

function error_handler($level, $message, $file, $line, $context) {
    if($level === E_USER_ERROR || $level === E_USER_WARNING || $level === E_USER_NOTICE){
        echo "USER ERROR: $message";
        if($level === E_USER_ERROR)
            die();
        return true;
    } 
    return false;
}

set_error_handler('error_handler');