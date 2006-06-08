<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

function __autoload($classname)
{
    if ('CE' == substr($classname, 0, 2)) {
        require_once sprintf(ROOT . '%sinc%sclass_exceptions.php', DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR);
        return;
    }
    
    $fname = strtolower($classname);
    $fname = str_replace('_', '', $fname);

    $fname1 = sprintf(ROOT . '%sinc%sclass_%s.php', DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $fname);
    if (is_file($fname1)) {
        require_once($fname1);
        return;
    }
    $fname2 = sprintf(ROOT . '%sinc%sns_%s.php', DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $fname);
    if (is_file($fname2)) {
        require_once($fname2);
        return;
    }
    echo $fname1 . '<br />';
    echo $fname2 . '<br />';
}
 
require_once sprintf('..%sconfig.php', DIRECTORY_SEPARATOR);
$config = new CoreConfig();

/*
new CoreInit(
    $config->enc_from,
    $config->enc_to,
    $config->comp_level,
    $config->email
);
*/

define('OPT_DIR', Path::join(ROOT, 'inc', 'opt') . DIRECTORY_SEPARATOR);
require_once Path::join(ROOT, 'inc', 'opt', 'opt.class.php');

$auth = new Auth;
echo $auth->authenticate('mysz', sha1('jsDhzc1'));

?>
