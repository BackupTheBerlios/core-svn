<?php

// Core CMS auto-generated config file

define('DB_HOST',               'localhost');
define('DB_USER',               'root');
define('DB_PASS',               'trustno1');
define('DB_NAME',               'corecms');
define('PREFIX',                'core_');

define('TABLE_ASSIGN2CAT',      PREFIX . 'assign2cat');
define('TABLE_MAIN',            PREFIX . 'devlog');
define('TABLE_USERS',           PREFIX . 'users');
define('TABLE_COMMENTS',        PREFIX . 'comments');
define('TABLE_CONFIG',          PREFIX . 'config');
define('TABLE_CATEGORY',        PREFIX . 'category');
define('TABLE_PAGES',           PREFIX . 'pages');
define('TABLE_LINKS',           PREFIX . 'links');
define('TABLE_NEWSLETTER',      PREFIX . 'newsletter');

define('CORE_INSTALLED',        true);

//mail address to person who can repair if something in Your code is broken
define('ADMIN_MAIL',            'core@example.com');

define('PATH_TO_CLASSES',       sprintf('%s/classes/', dirname(dirname(__file__))));
define('ROOT',                  dirname(dirname(PATH_TO_CLASSES)) . '/'  );
define('PATH_TO_MODULES_ADM',   ROOT . 'administration/modules/');
define('PATH_TO_MODULES_USER',  ROOT . 'modules/');
define('CLASS_EXTENSION',       '.php');

define('TMPDIR',                ROOT . 'administration/_tmp/');

define('BASE_HREF', 'http://81.190.160.132/corecms/050/');

?>