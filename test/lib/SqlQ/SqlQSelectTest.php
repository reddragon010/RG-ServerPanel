<?php

class SqlQSelectTest extends PHPUnit_Framework_TestCase {

    var $table = 'testTable';
    var $fields = array('id', 'testid', 'tField1', 'testField2');
    var $fields_str;
    var $pk = 'testid';

    function setUp() {
        $this->fields_str = SqlQBase::fields_to_sql($this->fields, $this->table);
    }

    function testArgsTableFieldsPK() {
        $sql = new SqlQSelect($this->table, $this->fields, $this->pk);

        $testString = "SELECT {$this->fields_str} FROM {$this->table}";
        $this->assertEquals($testString, (string) $sql);
    }

    function testArgsTableFields() {
        $sql = new SqlQSelect($this->table, $this->fields);

        $testString = "SELECT {$this->fields_str} FROM {$this->table}";
        $this->assertEquals($testString, (string) $sql);
    }

    function testArgsTable() {
        $sql = new SqlQSelect($this->table);
        
        $testString = "SELECT {$this->table}.* FROM {$this->table}";
        $this->assertEquals($testString, (string) $sql);
    }
    
    function testWhere(){
        $sql = new SqlQSelect($this->table,$this->fields);
        $testString = "SELECT {$this->fields_str} FROM {$this->table} WHERE {$this->table}.id = :id";
        
        $testValues = array(':id' => 1);
        
        $sql->where(array('id' => 1));
        $this->assertEquals($testString, (string) $sql);
        $this->assertEquals($testValues,  $sql->sql_values);
    }
    
    function testWhereLike(){
        $sql = new SqlQSelect($this->table,$this->fields);
        $testString = "SELECT {$this->fields_str} FROM {$this->table} WHERE {$this->table}.id LIKE :id";
        
        $testValues = array(':id' => '1%');
        
        $sql->where(array('id' => '1%'));
        $this->assertEquals($testString, (string) $sql);
        $this->assertEquals($testValues,  $sql->sql_values);
    }
    
    function testDistinct(){
        $sql = new SqlQSelect($this->table,$this->fields);
        $testString = "SELECT DISTINCT {$this->fields_str} FROM {$this->table}";
        
        $sql->distinct();
        $this->assertEquals($testString, (string) $sql);
    }

    function testCount(){
        $sql = new SqlQSelect($this->table,$this->fields);
        $testString = "SELECT count(*) as c FROM {$this->table} WHERE testTable.testid = :testid";
        $testValues = array(':testid' => '12');
        
        $sql->where(array('testid' => '12'))->count();
        $this->assertEquals($testString, (string) $sql);
        $this->assertEquals($testValues,  $sql->sql_values);
    }
    
}

