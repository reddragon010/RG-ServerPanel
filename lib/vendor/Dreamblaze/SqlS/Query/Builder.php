<?php

namespace Dreamblaze\SqlS;

class Query_Builder {
    public static function select($dbobject){
        return new Query_Select($dbobject);
    }
    
    public static function insert($dbobject){
        return new Query_Insert($dbobject);
    }
    
    public static function update($dbobject){
        return new Query_Update($dbobject);
    }
}

