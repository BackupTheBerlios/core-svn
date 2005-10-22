-- $Id: upgrade_037_to_040.sql 1127 2005-08-03 22:00:41Z mysz $

INSERT INTO `core_config` 
    VALUES ('date_format', 'Y-m-d H:i:s');

ALTER TABLE `core_pages` 
    ADD `assigned_tpl` VARCHAR(255) 
    DEFAULT 'main' NOT NULL;
    
ALTER TABLE `core_category` 
    ADD `category_tpl` VARCHAR(255) 
    DEFAULT 'default' NOT NULL;
    
UPDATE `core_config` 
    SET config_value = '0.4.0' 
    WHERE config_name = 'core_version';
    
ALTER TABLE 
    `core_category` 
    ADD `category_post_perpage` 
    INT(2) DEFAULT '6' NOT NULL;
    
ALTER TABLE `core_links` 
    ADD `link_order` MEDIUMINT(7) 
    DEFAULT '1' NOT NULL AFTER `id`;
    
UPDATE `core_links` 
    SET link_order = id *10;