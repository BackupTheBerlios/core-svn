<?php

header("Content-type: application/xml");

define("PATH_TO_CLASSES", "administration/classes");

require(PATH_TO_CLASSES. "/cls_db_mysql.php"); // dodawanie pliku konfigurujacego bibliotekê baz danych
require(PATH_TO_CLASSES. '/cls_fast_template.php');
require("administration/inc/config.php");

require("inc/common_lib.php");
require("inc/main_lib.php");

// mysql_server_version
get_mysql_server_version();

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

$rewrite    = get_config('mod_rewrite');
$lang       = get_config('language_set');

$ft = new FastTemplate('./templates/' . $lang . '/main/tpl/');

$ft->define('xml_feed', 'xml_feed.tpl');
$ft->define_dynamic('xml_row', 'xml_feed');
$ft->define_dynamic("cat_row", "xml_feed");

$http_root = get_httproot();

$ft->assign(array(
    'MAINSITE_LINK' =>'http://' . $http_root,
    'NEWS_FEED'     =>true
));

if($db->num_rows() > 0) {
    while($db->next_record()) {
	
	   $date           = $db->f("date");
	   $title          = $db->f("title");
	   $text           = $db->f("text");
	   $author         = $db->f("author");
	   $id             = $db->f("id");
	   $image          = $db->f("image");
	   $comments_allow = $db->f("comments_allow");
	 
	   // Przypisanie zmiennej $comments
	   $comments       = $db->f("comments");
	
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
    
        $text = str_replace($pattern, $replacement, $text);
    
        list_assigned_categories($id);
    
        if((bool)$rewrite) {
            
            $comments_link  = sprintf('%s1,%s,2,item.html', $http_root, $id);
            $permanent_link = sprintf('%s1,%s,1,item.html', $http_root, $id);
        } else {

            $comments_link  = sprintf('%sindex.php?p=2&amp;id=%s', $http_root, $id);
            $permanent_link = sprintf('%sindex.php?p=1&amp;id=%s',$http_root, $id);
        }
   
        $ft->assign(array(
            'DATE'          =>$date, 
            'TITLE'         =>$title, 
            'AUTHOR'        =>$author, 
            'PERMALINK'     =>$permanent_link, 
            'TEXT'          =>$text, 
            'COMMENTS_LINK' =>$comments_link, 
            'DISPLAY_XML'   =>true
        ));
    
        $ft->parse('XML_ROW', ".xml_row");
    }
} else {
    
    $ft->assign('DISPLAY_XML', false);
    $ft->parse('XML_ROW', ".xml_row");
}

$ft->parse('CONTENT', "xml_feed");
$ft->FastPrint('CONTENT');

?>
