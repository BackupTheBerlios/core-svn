INSERT INTO `core_config` 
    VALUES ('date_format', 'Y-m-d H:i:s');

ALTER TABLE `core_pages` 
    ADD `assigned_tpl` VARCHAR(255) 
    DEFAULT 'main' NOT NULL;
    
ALTER TABLE `core_category` 
    ADD `category_tpl` VARCHAR(255) 
    DEFAULT 'default' NOT NULL;
    
UPDATE `core_config` 
    SET config_value = '0.3.8' 
    WHERE config_name = 'core_version';