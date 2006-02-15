-- $Id$

ALTER TABLE 
    core_newsletter ADD id MEDIUMINT(7) 
    NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
    
ALTER TABLE core_newsletter 
    ADD active SMALLINT(1) 
    DEFAULT '1' NOT NULL;
    
ALTER TABLE core_newsletter 
    ADD token VARCHAR(32) NOT NULL;

UPDATE core_config
    SET 'config_value' = '0.4.2'
    WHERE 'config_name' = 'core_version';

INSERT INTO core_config VALUES
    ('start_page_type', 'all'),
    ('start_page_id', '0');

ALTER TABLE core_devlog ADD only_in_category SMALLINT(1) 
    DEFAULT '-1' NOT NULL;

CREATE TABLE core_assign2cat (
    id int(7) NOT NULL auto_increment,
    news_id int(7) NOT NULL default '0',
    category_id int(7) NOT NULL default '0',
    PRIMARY KEY (id),
    KEY news_id (news_id, category_id)
);