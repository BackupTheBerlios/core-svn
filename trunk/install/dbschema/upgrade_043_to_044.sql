-- $Id$

UPDATE `core_config` SET `config_value` = '0.4.4' WHERE `config_name` = 'core_version';

ALTER TABLE core_pages 
    ADD node_separately CHAR(1) 
    DEFAULT '0' NOT NULL;

INSERT INTO `core_config` ( `config_name` , `config_value` )
VALUES ( 'core_rss', '0');

ALTER TABLE `core_users` CHANGE `login` `login` VARCHAR( 32 ) NULL DEFAULT '0';
