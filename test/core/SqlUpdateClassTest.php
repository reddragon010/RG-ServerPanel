<?php

class SqlUpdateTest extends PHPUnit_Framework_TestCase {
    var $table = 'testTable';
    var $fields = array('id', 'testid', 'tField1', 'testField2');
    var $fields_str;
    var $pk = 'testid';

    function setUp() {
        $this->fields_str = SqlQuery::fields_to_sql($this->fields, $this->table);
    }

    function testArgsTableFieldsPK() {
        $sql = new SqlUpdate($this->table, $this->fields, $this->pk);

        $values = array('tField1' => 'Blub');
        $sql->set($values);
        $testString = "UPDATE {$this->table} SET id=?,testid=?,tField1=?,testField2=? ";
        $this->assertEquals($testString, (string) $sql);
        $this->assertEquals($sql->sql_values, array_values($values));
    }

    function testArgsTableFields() {
        $sql = new SqlUpdate($this->table, $this->fields);

        $values = array('tField1' => 'Blub');
        $sql->set($values);
        $testString = "UPDATE {$this->table} SET id=?,testid=?,tField1=?,testField2=? ";
        $this->assertEquals($testString, (string) $sql);
        $this->assertEquals($sql->sql_values, array_values($values));
    }
}
