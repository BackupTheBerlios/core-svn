-- $Id: upgrade_042_to_043.sql 1127 2005-08-03 22:00:41Z mysz $

INSERT INTO core_config VALUES ('show_calendar', 1);
INSERT INTO core_config VALUES ('language_set', 'pl');

UPDATE core_config SET config_value = '0.4.3' WHERE config_name = 'core_version';