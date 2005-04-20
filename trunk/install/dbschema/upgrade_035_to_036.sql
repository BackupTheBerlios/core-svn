ALTER TABLE `core_category` 
    ADD `category_parent_id` MEDIUMINT( 7 ) DEFAULT '0' NOT NULL 
    AFTER `category_id`;
    
ALTER TABLE `core_devlog` 
    CHANGE `published` `published` 
    ENUM( '1', '-1' ) DEFAULT '1' NOT NULL;
    
UPDATE `core_devlog` 
    SET published = '1';