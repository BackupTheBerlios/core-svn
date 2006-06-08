-- phpMyAdmin SQL Dump
-- version 2.6.3-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Czas wygenerowania: 08 Cze 2006, 15:01
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
  PRIMARY KEY  (`key`),
  KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Zrzut danych tabeli `core_config`
-- 

INSERT INTO `core_config` (`key`, `value`) VALUES ('email', 's:16:"core@urzenia.net";');
INSERT INTO `core_config` (`key`, `value`) VALUES ('enc_from', 's:10:"iso-8859-2";');
INSERT INTO `core_config` (`key`, `value`) VALUES ('enc_to', 's:5:"UTF-8";');
INSERT INTO `core_config` (`key`, `value`) VALUES ('comp_level', 'i:5;');

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_menu`
-- 

DROP TABLE IF EXISTS `core_menu`;
CREATE TABLE `core_menu` (
  `id_menu` bigint(20) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `listed_as_tree` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_menu`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Zrzut danych tabeli `core_menu`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_meta`
-- 

DROP TABLE IF EXISTS `core_meta`;
CREATE TABLE `core_meta` (
  `id_entry` bigint(20) unsigned NOT NULL default '0',
  `type` varchar(30) NOT NULL default '',
  `key` varchar(255) NOT NULL default '',
  `value` longtext,
  PRIMARY KEY  (`id_entry`,`type`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Zrzut danych tabeli `core_meta`
-- 

INSERT INTO `core_meta` (`id_entry`, `type`, `key`, `value`) VALUES (1, 'Post', 'test_meta', 'asd');
INSERT INTO `core_meta` (`id_entry`, `type`, `key`, `value`) VALUES (1, 'User', 'mail', 'marcin@urzenia.net');
INSERT INTO `core_meta` (`id_entry`, `type`, `key`, `value`) VALUES (1, 'User', 'jid', 'urzenia@gmail.com');
INSERT INTO `core_meta` (`id_entry`, `type`, `key`, `value`) VALUES (1, 'User', 'www', 'http://diary.urzenia.net/');
INSERT INTO `core_meta` (`id_entry`, `type`, `key`, `value`) VALUES (1, 'User', 'phone', '0');
INSERT INTO `core_meta` (`id_entry`, `type`, `key`, `value`) VALUES (1, 'Post', 'sticky', '2');
INSERT INTO `core_meta` (`id_entry`, `type`, `key`, `value`) VALUES (1, 'Post', 'allow_comments', '0');

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_p2c`
-- 

