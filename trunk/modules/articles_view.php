<?php


$ft->define('articles', "articles.tpl");


$contents_1 = file_get_contents("sources/ft1.php");

ob_start();
highlight_string($contents_1);
$contents_1 = ob_get_contents();
ob_end_clean();


$contents_2 = file_get_contents("sources/ft2.php");

ob_start();
highlight_string($contents_2);
$contents_2 = ob_get_contents();
ob_end_clean();


$contents_3 = file_get_contents("sources/ft3.php");

ob_start();
highlight_string($contents_3);
$contents_3 = ob_get_contents();
ob_end_clean();


$contents_4 = file_get_contents("sources/ft4.php");

ob_start();
highlight_string($contents_4);
$contents_4 = ob_get_contents();
ob_end_clean();


$contents_5 = file_get_contents("sources/ft5.php");

ob_start();
highlight_string($contents_5);
$contents_5 = ob_get_contents();
ob_end_clean();


$contents_6 = file_get_contents("sources/ft6.php");

ob_start();
highlight_string($contents_6);
$contents_6 = ob_get_contents();
ob_end_clean();


$contents_7 = file_get_contents("sources/ft7.php");

ob_start();
highlight_string($contents_7);
$contents_7 = ob_get_contents();
ob_end_clean();


$contents_8 = file_get_contents("sources/ft8.php");

ob_start();
highlight_string($contents_8);
$contents_8 = ob_get_contents();
ob_end_clean();


$contents_9 = file_get_contents("sources/ft9.php");

ob_start();
highlight_string($contents_9);
$contents_9 = ob_get_contents();
ob_end_clean();

$a = 1;

$ft->assign(array(  "CODE_$a"   =>$contents_1,
                    'CODE_2'    =>$contents_2,
                    'CODE_3'	=>$contents_3,
                    'CODE_4'	=>$contents_4,
                    'CODE_5'	=>$contents_5,
                    'CODE_6'	=>$contents_6,
                    'CODE_7'	=>$contents_7,
                    'CODE_8'	=>$contents_8,
                    'CODE_9'	=>$contents_9));

$ft->parse('ROWS',".articles");

?>