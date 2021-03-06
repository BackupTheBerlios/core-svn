-- $Id: core-mysql41_install.sql 1223 2005-11-07 19:25:25Z lark $

CREATE TABLE core_assign2cat (
  id bigint(20) unsigned NOT NULL auto_increment,
  news_id bigint(20) unsigned NOT NULL default '0',
  category_id bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY news_id (news_id),
  KEY category_id (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE core_category (
  category_id bigint(20) unsigned NOT NULL auto_increment,
  category_parent_id bigint(20) unsigned NOT NULL default '0',
  category_order bigint(20) unsigned NOT NULL default '1',
  category_name varchar(40) NOT NULL default '',
  category_description text NOT NULL,
  category_tpl varchar(255) NOT NULL default 'default',
  category_post_perpage int(2) NOT NULL default '6',
  KEY id (category_id),
  KEY category_parent_id (category_parent_id),
  KEY category_name (category_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE core_comments (
  id bigint(20) unsigned NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  id_news bigint(20) unsigned NOT NULL default '0',
  author varchar(255) NOT NULL default '',
  author_ip varchar(15) NOT NULL default '',
  email varchar(255) NOT NULL default '',
  `text` longtext NOT NULL,
  KEY id (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE core_config (
  config_name varchar(255) NOT NULL default '',
  config_value varchar(255) NOT NULL default '',
  PRIMARY KEY  (config_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE core_devlog (
  id bigint(20) unsigned NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  title varchar(255) NOT NULL default '',
  author varchar(255) NOT NULL default '',
  `text` longtext NOT NULL,
  comments_allow smallint(1) NOT NULL default '1',
  published smallint(1) NOT NULL default '1',
  only_in_category smallint(1) NOT NULL default '-1',
  KEY id (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE core_links (
  id bigint(20) unsigned NOT NULL auto_increment,
  link_order bigint(20) unsigned NOT NULL default '1',
  title varchar(255) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY id (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE core_newsletter (
  id bigint(20) unsigned NOT NULL auto_increment,
  email varchar(255) NOT NULL default '',
  active smallint(1) NOT NULL default '1',
  token varchar(32) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE core_pages (
  id bigint(20) unsigned NOT NULL auto_increment,
  parent_id bigint(20) unsigned NOT NULL default '0',
  page_order bigint(20) unsigned NOT NULL default '1',
  title varchar(255) NOT NULL default '',
  `text` longtext NOT NULL,
  published enum('Y','N') NOT NULL default 'Y',
  assigned_tpl varchar(255) NOT NULL default 'main',
  node_separately char(1) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE core_users (
  id int(5) NOT NULL auto_increment,
  login varchar(32) default '0',
  `password` varchar(32) default '0',
  email varchar(255) NOT NULL default '',
  permission_level varchar(2) NOT NULL default '',
  active enum('Y','N') NOT NULL default 'N',
  `name` varchar(32) default NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        


INSERT INTO core_category VALUES ('', '', '10', 'DEFAULT_CATEGORY', '', 'default', '6');

INSERT INTO core_config VALUES ('counter', '0');
INSERT INTO core_config VALUES ('mainposts_per_page', '4');
INSERT INTO core_config VALUES ('editposts_per_page', '15');
INSERT INTO core_config VALUES ('mostcomments_on_page', '20');
INSERT INTO core_config VALUES ('title_page', 'Core CMS');
INSERT INTO core_config VALUES ('max_photo_width', '440');
INSERT INTO core_config VALUES ('mod_rewrite', '0');
INSERT INTO core_config VALUES ('date_format', 'Y-m-d H:i:s');
INSERT INTO core_config VALUES ('core_version', '0.5.0');
INSERT INTO core_config VALUES ('start_page_type', 'all'), ('start_page_id', '0');
INSERT INTO core_config VALUES ('show_calendar', 1);
INSERT INTO core_config VALUES ('core_rss', 1);
