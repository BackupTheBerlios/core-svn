ALTER TABLE 
    core_newsletter ADD id MEDIUMINT(7) 
    NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
    
ALTER TABLE core_newsletter 
    ADD active SMALLINT(1) 
    DEFAULT '1' NOT NULL;
    
ALTER TABLE core_newsletter 
    ADD token VARCHAR(32) NOT NULL;

UPDATE core_config
    SET 'config_value' = '0.4.1'
    WHERE 'config_name' = 'core_version';

INSERT INTO core_config VALUES
    ('start_page_type', 'page'),
    ('start_page_id', '0');
