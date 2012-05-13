<?php

namespace Dreamblaze\SqlS;

class Test_QuerySelect extends PHPUnit_Framework_TestCase {

    var $table = 'testTable';
    var $fields = array('id', 'testid', 'tField1', 'testField2');
    var $fields_str;
    var $pk = 'testid';

    function setUp() {
        $this->fields_str = Query_Base::fields_to_sql($this->fields, $this->table);
    }

    function testArgsTableFieldsPK() {
        $sqlobj = new Query_Select($this->table, $this->fields, $this->pk);

        $testString = "SELECT {$this->fields_str} FROM {$this->table}";
        list($sql, $values) = $sqlobj->give_sql_and_values();
        $this->assertEquals($testString, (string) $sql);
    }

    function testArgsTableFields() {
        $sql = new Query_Select($this->table, $this->fields);

        $testString = "SELECT {$this->fields_str} FROM {$this->table}";
        $this->assertEquals($testString, (string) $sql);
    }

    function testArgsTable() {
        $sqlobj = new Query_Select($this->table);
        list($sql, $values) = $sqlobj->give_sql_and_values();
        $testString = "SELECT {$this->table}.* FROM {$this->table}";
        $this->assertEquals($testString, (string) $sql);
    }
    
    function testWhere(){
        $sqlobj = new Query_Select($this->table,$this->fields);
        $testString = "SELECT {$this->fields_str} FROM {$this->table} WHERE {$this->table}.id = :id";
        list($sql, $values) = $sqlobj->give_sql_and_values();
        
        $testValues = array(':id' => 1);
        
        $sql->where(array('id' => 1));
        $this->assertEquals($testString, (string) $sql);
        $this->assertEquals($testValues,  $values);
    }
    
    function testWhereLike(){
        $sqlobj = new Query_Select($this->table,$this->fields);
        list($sql, $values) = $sqlobj->give_sql_and_values();
        
        $testString = "SELECT {$this->fields_str} FROM {$this->table} WHERE {$this->table}.id LIKE :id";
        
        $testValues = array(':id' => '1%');
        
        $sql->where(array('id' => '1%'));
        $this->assertEquals($testString, (string) $sql);
        $this->assertEquals($testValues,  $values);
    }
    
    function testDistinct(){
        $sqlobj = new Query_Select($this->table,$this->fields);
        $testString = "SELECT DISTINCT {$this->fields_str} FROM {$this->table}";
        
        $sqlobj->distinct();
        list($sql, $values) = $sqlobj->give_sql_and_values();
        
        $this->assertEquals($testString, (string) $sql);
    }

    function testCount(){
        $sqlobj = new Query_Select($this->table,$this->fields);
        $testString = "SELECT count(*) as c FROM {$this->table} WHERE testTable.testid = :testid";
        $testValues = array(':testid' => '12');
        
        $sql->where(array('testid' => '12'))->count();
        list($sql, $values) = $sqlobj->give_sql_and_values();
        
        $this->assertEquals($testString, (string) $sql);
        $this->assertEquals($testValues,  $values);
    }
    
}

