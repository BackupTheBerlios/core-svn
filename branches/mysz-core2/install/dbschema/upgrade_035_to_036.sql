-- $Id$

ALTER TABLE `core_category` 
    ADD `category_parent_id` MEDIUMINT( 7 ) DEFAULT '0' NOT NULL 
    AFTER `category_id`;
    
ALTER TABLE `core_devlog` 
    CHANGE `published` `published` 
    CHAR(2) NOT NULL;
    
UPDATE `core_devlog` 
    SET published = '1' 
    WHERE published = 'Y';
    
UPDATE `core_devlog` 
    SET published = '0' 
    WHERE published = 'N';
    
ALTER TABLE `core_devlog` 
    CHANGE `published` `published` 
    SMALLINT(1) DEFAULT '1' NOT NULL;
    
UPDATE `core_devlog` 
    SET published = -1 
    WHERE published = 0;