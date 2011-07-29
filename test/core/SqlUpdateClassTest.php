<?php

class SqlUpdateTest extends PHPUnit_Framework_TestCase {
    var $table = 'testTable';
    var $fields = array('id', 'testid', 'tField1', 'testField2');
    var $fields_str;
    var $pk = 'testid';
    var $testString;
    var $testValues;
    var $testResultValues;

    function setUp() {
        $this->fields_str = SqlQuery::fields_to_sql($this->fields, $this->table);
        $this->testString = "UPDATE {$this->table} SET id=:id,testid=:testid,tField1=:tField1,testField2=:testField2 ";
        $this->testValues = array('tField1' => 'Blub');
        $this->testResultValues = array(':tField1' => 'Blub');
    }

    function testArgsTableFieldsPK() {
        $sql = new SqlUpdate($this->table, $this->fields, $this->pk);

        $sql->set($this->testValues);
        
        $this->assertEquals($this->testString, (string) $sql);
        $this->assertEquals($sql->sql_values, $this->testResultValues);
    }

    function testArgsTableFields() {
        $sql = new SqlUpdate($this->table, $this->fields);
        
        $sql->set($this->testValues);
        
        $this->assertEquals($this->testString, (string) $sql);
        $this->assertEquals($sql->sql_values, $this->testResultValues);
    }
}
