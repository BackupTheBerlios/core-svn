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

require_once 'config.php';

//new CoreInit(null, null, 0);


try {
    $i = new Image('img01.jpg');

    //$i->scale_nonprop(100, 100, '334455');
    //$i->scale_prop(100, 100, '334455');
    //$i->crop2(0, 500, 400, 100);
    //$i->crop1(100, 100, 200, 200);

    $b = $i->color('000000');
    $w = $i->color('ffffff');

    $r = $i->color('ff0000');
    $l = $i->color('0000ff');

    $g = $i->color('00ff00');
    $v = $i->color('ff00ff');

    
    $i->rotate(180);
    $i->addLayer('img05.jpg', 50);
    $style1 = array();
    for ($j=0; $j<20; ++$j) $style1[] = $b;
    for ($j=0; $j<20; ++$j) $style1[] = $w;

    $style2 = array();
    for ($j=0; $j<20; ++$j) $style2[] = $r;
    for ($j=0; $j<20; ++$j) $style2[] = $l;

    $style3 = array();
    for ($j=0; $j<20; ++$j) $style3[] = $g;
    for ($j=0; $j<20; ++$j) $style3[] = $v;

    $style4 = array();
    for ($j=0; $j<20; ++$j) $style4[] = $v;
    for ($j=0; $j<20; ++$j) $style4[] = $r;

    $style = array($style1, $style2, $style3, $style4);

    $i->border(1, null, $style); //array('aabbcc', '112233', '445566', '778899'), $style);
    //header('Content-type: image/jpeg');
    //echo $i->returnString();
    $i->show('jpg');
} catch(Exception $e) {
    echo $e->getMessage();
}


?>
