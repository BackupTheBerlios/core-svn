<?php
session_start();

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// $Id$
// $HeadURL$

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
$cfg = CoreConfig::init();

# new CoreInit(
#     $cfg->enc_from,
#     $cfg->enc_to,
#     $cfg->comp_level,
#     $cfg->email
# );

define('OPT_DIR', Path::join(ROOT, 'inc', 'opt') . Path::DS);
require_once Path::join(ROOT, 'inc', 'opt', 'opt.class.php');

### $pm = new Meta('post', 1);
### 
### $pm->show();
### 
### $pm->sticky=0;
### $pm->allow_comments=0;
### $pm->only_in_category=0;
### $pm->test_meta = "asd";
### 
### $pm->show();
### 
### unset($pm->sticky);
### unset($pm->only_in_category);
### 
### $pm->show();
### 
### $pm->sticky=2;
### 
### $pm->show();
### 
### $pm->save();
### 
### $pm->show();

### //$controller = new CoreController($_SERVER['REQUEST_URI']);
$agg = new PostAggregate('Post');
$a = $agg[1];
$a->permalink = "qwe permalink qwerty";
$agg[count($agg)+1] = new Post();


//Arrays::debug($agg);
printf('Ilo¶æ: %d<br />', count($agg));
foreach ($agg as $p) {
    printf('%d: %s<br />', $p->id_post, $p->permalink);
}

?>
