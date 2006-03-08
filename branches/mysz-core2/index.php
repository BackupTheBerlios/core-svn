<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// $Id$
// $HeadURL$

function __autoload($classname)
{
    $fname = strtolower($classname);
    $fname = str_replace('_', '', $fname);
    $fname1 = sprintf('inc%sclass_%s.php', DIRECTORY_SEPARATOR, $fname);
    if (is_file($fname1)) {
        require_once($fname1);
        return;
    }
    $fname2 = sprintf('inc%sns_%s.php', DIRECTORY_SEPARATOR, $fname);
    if (is_file($fname2)) {
        require_once($fname2);
        return;
    }
}

define('OPT_DIR', Path::join(ROOT, 'inc', 'opt'));

require_once 'config.php';
require_once Path::join(ROOT, 'inc', 'opt', 'opt.class.php');

new CoreInit(null, null, 5);

?>
