ALTER TABLE `sessions` ADD `user_id` INT  NULL  DEFAULT NULL  AFTER `session_expire`;
ALTER TABLE `sessions` ADD `current_url` TINYTEXT  NULL  AFTER `user_id`;
ALTER TABLE `sessions` ADD `current_ip` TINYTEXT  NULL  AFTER `current_url`;
