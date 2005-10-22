-- phpMyAdmin SQL Dump
-- version 2.6.3-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Oct 18, 2005 at 01:05 PM
-- Server version: 4.1.13
-- PHP Version: 5.1.0b3
-- 
-- Database: `core`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `core_assign2cat`
-- 

DROP TABLE IF EXISTS `core_assign2cat`;
CREATE TABLE `core_assign2cat` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `news_id` bigint(20) unsigned NOT NULL default '0',
  `category_id` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `news_id` (`news_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=218 ;

-- 
-- Dumping data for table `core_assign2cat`
-- 

INSERT INTO `core_assign2cat` VALUES (216, 26, 1),
(215, 28, 1),
(214, 30, 1),
(213, 32, 1),
(217, 24, 1),
(189, 34, 6),
(188, 34, 1),
(159, 0, 6),
(158, 0, 5),
(157, 0, 4),
(156, 0, 1),
(160, 35, 1),
(161, 35, 7);

-- --------------------------------------------------------

-- 
-- Table structure for table `core_category`
-- 

DROP TABLE IF EXISTS `core_category`;
CREATE TABLE `core_category` (
  `category_id` bigint(20) unsigned NOT NULL auto_increment,
  `category_parent_id` bigint(20) unsigned NOT NULL default '0',
  `category_order` bigint(20) unsigned NOT NULL default '1',
  `category_name` varchar(40) NOT NULL default '',
  `category_description` text NOT NULL,
  `category_tpl` varchar(255) NOT NULL default 'default',
  `category_post_perpage` int(2) NOT NULL default '6',
  KEY `id` (`category_id`),
  KEY `category_parent_id` (`category_parent_id`),
  KEY `category_name` (`category_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `core_category`
-- 

INSERT INTO `core_category` VALUES (1, 0, 10, 'ogólna', '', 'default', 6),
(2, 0, 20, 'k1', '', 'default', 6),
(3, 0, 30, 'k2', '', 'default', 6),
(4, 2, 40, 'k1 p1', '', 'default', 6),
(5, 2, 50, 'k1 p2', '', 'default', 6),
(6, 3, 60, 'k2 p1', '', 'default', 6),
(7, 4, 70, 'k1 p1 s1', '', 'default', 6);

-- --------------------------------------------------------

-- 
-- Table structure for table `core_comments`
-- 

DROP TABLE IF EXISTS `core_comments`;
CREATE TABLE `core_comments` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `id_news` bigint(20) unsigned NOT NULL default '0',
  `author` varchar(255) NOT NULL default '',
  `author_ip` varchar(15) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `text` longtext NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `core_comments`
-- 

INSERT INTO `core_comments` VALUES (1, '2005-10-18 10:47:29', 2, 'qwe', '127.0.0.1', 'marcin@urzenia.net', 'vxczz');

-- --------------------------------------------------------

-- 
-- Table structure for table `core_config`
-- 

DROP TABLE IF EXISTS `core_config`;
CREATE TABLE `core_config` (
  `config_name` varchar(255) NOT NULL default '',
  `config_value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`config_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `core_config`
-- 

INSERT INTO `core_config` VALUES ('counter', '14'),
('mainposts_per_page', '4'),
('editposts_per_page', '15'),
('mostcomments_on_page', '20'),
('title_page', './Core {lektura wcale nie obowiązkowa}'),
('max_photo_width', '440'),
('mod_rewrite', '0'),
('date_format', 'Y-m-d H:i:s'),
('core_version', '0.5.0'),
('start_page_type', 'all'),
('start_page_id', '0'),
('show_calendar', '1'),
('core_rss', '0'),
('language_set', 'pl');

-- --------------------------------------------------------

-- 
-- Table structure for table `core_devlog`
-- 

DROP TABLE IF EXISTS `core_devlog`;
CREATE TABLE `core_devlog` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `title` varchar(255) NOT NULL default '',
  `author` varchar(255) NOT NULL default '',
  `text` longtext NOT NULL,
  `comments_allow` smallint(1) NOT NULL default '1',
  `published` smallint(1) NOT NULL default '1',
  `only_in_category` smallint(1) NOT NULL default '-1',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- 
-- Dumping data for table `core_devlog`
-- 

INSERT INTO `core_devlog` VALUES (28, '2005-10-18 13:56:49', 'qqq', 'mysz', '', 1, -1, 1),
(26, '2005-10-18 12:36:36', 'vcx', 'mysz', '', 1, -1, 1),
(24, '2005-10-18 12:36:06', 'hgfdh', 'mysz', 'gfdhgfd', 1, -1, 1),
(30, '2005-10-18 13:59:57', 'aaaaaaaa', 'mysz', '', 1, -1, 1),
(32, '2005-10-18 14:02:08', 'zzzzzzz', 'mysz', '', 1, -1, 1),
(34, '2005-10-18 14:42:34', 'wpis testowy', 'myszOR', 'tralala4', 1, 1, -1),
(35, '2005-10-18 14:33:51', 'tescik', 'mysz', 'dsgvfdsg fdsg sfd', 1, 1, -1);

-- --------------------------------------------------------

-- 
-- Table structure for table `core_links`
-- 

DROP TABLE IF EXISTS `core_links`;
CREATE TABLE `core_links` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `link_order` bigint(20) unsigned NOT NULL default '1',
  `title` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `core_links`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `core_newsletter`
-- 

DROP TABLE IF EXISTS `core_newsletter`;
CREATE TABLE `core_newsletter` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL default '',
  `active` smallint(1) NOT NULL default '1',
  `token` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `core_newsletter`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `core_pages`
-- 

DROP TABLE IF EXISTS `core_pages`;
CREATE TABLE `core_pages` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `parent_id` bigint(20) unsigned NOT NULL default '0',
  `page_order` bigint(20) unsigned NOT NULL default '1',
  `title` varchar(255) NOT NULL default '',
  `text` longtext NOT NULL,
  `published` enum('Y','N') NOT NULL default 'Y',
  `assigned_tpl` varchar(255) NOT NULL default 'main',
  `node_separately` char(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- 
-- Dumping data for table `core_pages`
-- 

INSERT INTO `core_pages` VALUES (1, 0, 10, 'qwe', '', 'Y', 'main', '0'),
(2, 1, 20, 'rty', '', 'Y', 'main', '0'),
(3, 1, 30, 'qwea', 'sfdszg fdgds fgfdsg sfd', 'Y', 'main', '0'),
(4, 0, 40, 'asd', 'vxcbvcbx ', 'Y', 'main', '0'),
(5, 4, 50, 'aaa', 'aaa', 'Y', 'main', '0'),
(6, 4, 60, 'sss', '', 'Y', 'main', '0');

-- --------------------------------------------------------

-- 
-- Table structure for table `core_users`
-- 

DROP TABLE IF EXISTS `core_users`;
CREATE TABLE `core_users` (
  `id` int(5) NOT NULL auto_increment,
  `login` varchar(32) default '0',
  `password` varchar(32) default '0',
  `email` varchar(255) NOT NULL default '',
  `permission_level` varchar(2) NOT NULL default '',
  `active` enum('Y','N') NOT NULL default 'N',
  `name` varchar(32) default NULL,
  `surname` varchar(64) default NULL,
  `city` varchar(100) default NULL,
  `country` varchar(100) default NULL,
  `www` varchar(255) default NULL,
  `gg` varchar(10) default NULL,
  `tlen` varchar(32) default NULL,
  `jid` varchar(100) default NULL,
  `hobby` mediumtext,
  `additional_info` mediumtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `core_users`
-- 

INSERT INTO `core_users` VALUES (1, 'mysz', 'a8f5f167f44f4964e6c998dee827110c', 'mysz@localhost', '31', 'Y', '', '', '', '', '', '', '', '', '', '');
