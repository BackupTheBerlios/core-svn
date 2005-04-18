<?php

header("Content-type: application/xml");

echo('<?xml version="1.0" encoding="iso-8859-2"?>');
echo "\n<rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\n\n";

define("PATH_TO_CLASSES", "administration/classes");

require_once('inc/i18n.php');
require(PATH_TO_CLASSES. "/cls_db_mysql.php"); // dodawanie pliku konfigurujacego bibliotekę baz danych
require("administration/inc/config.php");
require("inc/main_functions.php");

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

    $mysql_data['db_table_comments'],
    $mysql_data['db_table'],
    10
);

$db->query($query);

printf("
<channel>
	<title>%1\$s</title>
	<link>http://" . $_SERVER['HTTP_HOST'] . "</link>
	<description>%2\$s</description>
	<language>%3\$s</language> 
	<copyright>%4\$s</copyright>
",

    $i18n['rsscomments'][0],
    $i18n['rsscomments'][1],
    $i18n['rsscomments'][2],
    $i18n['rsscomments'][3]
);

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
	
	$text = strip_tags($text, '<br><a>');
	
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

	
	echo "<item>\n";
	echo "	<pubDate>" . $date . " GMT</pubDate>\n";
	echo "	<title>" . stripslashes($title) . "</title>\n";
	echo "	<dc:creator>" . $author . "</dc:creator>\n";
	echo "	<link>http://" . $_SERVER['HTTP_HOST'] . "/1," . $id . ",1,item.html</link>\n";
	echo "	<description>" . stripslashes($text) . "</description>\n";
	echo "</item>\n\n";
}

echo "</channel>\n\n";
echo "</rss>"

?>