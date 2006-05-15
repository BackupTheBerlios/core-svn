<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// $Id: index.php 1319 2006-03-13 17:07:26Z mysz $
// $HeadURL: https://lark@svn.berlios.de/svnroot/repos/core/branches/mysz-core2/index.php $

function __autoload($classname)
{
    if ('CE' == substr($classname, 0, 2)) {
        require_once sprintf('inc%sclass_exceptions.php', DIRECTORY_SEPARATOR);
        return;
    }
    
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

require_once 'config.php';
$config = new CoreConfig();
new CoreInit(
    $config->enc_from,
    $config->enc_to,
    $config->comp_level,
    $config->email
);

define('OPT_DIR', Path::join(ROOT, 'inc/opt') . DIRECTORY_SEPARATOR);
require_once Path::join(ROOT, 'inc/opt/opt.class.php');

$id_post = 1;
try {
    $post = new Post($id_post);
    $post->title = 'asd';
    $post->show();
} catch (CENotFound $e) {
    echo '<pre>';
    echo $e;
    echo '</pre>';
    exit;
}

//$post->setFromDB(1);
?>
