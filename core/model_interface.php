<?php

interface ModelInterface {
    public static function find($pk=null);
    public static function create($params, &$obj);
    public static function build($data, $new);
    public static function set_dbname($dbname);
    public static function set_dbid($id);
    public static function get_fields();
}
