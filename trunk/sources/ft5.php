<?php

$tpl->parse('MAIN', "main");                 // regular
$tpl->parse('MAIN', array("table", "main")); // compound
$tpl->parse('MAIN', ".row");                 // append

?>