<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of StupidPrlf.
 *
 *    StupidPrlf is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    StupidPrlf is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with StupidPrlf.  If not, see <http://www.gnu.org/licenses/>.
 */

class Toolbox {
    /**
   * Translates a camel case string into a string with underscores (e.g. firstName -> first_name)
   * @param    string   $str    String in camel case format
   * @return   string   $str    Translated into underscore format
   */
  public static function from_camel_case($str) {
    $str[0] = strtolower($str[0]);
    $func = create_function('$c', 'return "_" . strtolower($c[1]);');
    return preg_replace_callback('/([A-Z])/', $func, $str);
  }
 
  /**
   * Translates a string with underscores into camel case (e.g. first_name -> firstName)
   * @param    string   $str                     String in underscore format
   * @param    bool     $capitalise_first_char   If true, capitalise the first char in $str
   * @return   string   $str                     translated into camel caps
   */
  public static function to_camel_case($str, $capitalise_first_char = false) {
    if($capitalise_first_char) {
      $str[0] = strtoupper($str[0]);
    }
    $func = create_function('$c', 'return strtoupper($c[1]);');
    return preg_replace_callback('/_([a-z])/', $func, $str);
  }
  
  /**
     * Use this for any adapters that can take connection info in the form below
     * to set the adapters connection info.
     *
     * <code>
     * protocol://username:password@host[:port]/dbname
     * protocol://urlencoded%20username:urlencoded%20password@host[:port]/dbname?decode=true
     * protocol://username:password@unix(/some/file/path)/dbname
     * </code>
     *
     * Sqlite has a special syntax, as it does not need a database name or user authentication:
     *
     * <code>
     * sqlite://file.db
     * sqlite://../relative/path/to/file.db
     * sqlite://unix(/absolute/path/to/file.db)
     * sqlite://windows(c%2A/absolute/path/to/file.db)
     * </code>
     *
     * @param string $connection_url A connection URL
     * @return object the parsed URL as an object.
     */
    public static function parse_database_connection_url($connection_url) {
        $url = @parse_url($connection_url);

        if (!isset($url['host']))
            throw new Exception('Database host must be specified in the connection string. If you want to specify an absolute filename, use e.g. sqlite://unix(/path/to/file)');

        $info = new SqlS_DatabaseConfig();
        $info->protocol = $url['scheme'];
        $info->host = $url['host'];
        $info->db = isset($url['path']) ? substr($url['path'], 1) : null;
        $info->user = isset($url['user']) ? $url['user'] : null;
        $info->pass = isset($url['pass']) ? $url['pass'] : null;

        $allow_blank_db = ($info->protocol == 'sqlite');

        if ($info->host == 'unix(') {
            $socket_database = $info->host . '/' . $info->db;

            if ($allow_blank_db)
                $unix_regex = '/^unix\((.+)\)\/?().*$/';
            else
                $unix_regex = '/^unix\((.+)\)\/(.+)$/';

            if (preg_match_all($unix_regex, $socket_database, $matches) > 0) {
                $info->host = $matches[1][0];
                $info->db = $matches[2][0];
            }
        } elseif (substr($info->host, 0, 8) == 'windows(') {
            $info->host = urldecode(substr($info->host, 8) . '/' . substr($info->db, 0, -1));
            $info->db = null;
        }

        if ($allow_blank_db && $info->db)
            $info->host .= '/' . $info->db;

        if (isset($url['port']))
            $info->port = $url['port'];

        if (strpos($connection_url, 'decode=true') !== false) {
            if ($info->user)
                $info->user = urldecode($info->user);

            if ($info->pass)
                $info->pass = urldecode($info->pass);
        }

        if (isset($url['query'])) {
            foreach (explode('/&/', $url['query']) as $pair) {
                list($name, $value) = explode('=', $pair);

                if ($name == 'charset')
                    $info->charset = $value;
            }
        }

        return $info;
    }

    public static function stripNewline($text){
        $text = str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $text);
        return trim(preg_replace("/\s+/", ' ',$text));
    }
}

?>
