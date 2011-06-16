<?php
class AccountBan extends BaseModel {
    static $dbname = 'login';
    static $table = 'account_banned';
    static $fields = array('id', 'bandate', 'unbandate', 'bannedby', 'banreason', 'active');
}
