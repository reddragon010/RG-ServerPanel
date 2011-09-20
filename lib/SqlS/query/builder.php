<?php

class SqlS_QueryBuilder {
    public static function select($dbobject){
        return new SqlS_QuerySelect($dbobject);
    }
    
    public static function insert($dbobject){
        return new SqlS_QueryInsert($dbobject);
    }
    
    public static function update($dbobject){
        return new SqlS_QueryUpdate($dbobject);
    }
}

