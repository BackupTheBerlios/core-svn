<?php

// do³±czamy klasê
include("cls_fast_template.php");

$tpl = FastTemplate("/path/to/templates");

$tpl->define(array( 'main'  =>"main.html",
                    'row'   =>"table_row.html",
                    'all'   =>"table_all.html"));
					
$tpl->assign(TITLE, "I am the title.");

$defaults = array( 'URL'   =>'http://somesite.com',
                   'EMAIL' =>'some@email.com');
$tpl->assign($defaults);

$tpl->parse(ROWS, ".row");  // the '.' appends to ROWS
$tpl->parse(CONTENT, array("row", "all"));
$tpl->parse(CONTENT, "main");

$tpl->FastPrint(CONTENT);

$raw = $tpl->fetch("CONTENT");
echo "$rawn";
?>