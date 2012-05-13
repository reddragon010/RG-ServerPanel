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
use Dreamblaze\Framework\Core\Environment;

class RealmSoapClient extends \RisingGods\Trinity\SoapClient {
    public function __construct($host){
        $config = Environment::get_value('soap');
        if(!empty($config)){
            if(!isset($config['host']))
                $config['host'] = $host;
            parent::__construct($config);
        } else {
            throw new Exception('Soap not configured');
        }
    }
}
