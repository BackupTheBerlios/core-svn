CREATE TABLE core_category (
  category_id mediumint(7) NOT NULL auto_increment,
  category_parent_id mediumint(7) NOT NULL default '0',
  category_name varchar(40) NOT NULL default '',
  category_description text NOT NULL,
  KEY id (category_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE core_comments (
  id mediumint(7) NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  comments_id varchar(40) NOT NULL default '',
  author varchar(30) NOT NULL default '',
  author_ip varchar(15) NOT NULL default '',
  email varchar(30) NOT NULL default '',
  `text` mediumtext NOT NULL,
  KEY id (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE core_config (
  config_name varchar(255) NOT NULL default '',
  config_value varchar(255) NOT NULL default '',
  PRIMARY KEY  (config_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE core_devlog (
  id mediumint(7) NOT NULL auto_increment,
  c_id int(7) NOT NULL default '1',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  title varchar(50) NOT NULL default '',
  author varchar(30) NOT NULL default '',
  `text` mediumtext NOT NULL,
  image varchar(255) NOT NULL default '',
  comments_allow char(1) NOT NULL default '1',
  published enum('Y','N') NOT NULL default 'Y',
  KEY id (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE core_links (
  id mediumint(7) NOT NULL auto_increment,
  title varchar(40) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY id (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE core_newsletter (
  email varchar(40) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE core_pages (
  id mediumint(7) NOT NULL auto_increment,
  parent_id mediumint(7) NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  `text` mediumtext NOT NULL,
  image varchar(255) NOT NULL default '',
  published enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE core_users (
  id int(5) NOT NULL auto_increment,
  login varchar(15) default '0',
  `password` varchar(32) default '0',
  email varchar(30) NOT NULL default '',
  permission_level varchar(2) NOT NULL default '',
  active enum('Y','N') NOT NULL default 'N',
  name varchar(32) default NULL,
  surname varchar(64) default NULL,
  city varchar(100) default NULL,
  country varchar(100) default NULL,
  www varchar(255) default NULL,
  gg varchar(10) default NULL,
  tlen varchar(32) default NULL,
  jid varchar(100) default NULL,
  hobby mediumtext,
  additional_info mediumtext,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;