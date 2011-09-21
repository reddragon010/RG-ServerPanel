<?php

class AccountAccess extends BaseModel {
    static $dbname = 'login';
    static $table = 'account_access';
    static $fields = array('id', 'gmlevel', 'realmid');
    static $per_page = 1000;
    
    static $relations = array(
        'account' => array(
            'model' => 'Account',
            'type' => 'has_one',
            'field' => 'id',
            'fk' => 'id'
        )
    );
}
