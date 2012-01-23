<?php

class SqlS_DatabaseConnection {

    /**
     * The PDO connection object.
     * @var mixed
     */
    public $connection;
    /**
     * The last query run.
     * @var string
     */
    public $last_query;
    /**
     * The name of the protocol that is used.
     * @var string
     */
    public $protocol;
    /**
     * Default PDO options to set for each connection.
     * @var array
     */
    static $PDO_OPTIONS = array(
        PDO::ATTR_CASE => PDO::CASE_LOWER,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false);
    /**
     * The quote character for stuff like column and field names.
     * @var string
     */
    static $QUOTE_CHARACTER = '`';
    /**
     * Default port.
     * @var int
     */
    static $DEFAULT_PORT = 0;
    

    public function __construct($info) {
        try {
            // unix sockets start with a /
            if ($info->host[0] != '/') {
                $host = "host=$info->host";

                if (isset($info->port))
                    $host .= ";port=$info->port";
            }
            else
                $host = "unix_socket=$info->host";

            $this->connection = new PDO("$info->protocol:$host;dbname=$info->db", $info->user, $info->pass, static::$PDO_OPTIONS);
        } catch (PDOException $e) {
            SqlS_ToolLogger::error($e->getMessage() . "\n" . $e->getTraceAsString() . "\n");
            throw new SqlS_DatabaseException("Can't connect to database {$info->db}", $e->getCode() , $e);
        }
    }

    /**
     * Retrieves column meta data for the specified table.
     *
     * @param string $table Name of a table
     * @return array An array of {@link Column} objects.
     */
    public function columns($table) {
        $columns = array();
        $sth = $this->query_column_info($table);

        while (($row = $sth->fetch())) {
            $c = $this->create_column($row);
            $columns[$c->name] = $c;
        }
        return $columns;
    }

    public function columns_array($table) {
        $fields = array();
        $sth = $this->query_column_info($table);

        while ($row = $sth->fetch()) {
            if (isset($row['Field']))
                $fields[] = $row['Field'];
        }
        return $fields;
    }

    /**
     * Escapes quotes in a string.
     *
     * @param string $string The string to be quoted.
     * @return string The string with any quotes in it properly escaped.
     */
    public function escape($string) {
        return $this->connection->quote($string);
    }
   
    /**
     * Retrieve the insert id of the last model saved.
     *
     * @param string $sequence Optional name of a sequence to use
     * @return int
     */
    public function insert_id($sequence=null) {
        return $this->connection->lastInsertId($sequence);
    }

    /**
     * Execute a raw SQL query on the database.
     *
     * @param string $sql Raw SQL string to execute.
     * @param array &$values Optional array of bind values
     * @return mixed A result set object
     */
    public function query($sql, $values=array()) {
        $this->last_query = $sql;
        SqlS_ToolLogger::debug($sql . ' => ' . var_export($values,true));
        
        try {
            if (!($sth = $this->connection->prepare($sql)))
                SqlS_ToolLogger::error('PDO Prepare ERROR!' . ' SQL:' . $sql);
        } catch (PDOException $e) {
            SqlS_ToolLogger::error(array($e->getMessage(), $e->getTraceAsString()));
        }

        $sth->setFetchMode(PDO::FETCH_ASSOC);
        
        try {
            if (!$sth->execute($values)){
                SqlS_ToolLogger::error(array('PDO Exec ERROR!', "SQL: " . $sql , "VALUES: " . var_export($values, true) ,'PDO: ' . $sth->errorInfo()));
                throw new SqlS_DatabaseException("PDO Exec ERROR! (1)", $e->getCode() , $e);
            }

        } catch (PDOException $e) {
            SqlS_ToolLogger::error(array('PDO Exec ERROR!' , "SQL: " . $sql , "VALUES: " . var_export($values, true) , "PDO: " . $e->getMessage()));
            throw new SqlS_DatabaseException("PDO Exec ERROR! (2)", $e->getCode() , $e);
        }
        return $sth;
    }

    /**
     * Execute a query that returns maximum of one row with one field and return it.
     *
     * @param string $sql Raw SQL string to execute.
     * @param array &$values Optional array of values to bind to the query.
     * @return string
     */
    public function query_and_fetch_one($sql, $values=array()) {
        $sth = $this->query($sql, $values);
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    /**
     * Execute a raw SQL query and fetch the results.
     *
     * @param string $sql Raw SQL string to execute.
     * @param Closure $handler Closure that will be passed the fetched results.
     */
    public function query_and_fetch($sql, Closure $handler, $values=array()) {
        $sth = $this->query($sql, $values);
        $result = array();
        while (($row = $sth->fetch(PDO::FETCH_ASSOC))) {
            $result[] = $handler($row);
        }
        return $result;
    }

    /**
     * Returns all tables for the current database.
     *
     * @return array Array containing table names.
     */
    public function tables() {
        $tables = array();
        $sth = $this->query_for_tables();

        while (($row = $sth->fetch(PDO::FETCH_NUM)))
            $tables[] = $row[0];

        return $tables;
    }

    /**
     * Quote a name like table names and field names.
     *
     * @param string $string String to quote.
     * @return string
     */
    public function quote_name($string) {
        return $string[0] === static::$QUOTE_CHARACTER || $string[strlen($string) - 1] === static::$QUOTE_CHARACTER ?
                $string : static::$QUOTE_CHARACTER . $string . static::$QUOTE_CHARACTER;
    }

}
