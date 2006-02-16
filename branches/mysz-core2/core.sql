-- phpMyAdmin SQL Dump
-- version 2.7.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Czas wygenerowania: 16 Lut 2006, 11:24
-- Wersja serwera: 5.0.18
-- Wersja PHP: 5.1.2
-- 
-- Baza danych: `core_new`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_config`
-- 

DROP TABLE IF EXISTS core_config;
CREATE TABLE core_config (
  `key` varchar(255) NOT NULL default '',
  `value` longtext NOT NULL,
  PRIMARY KEY  (`key`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_menusection`
-- 

DROP TABLE IF EXISTS core_menusection;
CREATE TABLE core_menusection (
  id_menu bigint(20) unsigned NOT NULL,
  title varchar(255) NOT NULL default '',
  listed_as_tree char(1) NOT NULL default '0',
  PRIMARY KEY  (id_menu)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_p2c`
-- 

DROP TABLE IF EXISTS core_p2c;
CREATE TABLE core_p2c (
  id_p2c bigint(20) unsigned NOT NULL,
  id_cat bigint(20) unsigned NOT NULL,
  id_post bigint(20) unsigned NOT NULL,
  PRIMARY KEY  (id_p2c)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_postcats`
-- 

DROP TABLE IF EXISTS core_postcats;
CREATE TABLE core_postcats (
  id_cat bigint(20) unsigned NOT NULL,
  id_parent bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  permalink varchar(255) NOT NULL,
  description text NOT NULL,
  tpl_name varchar(255) NOT NULL,
  enabled tinyint(1) NOT NULL default '1'
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_postgroups`
-- 

DROP TABLE IF EXISTS core_postgroups;
CREATE TABLE core_postgroups (
  id_group bigint(20) unsigned NOT NULL,
  grp_name varchar(255) NOT NULL,
  tpl_name varchar(255) NOT NULL,
  enabled tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (id_group)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_postmeta`
-- 

DROP TABLE IF EXISTS core_postmeta;
CREATE TABLE core_postmeta (
  id_meta bigint(20) unsigned NOT NULL,
  id_post bigint(20) unsigned NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY  (id_meta)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_posts`
-- 

DROP TABLE IF EXISTS core_posts;
CREATE TABLE core_posts (
  id_post bigint(20) unsigned NOT NULL,
  id_parent bigint(20) unsigned NOT NULL,
  id_type bigint(20) unsigned NOT NULL,
  id_section bigint(20) unsigned NOT NULL,
  title varchar(255) NOT NULL,
  permalink varchar(255) NOT NULL,
  caption text,
  body longtext,
  tpl_name varchar(255) default NULL,
  author_name varchar(255) default NULL,
  author_mail varchar(255) default NULL,
  author_www varchar(255) default NULL,
  date_add datetime NOT NULL,
  date_mod datetime NOT NULL,
  `status` enum('published','draft','disabled') NOT NULL default 'published',
  PRIMARY KEY  (id_post)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_users`
-- 

DROP TABLE IF EXISTS core_users;
CREATE TABLE core_users (
  id_user bigint(20) unsigned NOT NULL,
  login varchar(64) NOT NULL,
  passwd varchar(40) NOT NULL,
  `level` int(11) NOT NULL default '1',
  date_add datetime NOT NULL,
  enabled tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (id_user)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Struktura tabeli dla  `core_usersmeta`
-- 

DROP TABLE IF EXISTS core_usersmeta;
CREATE TABLE core_usersmeta (
  id_meta bigint(20) unsigned NOT NULL,
  id_user bigint(20) unsigned NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY  (id_meta)
) TYPE=MyISAM;

