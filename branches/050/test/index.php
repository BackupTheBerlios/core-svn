<?php
    static $a, $b;

    require_once 'a.php';
    require 'b.php';

    require_once 'a.php';
    require 'b.php';

    printf('%s<br />%s<br /><br /><hr /><br /><br />', $a, $b);

$a='s';
$s=2;
echo $$a;
?>
