ALTER TABLE 
    core_newsletter ADD id MEDIUMINT(7) 
    NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
    
ALTER TABLE core_newsletter 
    ADD active SMALLINT(1) 
    DEFAULT '1' NOT NULL;
    
ALTER TABLE core_newsletter 
    ADD token VARCHAR(32) NOT NULL;