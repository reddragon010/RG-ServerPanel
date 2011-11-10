<?php
/*
 *    Copyright (C) 2011 Michael Riedmann <michael.riedmann@gmx.net>    
 *
 *    his file is part of RG-ServerPanel.
 *
 *    RG-ServerPanel is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    RG-ServerPanel is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with RG-ServerPanel.  If not, see <http://www.gnu.org/licenses/>.
 */

class Realm extends BaseModel {
    public $online = NULL;
    public $uptime = NULL;
    
    static $dbname = 'login';
    static $table = 'realmlist';
    static $fields = array(
      'id',
      'name',
      'address',
      'port',
      'icon',
      'allowedsecuritylevel',
      'gamebuild'
    );
    
    private $soap_client = NULL;
    
    public static function find_all_available(){
        $databases = Config::instance('databases')->get_value(Environment::$name);
        $available_realm_ids = array_keys($databases['realm']);
        return self::find()->where(array('id IN (' . join(',',$available_realm_ids) . ')' ))->all();
    }
    
    function get_status($force=false) {
        if ($this->online == NULL || $force) {
            if (!$sock = @fsockopen($this->address, $this->port, $num, $error, 3))
                $this->online = true;
            else {
                $this->online = false;
                fclose($sock);
            }
        }
        return $this->online;
    }
    
    function get_acl(){
        return AccountAccess::find()->where(array('realmid' => $this->id))->order('gmlevel DESC')->all();
    }
    
    function get_soap(){
        if($this->soap_client == NULL || get_class($this->soap_client) != 'SoapClient' || !$this->soap_client->is_connected()){
            $this->soap_client = new RealmSoapClient($this->address);
            if(!$this->soap_client->connect()){
                $this->errors[] = "Can't connect to SOAP-Interface on {$this->soap_client->location}";
                return false;
            }
        }
        return $this->soap_client;
    }
}
