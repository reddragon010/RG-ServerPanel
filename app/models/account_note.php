<?php

class AccountNote extends BaseModel {
    static $dbname = 'web';
    static $table = 'account_notes';
    static $fields = array('account_id', 'comment', 'updated_at');
    static $primary_key = 'account_id';
}
