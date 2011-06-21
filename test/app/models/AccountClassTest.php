<?php

class AccountTest extends PHPUnit_Framework_TestCase {

    var $table = 'testTable';
    var $fields = array('id', 'testid', 'tField1', 'testField2');
    var $fields_str = "id,testid,tField1,testField2";
    var $pk = 'testid';
    
    var $account;
    
    function setUp() {
        $this->account = Account::find(1);
    }

    function testBaseAttributes(){
        $acc = $this->account;
        $this->assertEquals($acc->id, 1);
        $this->assertEquals($acc->username, 'ACCOUNT1');
        $this->assertEquals($acc->email, 'acc1@test.com');
        $this->assertEquals($acc->joindate, '2011-04-11 20:02:06');
        $this->assertEquals($acc->last_ip, '127.0.0.1');
        $this->assertEquals($acc->locked, 0);
    }
    
    function testCharsRelation(){
        $chars = $this->account->characters;
        $this->assertEquals(count($chars), 5);
        $this->assertEquals($chars[0]->data, array(
            "guid"      =>"1",
            "name"      =>"Menschenkrie",
            "online"    =>"0",
            "map"       =>"1",
            "zone"      =>"876",
            "account"   =>"1",
            "race"      =>"1",
            "class"     =>"1",
            "gender"    =>"1",
            "level"     =>"80",
            "money"     =>"2143800016",
            "totaltime" =>"1610"
        ));
    }
    
    function testAccountRelation(){
        $accs = $this->account->accounts_with_same_ip;
        $this->assertEquals(count($accs), 5);
    }
    
    function testBansRelation(){
        $bans = $this->account->bans;
        $this->assertEquals(count($bans), 1);
    }
    
    function testAccessLevelsRelation(){
        $levels = $this->account->access_levels;
        $this->assertEquals(count($levels), 2);
        $this->assertEquals($levels[0]->gmlevel, 3);
        $this->assertEquals($levels[0]->realmid, -1);
    }
    
    function testAccessRealmsRelation(){
        $realms = $this->account->access_realms;
        $this->assertEquals(count($realms), 1);
    }
    
    function testLowestGmLevel(){
        $this->assertEquals($this->account->lowest_gm_level, 2);
    }
    
    function testHighestGmLevel(){
        $this->assertEquals($this->account->highest_gm_level, 3);
    }
}
