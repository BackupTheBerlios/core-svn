-- phpMyAdmin SQL Dump
-- version 2.6.0-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Czas wygenerowania: 02 Mar 2005, 02:57
-- Wersja serwera: 4.1.8
-- Wersja PHP: 5.0.3
-- 
-- Baza danych: `devlog`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `devlog`
-- 

CREATE TABLE devlog (
  id mediumint(7) NOT NULL auto_increment,
  c_id int(7) NOT NULL default '1',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  title varchar(50) NOT NULL default '',
  author varchar(30) NOT NULL default '',
  `text` mediumtext NOT NULL,
  image varchar(255) NOT NULL default '',
  comments_allow char(1) NOT NULL default '1',
  published enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (id,c_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `devlog_category`
-- 

CREATE TABLE devlog_category (
  category_id mediumint(7) NOT NULL auto_increment,
  category_name varchar(40) NOT NULL default '',
  category_description text NOT NULL,
  PRIMARY KEY  (category_id),
  KEY id (category_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `devlog_comments`
-- 

CREATE TABLE devlog_comments (
  id mediumint(7) NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  comments_id varchar(40) NOT NULL default '',
  author varchar(30) NOT NULL default '',
  author_ip varchar(15) NOT NULL default '',
  email varchar(30) NOT NULL default '',
  `text` mediumtext NOT NULL,
  PRIMARY KEY  (id,comments_id),
  KEY id (comments_id,id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `devlog_config`
-- 

CREATE TABLE devlog_config (
  config_name varchar(255) NOT NULL default '',
  config_value varchar(255) NOT NULL default '',
  PRIMARY KEY  (config_name)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `devlog_counter`
-- 

CREATE TABLE devlog_counter (
  id int(10) unsigned NOT NULL auto_increment,
  hit text,
  hitnumber int(11) default NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `devlog_newsletter`
-- 

CREATE TABLE devlog_newsletter (
  email varchar(40) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `devlog_pages`
-- 

CREATE TABLE devlog_pages (
  id mediumint(7) NOT NULL auto_increment,
  parent_id mediumint(7) NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  `text` mediumtext NOT NULL,
  image varchar(255) NOT NULL default '',
  published enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `devlog_session`
-- 

CREATE TABLE devlog_session (
  session_id varchar(32) NOT NULL default '',
  session_ip_address varchar(32) NOT NULL default '',
  session_log_in_time int(10) unsigned NOT NULL default '0',
  session_running_time int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (session_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `devlog_users`
-- 

CREATE TABLE devlog_users (
  id int(5) NOT NULL auto_increment,
  login varchar(15) default '0',
  `password` varchar(32) default '0',
  email varchar(30) NOT NULL default '',
  active enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla  'devlog_links_category'
-- 

CREATE TABLE `devlog_links_category` (
  `id` mediumint(7) NOT NULL auto_increment,
  `parent_id` mediumint(7) NOT NULL default '0',
  `title` varchar(40) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;