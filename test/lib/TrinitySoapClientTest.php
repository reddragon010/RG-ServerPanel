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
class TrinitySoapClientTest extends PHPUnit_Framework_TestCase {
    private $client;
    
    function setUp() {
        $config = Environment::get_value('soap');
        $config['host'] = '127.0.0.1';
        $this->client = new TrinitySoapClient($config);
        $this->client->connect();
    }
/*
    function testFetching() {
        $result = $this->client->fetch('lookup item %s', 'Robe');
    }
    
    function testLookupItems() {
        $result = $this->client->lookup('item', 'Robe');
        $this->assertEquals(count($result),  932);
    }
 
    function testKick() {
        $result = $this->client->kick('gammler');
        echo $result;
    }
 
    
    function testCharDump() {
        $result = $this->client->dump_char('Robigo', '/tmp/test.sql');
        echo $result;
    }
 */   
    function testCharErase() {
        $result = $this->client->delete_char('Robigo');
    }
}

?>