DROP TABLE IF EXISTS `core_p2c`;
CREATE TABLE `core_p2c` (
  `id_cat` bigint(20) unsigned NOT NULL default '0',
  `id_post` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id_cat`,`id_post`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `id_parent` bigint(20) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `permalink` varchar(255) NOT NULL default '',
  `description` text,
  `tpl_name` varchar(255) default NULL,
  `enabled` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_cat`),
  KEY `id_parent` (`id_parent`),
  KEY `permalink` (`permalink`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- Zrzut danych tabeli `core_postcats`
-- 

INSERT INTO `core_postcats` (`id_cat`, `id_parent`, `name`, `permalink`, `description`, `tpl_name`, `enabled`) VALUES (1, 0, 'kat1', 'kat1', 'desc', '', -1);
INSERT INTO `core_postcats` (`id_cat`, `id_parent`, `name`, `permalink`, `description`, `tpl_name`, `enabled`) VALUES (2, 0, 'kat2', 'kat2', '', '', 1);

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_postgroups`
-- 

DROP TABLE IF EXISTS `core_postgroups`;
CREATE TABLE `core_postgroups` (
  `id_group` bigint(20) unsigned NOT NULL auto_increment,
  `grp_name` varchar(255) NOT NULL default '',
  `tpl_name` varchar(255) NOT NULL default '',
  `enabled` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Zrzut danych tabeli `core_postgroups`
-- 

INSERT INTO `core_postgroups` (`id_group`, `grp_name`, `tpl_name`, `enabled`) VALUES (1, 'post', '', 1);
INSERT INTO `core_postgroups` (`id_group`, `grp_name`, `tpl_name`, `enabled`) VALUES (2, 'comment', '', 1);
INSERT INTO `core_postgroups` (`id_group`, `grp_name`, `tpl_name`, `enabled`) VALUES (3, 'page', '', 1);

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_posts`
-- 

DROP TABLE IF EXISTS `core_posts`;
CREATE TABLE `core_posts` (
  `id_post` bigint(20) unsigned NOT NULL auto_increment,
  `id_parent` bigint(20) unsigned NOT NULL default '1',
  `id_cat` bigint(20) unsigned NOT NULL default '1',
  `id_group` bigint(20) unsigned NOT NULL default '1',
  `id_menu` bigint(20) unsigned NOT NULL default '1',
  `title` varchar(255) NOT NULL default '',
  `permalink` varchar(255) NOT NULL default '',
  `caption` text,
  `body` longtext,
  `tpl_name` varchar(255) default NULL,
  `author_name` varchar(255) default NULL,
  `author_mail` varchar(255) default NULL,
  `author_www` varchar(255) default NULL,
  `date_add` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_mod` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` enum('published','draft','disabled') NOT NULL default 'published',
  PRIMARY KEY  (`id_post`),
  KEY `permalink` (`permalink`),
  KEY `id_parent` (`id_parent`),
  KEY `title` (`title`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Zrzut danych tabeli `core_posts`
-- 

INSERT INTO `core_posts` (`id_post`, `id_parent`, `id_cat`, `id_group`, `id_menu`, `title`, `permalink`, `caption`, `body`, `tpl_name`, `author_name`, `author_mail`, `author_www`, `date_add`, `date_mod`, `status`) VALUES (1, 0, 1, 1, 0, 'asd', 'qwe permalink qwerty', 'siakis caption post 1', 'siakis text post 1', 'specjal.tpl', 'mysz', 'marcin@urzenia.net', 'http://diary.urzenia.net/', '0000-00-00 00:00:00', '2006-06-08 14:57:49', 'published');
INSERT INTO `core_posts` (`id_post`, `id_parent`, `id_cat`, `id_group`, `id_menu`, `title`, `permalink`, `caption`, `body`, `tpl_name`, `author_name`, `author_mail`, `author_www`, `date_add`, `date_mod`, `status`) VALUES (2, 0, 1, 1, 0, 'specjal title', 'post1', 'specjal caption', 'specjal body', NULL, 'stasiu', 'marcin@urzenia.net', NULL, '0000-00-00 00:00:00', '2006-06-08 14:57:49', 'published');
INSERT INTO `core_posts` (`id_post`, `id_parent`, `id_cat`, `id_group`, `id_menu`, `title`, `permalink`, `caption`, `body`, `tpl_name`, `author_name`, `author_mail`, `author_www`, `date_add`, `date_mod`, `status`) VALUES (3, 0, 1, 1, 0, 'specjal title', 'post1', 'specjal caption', 'specjal body', NULL, 'stasiu', 'marcin@urzenia.net', 'http://sztolcman.eu', '0000-00-00 00:00:00', '2006-06-08 14:57:49', 'published');

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_users`
-- 

DROP TABLE IF EXISTS `core_users`;
CREATE TABLE `core_users` (
  `id_user` bigint(20) unsigned NOT NULL auto_increment,
  `login` varchar(64) NOT NULL default '',
  `passwd` varchar(40) NOT NULL default '',
  `fname` varchar(128) NOT NULL default '',
  `lname` varchar(250) NOT NULL default '',
  `perms` text NOT NULL,
  `date_add` datetime NOT NULL default '0000-00-00 00:00:00',
  `enabled` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id_user`),
  KEY `login` (`login`),
  KEY `passwd` (`passwd`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Zrzut danych tabeli `core_users`
-- 

INSERT INTO `core_users` (`id_user`, `login`, `passwd`, `fname`, `lname`, `perms`, `date_add`, `enabled`) VALUES (1, 'mysz', '1c13383468d08d167f624c10d6c3da8a4163be89', '', '', '', '2006-04-03 14:12:02', 1);
