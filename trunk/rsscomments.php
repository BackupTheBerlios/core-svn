<?php

header("Content-type: application/xml");

define("PATH_TO_CLASSES", "administration/classes");

require(PATH_TO_CLASSES. "/cls_db_mysql.php"); // dodawanie pliku konfigurujacego bibliotekê baz danych
require(PATH_TO_CLASSES. '/cls_fast_template.php');
require("administration/inc/config.php");

require("inc/common_lib.php");
require("inc/main_lib.php");

$db     = new DB_SQL;
$query  = sprintf("
    SELECT 
        b.*, a.id, a.title 
    FROM 
        %1\$s b 
    LEFT JOIN 
        %2\$s a 
    ON 
        b.comments_id = a.id 
    GROUP BY 
        date 
    DESC 
    LIMIT 
        %3\$d", 

    TABLE_COMMENTS,
    TABLE_MAIN,
    10
);

$db->query($query);

// pobieranie informacji o uzyciu mod_rewrite
$rewrite = get_config('mod_rewrite');

// inicjowanie klasy, wkazanie katalogu przechowuj±cego szablony
$ft = new FastTemplate('./templates/main/tpl/');

$ft->define('xml_feed', 'xml_feed.tpl');
$ft->define_dynamic('xml_row', 'xml_feed');

$i = pathinfo($_SERVER['REQUEST_URI']);
$s = $_SERVER['HTTP_HOST'];

$ft->assign(array(
    'MAINSITE_LINK' =>get_httproot(),
    'NEWS_FEED'     =>false
));

while($db->next_record()) {
	
	$date 			= $db->f("date");
	$title 			= $db->f("title");
	$text 			= $db->f("text");
	$author 		= $db->f("author");
	$id 			= $db->f("id");
	$image			= $db->f("image");
	$comments_allow = $db->f("comments_allow");
	 
	// Przypisanie zmiennej $comments
	$comments 		= $db->f("comments");
	
	// zmiana formatu wy¶wietlania daty
	$date	= coreRssDateConvert($date);
	
    $pattern = array(
        "&",
        "<br />", 
        "<",
        ">"
    );
    
    $replacement = array(
        " &amp; ",
        "&lt;br /&gt;",
        "&lt;",
        "&gt;"
    );
    
    $text   = str_replace($pattern, $replacement, $text);

    $permanent_link = (bool)$rewrite ? $s . '/' . substr($i['dirname'], 1) . '/1,' . $id . ',1,item.html' : $s . '/' . substr($i['dirname'], 1) . '/index.php?p=1&amp;id=' . $id . '';
   
    $ft->assign(array(
        'DATE'          =>$date, 
        'TITLE'         =>$title, 
        'AUTHOR'        =>$author, 
        'PERMALINK'     =>$permanent_link, 
        'TEXT'          =>$text
    ));
    
    $ft->parse('XML_ROW', ".xml_row");
}

$ft->parse('CONTENT', "xml_feed");
$ft->FastPrint('CONTENT');

?>
