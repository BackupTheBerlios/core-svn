UPDATE `core_config` SET `config_value` = '0.4.4' WHERE `config_name` = 'core_version';
INSERT INTO `core_config` ( `config_name` , `config_value` ) VALUES ( 'get_rss', '1');
ALTER TABLE `core_devlog` CHANGE `comments_allow` `comments_allow` TINYINT( 1 ) NOT NULL DEFAULT '1'
