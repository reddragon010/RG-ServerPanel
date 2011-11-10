<?php

/*
 * Copyright (C) 2011 Michael Riedmann
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class CharacterTest extends PHPUnit_Framework_TestCase {
    var $source_realmid = 1;
    var $target_realmid = 2;
    var $guid = 4556;
    var $char;
    
    function setUp() {
        $this->char = Character::find()->realm($this->source_realmid)->where(array('guid' => $this->guid))->first();
    }
    /*
    function testWriteDump(){
        echo $this->char->write_dump();
        var_dump($char->errors);
    }
    
    function testLoadDump(){
        echo $this->char->load_dump_to_realm($this->target_realmid);
        var_dump($char->errors);
    }
    */
    function testErase(){
        echo $this->char->erase();
        var_dump($char->errors);
        $this->assertEquals(isset(Character::find()->realm($this->source_realmid)->where(array('guid' => $this->guid))->first()->name),false);
        echo $this->char->load_dump_to_realm($this->source_realmid);
        var_dump($char->errors);
        $this->assertEquals(isset(Character::find()->realm($this->source_realmid)->where(array('guid' => $this->guid))->first()->name),true);
    }
     
}
