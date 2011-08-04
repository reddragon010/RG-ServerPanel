<?php

class SqlQUpdateTest extends PHPUnit_Framework_TestCase {
    var $table = 'testTable';
    var $fields = array('id', 'testid', 'tField1', 'testField2');
    var $fields_str;
    var $pk = 'testid';
    var $testString;
    var $testValues;
    var $testResultValues;

    function setUp() {
        $this->fields_str = SqlQBase::fields_to_sql($this->fields, $this->table);
        $this->testString = "UPDATE {$this->table} SET tField1 = :tfield1 ,testField2 = :testField2";
        $this->testValues = array('tfield1' => 'Blub', 'testField2' => 'Bluu');
        $this->testResultValues = array(':tfield1' => 'Blub', ':testField2' => 'Bluu');
    }

    function testArgsTableFieldsPK() {
        $sql = new SqlQUpdate($this->table, $this->fields, $this->pk);

        $sql->set($this->testValues);
        
        $this->assertEquals($this->testString, (string) $sql);
        $this->assertEquals($sql->sql_values, $this->testResultValues);
    }

    function testArgsTableFields() {
        $sql = new SqlQUpdate($this->table, $this->fields);
        
        $sql->set($this->testValues);
        
        $this->assertEquals($this->testString, (string) $sql);
        $this->assertEquals($sql->sql_values, $this->testResultValues);
    }
}
