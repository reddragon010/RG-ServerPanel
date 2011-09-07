<?php

interface ModelInterface {
    public static function find($type, $options, $additions);
    public static function count($options);
    public static function create($params, &$obj);
    public static function build($data, $new);
    public static function set_dbname($dbname);
    public static function set_dbid($id);
    public static function get_fields();
}
