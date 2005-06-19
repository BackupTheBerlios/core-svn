INSERT INTO core_config VALUES ('show_calendar', 1);
INSERT INTO core_config VALUES ('language_set', 'pl');

UPDATE core_config SET config_value = '0.4.3' WHERE config_name = 'core_version';