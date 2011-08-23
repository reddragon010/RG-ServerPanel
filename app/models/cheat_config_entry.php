<?php

class CheatConfigEntry extends BaseModel {
    static $table = 'anticheat_config';
    static $fields = array('checktype', 'description');
    static $primary_key = 'checktype';
}