ALTER TABLE `core_category` 
    ADD `category_order` MEDIUMINT(7) 
    DEFAULT '1' NOT NULL AFTER `category_parent_id`;
    
UPDATE `core_category` 
    SET category_order = category_id *10;
    
ALTER TABLE `core_pages` 
    ADD `page_order` MEDIUMINT(7) 
    DEFAULT '1' NOT NULL AFTER `parent_id`;
    
UPDATE `core_pages` 
    SET page_order = id *10;
    
INSERT INTO `core_config` 
    VALUES ('mod_rewrite', '0');

INSERT INTO `core_config` 
    VALUES ('core_version', '0.3.7');