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
    LEFT JOIN 
        %4\$s d 
    ON 
        a.id = d.news_id
    WHERE 
        published = '1' 
    GROUP BY 
        a.date 
    DESC 
    LIMIT 
        %5\$d", 

    TABLE_MAIN, 
    TABLE_CATEGORY, 
    TABLE_COMMENTS, 
    TABLE_ASSIGN2CAT, 
    10
);

$db->query($query);

// pobieranie informacji o uzyciu mod_rewrite
$rewrite = get_config('mod_rewrite');

// inicjowanie klasy, wkazanie katalogu przechowuj±cego szablony
$ft = new FastTemplate('./templates/main/tpl/');

$ft->define('xml_feed', 'xml_feed.tpl');
$ft->define_dynamic('xml_row', 'xml_feed');
$ft->define_dynamic("cat_row", "xml_feed");

$i = pathinfo($_SERVER['REQUEST_URI']);
$s = $_SERVER['HTTP_HOST'];

$ft->assign(array(
    'MAINSITE_LINK' =>get_httproot(),
    'NEWS_FEED'     =>true
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
    
    list_assigned_categories($id);
    
    if((bool)$rewrite) {
        $comments_link  = $s . '/' . substr($i['dirname'], 1) . '/1,' . $id . ',2,item.html';
        $permanent_link = $s . '/' . substr($i['dirname'], 1) . '/1,' . $id . ',1,item.html';
    } else {
        $comments_link  = $s . '/' . substr($i['dirname'], 1) . '/index.php?p=2&amp;id=' . $id;
        $permanent_link = $s . '/' . substr($i['dirname'], 1) . '/index.php?p=1&amp;id=' . $id;
    }
   
    $ft->assign(array(
        'DATE'          =>$date, 
        'TITLE'         =>$title, 
        'AUTHOR'        =>$author, 
        'PERMALINK'     =>$permanent_link, 
        'TEXT'          =>$text, 
        'COMMENTS_LINK' =>$comments_link
    ));
    
    $ft->parse('XML_ROW', ".xml_row");
}

$ft->parse('CONTENT', "xml_feed");
$ft->FastPrint('CONTENT');

?>
