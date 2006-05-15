-- phpMyAdmin SQL Dump
-- version 2.6.3-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Czas wygenerowania: 03 Kwi 2006, 14:29
-- Wersja serwera: 5.0.15
-- Wersja PHP: 5.1.2
-- 
-- Baza danych: `core2`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_config`
-- 

DROP TABLE IF EXISTS `core_config`;
CREATE TABLE `core_config` (
  `key` varchar(255) NOT NULL default '',
  `value` longtext NOT NULL,
  PRIMARY KEY  (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Zrzut danych tabeli `core_config`
-- 

INSERT INTO `core_config` VALUES ('email', 's:16:"core@urzenia.net";');
INSERT INTO `core_config` VALUES ('enc_from', 's:10:"iso-8859-2";');
INSERT INTO `core_config` VALUES ('enc_to', 's:5:"utf-8";');
INSERT INTO `core_config` VALUES ('comp_level', 'i:5;');

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_menusection`
-- 

DROP TABLE IF EXISTS `core_menusection`;
CREATE TABLE `core_menusection` (
  `id_menu` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `listed_as_tree` char(1) NOT NULL default '0',
  PRIMARY KEY  (`id_menu`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `core_menusection`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_meta`
-- 

DROP TABLE IF EXISTS `core_meta`;
CREATE TABLE `core_meta` (
  `id_meta` bigint(20) unsigned NOT NULL auto_increment,
  `id_entry` bigint(20) unsigned NOT NULL,
  `type` varchar(30) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` longtext,
  PRIMARY KEY  (`id_meta`),
  KEY `type` (`type`),
  KEY `key` (`key`),
  KEY `id_entry` (`id_entry`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- 
-- Zrzut danych tabeli `core_meta`
-- 

INSERT INTO `core_meta` VALUES (1, 1, 'Post', 'sticky', '0');
INSERT INTO `core_meta` VALUES (2, 1, 'Post', 'allow_comments', '0');
INSERT INTO `core_meta` VALUES (3, 1, 'Post', 'only_in_category', '0');
INSERT INTO `core_meta` VALUES (4, 1, 'User', 'mail', 'marcin@urzenia.net');
INSERT INTO `core_meta` VALUES (5, 1, 'User', 'jid', 'urzenia@gmail.com');
INSERT INTO `core_meta` VALUES (6, 1, 'User', 'www', 'http://diary.urzenia.net/');
INSERT INTO `core_meta` VALUES (7, 1, 'User', 'phone', '0');

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_p2c`
-- 

DROP TABLE IF EXISTS `core_p2c`;
CREATE TABLE `core_p2c` (
  `id_p2c` bigint(20) unsigned NOT NULL auto_increment,
  `id_cat` bigint(20) unsigned NOT NULL,
  `id_post` bigint(20) unsigned NOT NULL,
  PRIMARY KEY  (`id_p2c`),
  KEY `id_cat` (`id_cat`),
  KEY `id_post` (`id_post`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `core_p2c`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_postcats`
-- 

DROP TABLE IF EXISTS `core_postcats`;
CREATE TABLE `core_postcats` (
  `id_cat` bigint(20) unsigned NOT NULL auto_increment,
  `id_parent` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `permalink` varchar(255) NOT NULL,
  `description` text,
  `tpl_name` varchar(255) default NULL,
  `enabled` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_cat`),
  KEY `id_parent` (`id_parent`),
  KEY `permalink` (`permalink`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Zrzut danych tabeli `core_postcats`
-- 

INSERT INTO `core_postcats` VALUES (1, 0, 'kat1', 'kat1', 'desc', '', -1);
INSERT INTO `core_postcats` VALUES (2, 0, 'kat2', 'kat2', '', '', 1);

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_postgroups`
-- 

DROP TABLE IF EXISTS `core_postgroups`;
CREATE TABLE `core_postgroups` (
  `id_group` bigint(20) unsigned NOT NULL auto_increment,
  `grp_name` varchar(255) NOT NULL,
  `tpl_name` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_group`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Zrzut danych tabeli `core_postgroups`
-- 

INSERT INTO `core_postgroups` VALUES (1, 'post', '', 1);
INSERT INTO `core_postgroups` VALUES (2, 'comment', '', 1);
INSERT INTO `core_postgroups` VALUES (3, 'page', '', 1);

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_posts`
-- 

DROP TABLE IF EXISTS `core_posts`;
CREATE TABLE `core_posts` (
  `id_post` bigint(20) unsigned NOT NULL auto_increment,
  `id_parent` bigint(20) unsigned NOT NULL,
  `id_cat` bigint(20) unsigned NOT NULL,
  `id_group` bigint(20) unsigned NOT NULL,
  `id_section` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `permalink` varchar(255) NOT NULL,
  `caption` text,
  `body` longtext,
  `tpl_name` varchar(255) default NULL,
  `author_name` varchar(255) default NULL,
  `author_mail` varchar(255) default NULL,
  `author_www` varchar(255) default NULL,
  `date_add` datetime NOT NULL,
  `date_mod` datetime NOT NULL,
  `status` enum('published','draft','disabled') NOT NULL default 'published',
  PRIMARY KEY  (`id_post`),
  KEY `permalink` (`permalink`),
  KEY `id_parent` (`id_parent`),
  KEY `title` (`title`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Zrzut danych tabeli `core_posts`
-- 

INSERT INTO `core_posts` VALUES (1, 0, 1, 1, 0, 'asd', 'post1', 'siakis caption post 1', 'siakis text post 1', 'specjal.tpl', 'mysz', 'marcin@urzenia.net', 'http://diary.urzenia.net/', '2006-03-13 11:51:38', '2006-03-30 13:30:38', 'published');
INSERT INTO `core_posts` VALUES (2, 0, 1, 1, 0, 'specjal title', 'post1', 'specjal caption', 'specjal body', NULL, 'stasiu', 'marcin@urzenia.net', NULL, '2006-03-31 08:03:35', '2006-03-31 12:32:40', 'published');
INSERT INTO `core_posts` VALUES (3, 0, 1, 1, 0, 'specjal title', 'post1', 'specjal caption', 'specjal body', NULL, 'stasiu', 'marcin@urzenia.net', 'http://sztolcman.eu', '2006-03-31 12:33:21', '2006-03-31 12:33:21', 'published');

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_users`
-- 

DROP TABLE IF EXISTS `core_users`;
CREATE TABLE `core_users` (
  `id_user` bigint(20) unsigned NOT NULL auto_increment,
  `login` varchar(64) NOT NULL,
  `passwd` varchar(40) NOT NULL,
  `level` int(11) NOT NULL default '1',
  `date_add` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_user`),
  KEY `login` (`login`),
  KEY `passwd` (`passwd`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Zrzut danych tabeli `core_users`
-- 

INSERT INTO `core_users` VALUES (1, 'mysz', '1c13383468d08d167f624c10d6c3da8a4163be89', 1, '2006-04-03 14:12:02', 1);
