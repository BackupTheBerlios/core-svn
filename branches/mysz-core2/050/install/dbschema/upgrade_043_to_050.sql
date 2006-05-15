-- $Id: upgrade_043_to_050.sql 1195 2005-11-04 22:44:03Z mysz $

UPDATE `core_config` SET `config_value` = '0.5.0' WHERE `config_name` = 'core_version';

ALTER TABLE `core_pages` ADD `node_separately` SMALLINT(1) 
    DEFAULT '0' NOT NULL;

INSERT INTO `core_config` ( `config_name` , `config_value` )
    VALUES ( 'core_rss', '1');

ALTER TABLE `core_users` CHANGE `login` `login` VARCHAR( 32 )
    NULL DEFAULT '0';

ALTER TABLE `core_devlog` ADD `image_width` MEDIUMINT UNSIGNED AFTER `image` ,
    ADD `image_height` MEDIUMINT UNSIGNED AFTER `image_width`;

ALTER TABLE `core_devlog` CHANGE `image` `image` VARCHAR( 255 ) NULL;

ALTER TABLE `core_devlog` ADD `image_title` VARCHAR( 255 )
    AFTER `image_height`;

ALTER TABLE `core_devlog` CHANGE `comments_allow` `comments_allow`
    SMALLINT( 1 ) NOT NULL DEFAULT '1';

ALTER TABLE `core_comments` CHANGE `comments_id` `id_news`
    BIGINT UNSIGNED NOT NULL;

ALTER TABLE `core_comments` CHANGE `id` `id`
    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ;

ALTER TABLE `core_assign2cat` CHANGE `id` `id`
    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    CHANGE `news_id` `news_id` BIGINT UNSIGNED NOT NULL DEFAULT '0',
    CHANGE `category_id` `category_id` BIGINT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `core_category` CHANGE `category_id` `category_id`
    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    CHANGE `category_parent_id` `category_parent_id`
    BIGINT UNSIGNED NOT NULL DEFAULT '0',
    CHANGE `category_order` `category_order`
    BIGINT UNSIGNED NOT NULL DEFAULT '1';

ALTER TABLE `core_devlog` CHANGE `id` `id`
    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    CHANGE `title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE
    utf8_general_ci NOT NULL ,
    CHANGE `author` `author` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE
    utf8_general_ci NOT NULL ,
    CHANGE `text` `text` LONGTEXT CHARACTER SET utf8 COLLATE
    utf8_general_ci NOT NULL ;

ALTER TABLE `core_links` CHANGE `id` `id`
    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    CHANGE `link_order` `link_order` BIGINT UNSIGNED NOT NULL DEFAULT '1',
    CHANGE `title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE
    utf8_general_ci NOT NULL ;

ALTER TABLE `core_newsletter` CHANGE `id` `id`
    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    CHANGE `email` `email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE
    utf8_general_ci NOT NULL ;

ALTER TABLE `core_pages` CHANGE `id` `id`
    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    CHANGE `parent_id` `parent_id` BIGINT UNSIGNED NOT NULL DEFAULT '0',
    CHANGE `page_order` `page_order` BIGINT UNSIGNED NOT NULL DEFAULT '1',
    CHANGE `title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE
    utf8_general_ci NOT NULL ,
    CHANGE `text` `text` LONGTEXT CHARACTER SET utf8 COLLATE
    utf8_general_ci NOT NULL ;

ALTER TABLE `core_pages` DROP `image` ;

ALTER TABLE `core_users` CHANGE `email` `email` VARCHAR( 255 )
    CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `core_comments` CHANGE `author` `author` VARCHAR( 255 )
    CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    CHANGE `email` `email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE
    utf8_general_ci NOT NULL ,
    CHANGE `text` `text` LONGTEXT CHARACTER SET utf8 COLLATE
    utf8_general_ci NOT NULL ;


