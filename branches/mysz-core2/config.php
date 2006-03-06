<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

define('ROOT', dirname(__file__));
define('DEBUG', true);

define('DB_HOST', 'localhost');
define('DB_USER', 'mysz');
define('DB_PASS', 'ttt');
define('DB_NAME', 'core2');

define('TBL_PREFIX',        'core_');
define('TBL_CONFIG',        TBL_PREFIX . 'config');
define('TBL_MENU',          TBL_PREFIX . 'menusection');
define('TBL_P2C',           TBL_PREFIX . 'p2c');
define('TBL_POSTCATS',      TBL_PREFIX . 'postcats');
define('TBL_POSTGROUPS',    TBL_PREFIX . 'postgroup');
define('TBL_POSTMETA',      TBL_PREFIX . 'postmeta');
define('TBL_POSTS',         TBL_PREFIX . 'posts');
define('TBL_USERS',         TBL_PREFIX . 'users');
define('TBL_USERSMETA',     TBL_PREFIX . 'usersmeta');

define('TECH_MAIL',         'mysz@localhost');

?>
