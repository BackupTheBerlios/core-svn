<?php

header("Content-type: application/xml");

define("PATH_TO_CLASSES", "administration/classes");

require(PATH_TO_CLASSES. "/cls_db_mysql.php"); // dodawanie pliku konfigurujacego bibliotekę baz danych
require(PATH_TO_CLASSES. '/cls_fast_template.php');
require("administration/inc/config.php");

require("inc/main_functions.php");

$db     = new DB_SQL;
$query  = sprintf("
    SELECT 
        a.*, b.*, c.comments_id, count(DISTINCT c.id) 
    AS 
        comments 
    FROM 
        %1\$s a, 
        %2\$s b 
    LEFT JOIN 
        %3\$s c 
    ON 
        a.id = c.comments_id
    WHERE 
        b.category_id = a.c_id 
    AND 
        published = '1' 
    GROUP BY 
        a.date 
    DESC 
    LIMIT 
        %4\$d", 

    TABLE_MAIN, 
    TABLE_CATEGORY, 
    TABLE_COMMENTS, 
    10
);

$db->query($query);

// pobieranie informacji o uzyciu mod_rewrite
$rewrite = get_config('mod_rewrite');

// inicjowanie klasy, wkazanie katalogu przechowującego szablony
$ft = new FastTemplate('./templates/main/tpl/');

$ft->define('xml_feed', 'xml_feed.tpl');
$ft->define_dynamic('xml_row', 'xml_feed');

$ft->assign(array(
    'MAINSITE_LINK' =>$_SERVER['HTTP_HOST'], 
    'NEWS_FEED'     =>true
));

while($db->next_record()) {
	
	$date 			= $db->f("date");
	$title 			= $db->f("title");
	$text 			= $db->f("text");
	$author 		= $db->f("author");
	$id 			= $db->f("id");
	$c_id			= $db->f("c_id");
	$image			= $db->f("image");
	$comments_allow = $db->f("comments_allow");
	
	$c_name 		= $db->f("category_name");
	$c_id 			= $db->f("category_id");
	 
	// Przypisanie zmiennej $comments
	$comments 		= $db->f("comments");
	
	// zmiana formatu wyświetlania daty
	$date	= coreRssDateConvert($date);
	
	$text = strip_tags($text, '<br><a><div>');
	
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
    $c_name = str_replace("&", " and ", $c_name);

    $comments_link  = isset($rewrite) && $rewrite == 1 ? $_SERVER['HTTP_HOST'] . '/1,' . $id . ',2,item.html' : $_SERVER['HTTP_HOST'] . '/index.php?p=2&amp;id=' . $id . '';
    $permanent_link = isset($rewrite) && $rewrite == 1 ? $_SERVER['HTTP_HOST'] . '/1,' . $id . ',1,item.html' : $_SERVER['HTTP_HOST'] . '/index.php?p=1&amp;id=' . $id . '';
   
    $ft->assign(array(
        'DATE'          =>$date, 
        'TITLE'         =>stripslashes($title), 
        'AUTHOR'        =>$author, 
        'PERMALINK'     =>$permanent_link, 
        'TEXT'          =>stripslashes($text), 
        'CATEGORY'      =>$c_name, 
        'COMMENTS_LINK' =>$comments_link
    ));
    
    $ft->parse('XML_ROW', ".xml_row");
}

$ft->parse('CONTENT', "xml_feed");
$ft->FastPrint('CONTENT');

?>