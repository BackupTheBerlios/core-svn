<?php

$tpl->parse('MAIN', "table");
$tpl->parse('MAIN', ".main");

// is the same as:

$tpl->parse('MAIN', array("table", "main"));
// this form saves function calls and makes your code
// cleaner

?>