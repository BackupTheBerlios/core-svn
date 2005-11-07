
CREATE SEQUENCE core_assign2cat_id_seq start 1 increment 1 cache 1;
CREATE SEQUENCE core_category_category_id_seq start 1 increment 1 cache 1;
CREATE SEQUENCE core_comments_id_seq start 1 increment 1 cache 1;
CREATE SEQUENCE core_devlog_id_seq start 1 increment 1 cache 1;
CREATE SEQUENCE core_links_id_seq start 1 increment 1 cache 1;
CREATE SEQUENCE core_newsletter_id_seq start 1 increment 1 cache 1;
CREATE SEQUENCE core_pages_id_seq start 1 increment 1 cache 1;
CREATE SEQUENCE core_users_id_seq start 1 increment 1 cache 1;


CREATE TABLE core_assign2cat (
    id int DEFAULT '0' NOT NULL,
    news_id int2 DEFAULT '0' NOT NULL,
    category_id int2 DEFAULT '0' NOT NULL,
    CONSTRAINT core_assign2cat_pkey PRIMARY KEY(id, news_id, category_id)
);

CREATE INDEX news_id_core_assign2cat_index ON core_assign2cat (news_id);
CREATE INDEX category_id_core_assign2cat_index ON core_assign2cat (category_id);


CREATE TABLE core_category (
  category_id int DEFAULT '0' NOT NULL,
  category_parent_id int2 DEFAULT '0' NOT NULL,
  category_order int2 DEFAULT '1' NOT NULL,
  category_name varchar(40) DEFAULT '' NOT NULL,
  category_description text NOT NULL, 
  category_tpl varchar(255) DEFAULT 'default' NOT NULL, 
  category_post_perpage int2 DEFAULT '6' NOT NULL, 
  CONSTRAINT core_category_pkey PRIMARY KEY(category_id)
);

CREATE INDEX category_id_core_category_index ON core_category (category_id);
CREATE INDEX category_parent_id_core_category_index ON core_category (category_parent_id);


CREATE TABLE core_comments (
  id int DEFAULT '0' NOT NULL,
  date timestamp,
  id_news varchar(40) DEFAULT '' NOT NULL,
  author varchar(30) DEFAULT '' NOT NULL,
  author_ip varchar(15) DEFAULT '' NOT NULL,
  email varchar(30) DEFAULT '' NOT NULL,
  text text NOT NULL, 
  CONSTRAINT core_comments_pkey PRIMARY KEY(id, comments_id)
);

CREATE INDEX id_news_core_comments_index ON core_comments (id_news);


CREATE TABLE core_config (
  config_name varchar(255) DEFAULT '' NOT NULL,
  config_value varchar(255) DEFAULT '' NOT NULL,
  CONSTRAINT core_config_pkey PRIMARY KEY(config_name)
);


CREATE TABLE core_devlog (
  id int DEFAULT '0' NOT NULL,
  date timestamp,
  title varchar(50) DEFAULT '' NOT NULL,
  author varchar(30) DEFAULT '' NOT NULL,
  text text DEFAULT '' NOT NULL,
  image varchar(255) DEFAULT '' NOT NULL,
  comments_allow char(1) DEFAULT '1' NOT NULL,
  published smallint(1) DEFAULT '1' NOT NULL,
  only_in_category smallint(1) DEFAULT '-1' NOT NULL,
  CONSTRAINT core_devlog_pkey PRIMARY KEY(id)
);

CREATE INDEX id_core_devlog_index ON core_devlog (id);


CREATE TABLE core_links (
  id int DEFAULT '0' NOT NULL, 
  link_order int DEFAULT '1' NOT NULL,
  title varchar(40) DEFAULT '' NOT NULL,
  url varchar(255) DEFAULT '' NOT NULL,
  CONSTRAINT core_links_pkey PRIMARY KEY(id)
);

CREATE INDEX id_core_links_index ON core_links (id);


CREATE TABLE core_newsletter (
  id int DEFAULT '0' NOT NULL,
  email varchar(40) DEFAULT '' NOT NULL, 
  active smallint(1) DEFAULT '1' NOT NULL, 
  token varchar(32) DEFAULT '' NOT NULL, 
  CONSTRAINT core_newsletter_pkey PRIMARY KEY(id)
);

CREATE INDEX id_core_newsletter_index ON core_newsletter (id);


CREATE TABLE core_pages (
  id int DEFAULT '0' NOT NULL,
  parent_id int DEFAULT '0' NOT NULL,
  page_order int DEFAULT '1' NOT NULL, 
  title varchar(50) DEFAULT '' NOT NULL,
  text text DEFAULT '' NOT NULL,
  image varchar(255) DEFAULT '' NOT NULL,
  published char(1) DEFAULT 'Y' NOT NULL, 
  assigned_tpl VARCHAR(255) DEFAULT 'main' NOT NULL,
  node_separately char(1) DEFAULT '0' NOT NULL,
  CONSTRAINT core_pages_pkey PRIMARY KEY (id, parent_id)
);

CREATE INDEX id_core_pages_index ON core_pages (id);
CREATE INDEX parent_id_core_pages_index ON core_pages (parent_id);


CREATE TABLE core_users (
  id int DEFAULT '0' NOT NULL,
  login varchar(15) DEFAULT '' NOT NULL,
  password varchar(32) DEFAULT '' NOT NULL,
  email varchar(30) DEFAULT '' NOT NULL,
  permission_level varchar(2) DEFAULT '' NOT NULL,
  active char(1) DEFAULT 'N' NOT NULL,
  name varchar(32) DEFAULT '' NOT NULL,
  surname varchar(64) DEFAULT '' NOT NULL,
  city varchar(100) DEFAULT '' NOT NULL,
  country varchar(100) DEFAULT '' NOT NULL,
  www varchar(255) DEFAULT '' NOT NULL,
  gg varchar(10) DEFAULT '' NOT NULL,
  tlen varchar(32) DEFAULT '' NOT NULL,
  jid varchar(100) DEFAULT '' NOT NULL,
  hobby text DEFAULT '' NOT NULL,
  additional_info text DEFAULT '' NOT NULL,
  CONSTRAINT core_users_pkey PRIMARY KEY(id)
);

CREATE INDEX id_core_users_index ON core_users (id);


INSERT INTO core_category VALUES ('', '', '10', 'DEFAULT_CATEGORY', '', 'default', '6');

INSERT INTO core_config VALUES ('counter', '0');
INSERT INTO core_config VALUES ('mainposts_per_page', '4');
INSERT INTO core_config VALUES ('editposts_per_page', '15');
INSERT INTO core_config VALUES ('mostcomments_on_page', '20');
INSERT INTO core_config VALUES ('title_page', './Core {lektura wcale nie obowiązkowa}');
INSERT INTO core_config VALUES ('max_photo_width', '440');
INSERT INTO core_config VALUES ('mod_rewrite', '0');
INSERT INTO core_config VALUES ('date_format', 'Y-m-d H:i:s');
INSERT INTO core_config VALUES ('core_version', '0.4.4');
INSERT INTO core_config VALUES ('start_page_type', 'all'), ('start_page_id', '0');
INSERT INTO core_config VALUES ('show_calendar', 1);
INSERT INTO core_config VALUES ('core_rss', 1);