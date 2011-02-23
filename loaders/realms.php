<?php
foreach($config['realm'] as $realm_id => $realm){
	$realms[$realm_id] = new Realm($realm_id);
}