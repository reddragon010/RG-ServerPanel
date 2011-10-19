<?php

/*
 * Based on TCSoap from MiniManager <http://code.google.com/p/mm-dev/>
 */

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

/**
 * Description of TrinitySoapClient
 *
 * @author Michael Riedmann
 */
class TrinitySoapClient {
    public $location = '';
    
    private $soap = NULL;
    private $config = array();
    
    const NO_SUCH_CMD = 101;
    
    private $error_msgs = array(
        'NO_SUCH_CMD' => 'Es gibt keinen solchen Befehl',
        'NO_ITEM_FOUND' => 'Keine Items gefunden!'
    );

    public function __construct($config=array()) {
        $this->config = $config;
    }

    public function connect($user=null, $pass=null, $host=null, $port=null) {
        if (is_null($user)) $user = $this->config['user'];
        if (is_null($pass)) $pass = $this->config['pass'];
        if (is_null($host)) $host = $this->config['host'];
        if (is_null($port)) $port = $this->config['port'];
        
        if (empty($this->location)) $this->location = "http://" . $host . ":" . $port . "/";
        $this->soap = new SoapClient(NULL, array(
                    "location" => $this->location,
                    "uri" => "urn:TC",
                    "user_agent" => "RG-ServerPanel",
                    "style" => SOAP_RPC,
                    "login" => $user,
                    "password" => $pass,
                    "trace" => 1,
                    "exceptions" => 0
                ));

        if (is_soap_fault($this->soap)) {
            throw new Exception("SOAP Error! (" . $this->soap->faultcode . ") " . $this->soap->faultstring);
            return false;
        }
        return true;
    }
    
    public function disconnect() {
        if (!empty($this->soap)) {
            $this->soap = NULL;
            return true;
        } else {
            return false;
        }
    }
    
    public function fetch($command) {
        if ($this->soap == NULL)
            throw new Exception('SOAP Error: Not connected!');

        $params = func_get_args();
        array_shift($params);

        $command = vsprintf($command, $params);
        $answer = $this->soap->executeCommand(new SoapParam($command, "command"));

        if (is_soap_fault($this->soap)) {
            throw new Exception("SOAP Error! (" . $this->soap->faultcode . ") " . $this->soap->faultstring);
            return false;
        }
        
        $result = $this->getResult($this->soap->__getLastResponse());
        
        $error = $this->is_error($result);
        if($error == false){
            return $result;
        } else {
            throw new Exception('SOAP Error! ('. $error .') ' . $this->error_msgs[$error]);
        }
    }

    private function is_error($result){
        foreach($this->error_msgs as $code=>$error){
            if(substr_count($result, $error) > 0)
                    return $code;
        }
        return false;
    }
    
    private function getResult($xmlresponse) {
        $start = strpos($xmlresponse, '<?xml');
        $end = strrpos($xmlresponse, '>');
        $soapdata = substr($xmlresponse, $start, $end - $start + 1);

        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $soapdata, $vals, $index);
        xml_parser_free($xml_parser);
        
        if (array_key_exists("RESULT", $index)){
            $result = $vals[$index['RESULT'][0]]['value'];
        } elseif (array_key_exists("FAULTSTRING", $index)){
            throw new Exception(trim($vals[$index['FAULTSTRING'][0]]['value']));
        }
        
        if (!empty($result))
            return trim($result);
        else
            return "";
    }
    
    public function is_connected(){
        if($this->soap != NULL && get_class($this->soap) == "SoapClient")
                return true;
        return false;
    }
    
    public function lookup($type, $pattern){
        $answer = $this->fetch("lookup $type $pattern");
        $lines = explode("\n", $answer);
        $result = array();
        foreach($lines as $line){
            $split = explode(" - ", $line);
            if(!empty($split[1]))
                $result[] = array('id' => trim($split[0]), 'name' => trim($split[1]));
        }
        return $result;
    }
    
    public function kick($charname){
        $answer = $this->fetch("kick $charname");
        return $answer;
    }
}
