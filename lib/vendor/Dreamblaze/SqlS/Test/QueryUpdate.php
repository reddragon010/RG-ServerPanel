<?php

namespace Dreamblaze\SqlS;

class Test_QueryUpdate extends PHPUnit_Framework_TestCase {
    var $table = 'testTable';
    var $fields = array('id', 'testid', 'tField1', 'testField2');
    var $fields_str;
    var $pk = 'testid';
    var $testString;
    var $testValues;
    var $testResultValues;

    function setUp() {
        $this->fields_str = Query_Base::fields_to_sql($this->fields, $this->table);
        $this->testString = "UPDATE {$this->table} SET tField1 = :tfield1 ,testField2 = :testField2";
        $this->testValues = array('tfield1' => 'Blub', 'testField2' => 'Bluu');
        $this->testResultValues = array(':tfield1' => 'Blub', ':testField2' => 'Bluu');
    }

    function testArgsTableFieldsPK() {
        $sql = new Query_Update($this->table, $this->fields, $this->pk);

        $sql->set($this->testValues);

        list($sql_text, $values) = $sql->give_sql_and_values();
        $this->assertEquals($this->testString, $sql_text);
        $this->assertEquals($values, $this->testResultValues);
    }

    function testArgsTableFields() {
        $sql = new Query_Update($this->table, $this->fields);
        
        $sql->set($this->testValues);

        list($sql_text, $values) = $sql->give_sql_and_values();
        $this->assertEquals($this->testString, $sql_text);
        $this->assertEquals($values, $this->testResultValues);
    }
}
