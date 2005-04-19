ALTER TABLE `core_category` 
    ADD `category_parent_id` MEDIUMINT( 7 ) DEFAULT '0' NOT NULL 
    AFTER `category_id` ;