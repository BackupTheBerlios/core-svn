<?php

// ¶cie¿ka do katalogu z szablonami
$tpl = new FastTemplate("./templates");

$tpl->define(array( 'main'  =>"main.html",
                    'table' =>"dynamic.html"));

$tpl->define_dynamic("row", "table" );

?>