<?php
include '../testDBObject.php';
/**
 * 
 */
class Test_QueryInsert extends PHPUnit_Framework_TestCase {
    var $sql;

    function setUp() {
        $dbobj = new testDBObject();
        $this->sql = new Query_Insert($dbobj);
    }
    
    function testQuery(){        
        $data = array(
           'testField1' => 'test',
           'testField2' => 'test2',
           'name' => 'testRecord'
        );
        $this->sql->values($data);
        
        list($query,$values) = $this->sql->give_sql_and_values();
        $this->assertEquals("INSERT INTO testTable (testTable.testField1, testTable.testField2, testTable.name) VALUES (:testField1,:testField2,:name)", $query);
        
        $test_data = array();
        foreach($data as $key=>$val){
            $test_data[':' . $key] = $val;
        }
        $this->assertEquals($test_data, $values);
    }
    
    function testCommands(){
        $data = array(
           'testField1' => 'test',
           'testField2' => '#NOW',
        );
        $this->sql->values($data);
        
        list($query,$values) = $this->sql->give_sql_and_values();
        $this->assertEquals("INSERT INTO testTable (testTable.testField1, testTable.testField2) VALUES (:testField1,NOW())", $query);
        
        $test_data = array(
            ':testField1' => 'test'
        );
        $this->assertEquals($test_data, $values);
    }
    
    function testWrongCommandFallback(){
        $data = array(
           'testField1' => 'test',
           'testField2' => '#NOWE',
        );
        $this->sql->values($data);
        
        list($query,$values) = $this->sql->give_sql_and_values();
        $this->assertEquals("INSERT INTO testTable (testTable.testField1, testTable.testField2) VALUES (:testField1,:testField2)", $query);
        
        $test_data = array(
            ':testField1' => 'test',
            ':testField2' => '#NOWE'
        );
        $this->assertEquals($test_data, $values);
    }
    
    /*
    function testArgsTableFieldsPK() {
        $sql = new SqlS_QueryInsert($this->table, $this->fields, $this->pk);

        $values = array('tField' => 'Blub');
        $sql->values($values);
        $testString = "INSERT INTO {$this->table} ({$this->fields_str}) VALUES (?)";
        $this->assertEquals($testString, (string) $sql);
        $this->assertEquals($sql->sql_values, array_values($values));
    }

    function testArgsTableFields() {
        $sql = new SqlS_QueryInsert($this->table, $this->fields);

        $values = array('tField' => 'Blub');
        $sql->values($values);
        $testString = "INSERT INTO {$this->table} ({$this->fields_str}) VALUES (?)";
        $this->assertEquals($testString, (string) $sql);
        $this->assertEquals($sql->sql_values, array_values($values));
    }

    function testArgsTable() {
        $sql = new SqlS_QueryInsert($this->table);
        
        $values = array('tField' => 'Blub');
        $sql->values($values);
        $testString = "INSERT INTO {$this->table} VALUES (?)";
        $this->assertEquals($testString, (string) $sql);
        $this->assertEquals($sql->sql_values, array_values($values));
    }
    */
}
