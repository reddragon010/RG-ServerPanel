<?php

class Character extends BaseModel {

    static $table = 'characters';
    static $primary_key = 'guid';
    static $fields = array(
        'guid',
        'name',
        'online',
        'map',
        'zone',
        'account',
        'race',
        'class',
        'gender',
        'level',
        'money',
        'totaltime'
    );
    public $user;

    public function after_build() {
        if (!empty($this->account))
            $this->user = User::find($this->account);
    }

}