-- $Id$

DROP TABLE `core_counter`;

DROP TABLE `core_session`;

ALTER TABLE `core_users` 
    ADD `permission_level` 
    CHAR(2) 
    NOT NULL AFTER `email`;

ALTER TABLE `core_users` 
    ADD `name` CHAR(32),
    ADD `surname` CHAR(64),
    ADD `city` CHAR(100),
    ADD `country` CHAR(100),
    ADD `www` VARCHAR(255),
    ADD `gg` CHAR(10),
    ADD `tlen` CHAR(32),
    ADD `jid` CHAR(100),
    ADD `hobby` MEDIUMTEXT,
    ADD `additional_info` MEDIUMTEXT;