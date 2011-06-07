<?php

/**
 * 
 */
class SQLInsertTest extends PHPUnit_Framework_TestCase {

    var $table = 'testTable';
    var $fields = array('id', 'testid', 'tField1', 'testField2');
    var $fields_str = "id,testid,tField1,testField2";
    var $pk = 'testid';

    function setUp() {
        
    }

    function testArgsTableFieldsPK() {
        $sql = new SQLInsert($this->table, $this->fields, $this->pk);

        $values = array('tField' => 'Blub');
        $values_str = "Blub";
        $sql->values($values);
        $testString = "INSERT INTO {$this->table} ({$this->fields_str}) VALUES ({$values_str})";
        $this->assertEquals($testString, (string) $sql);
    }

    function testArgsTableFields() {
        $sql = new SQLInsert($this->table, $this->fields);

        $values = array('tField' => 'Blub');
        $values_str = "Blub";
        $sql->values($values);
        $testString = "INSERT INTO {$this->table} ({$this->fields_str}) VALUES ({$values_str})";
        $this->assertEquals($testString, (string) $sql);
    }

    function testArgsTable() {
        $sql = new SQLInsert($this->table);

        $values = array('tField' => 'Blub');
        $values_str = "Blub";
        $sql->values($values);
        $testString = "INSERT INTO {$this->table} VALUES ({$values_str})";
        $this->assertEquals($testString, (string) $sql);
    }

}
