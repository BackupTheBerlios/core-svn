<?php

class T
{
    function set_test()
    {
        echo 'passed';
    }
}

$method = 'set_test';
$o = new T;
$o->$method();

$m = 'test';

?>
