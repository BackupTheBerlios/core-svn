<?php

header("Content-type: application/xml");

echo('<?xml version="1.0" encoding="iso-8859-2"?>');
echo "\n<rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\n\n";

define("PATH_TO_CLASSES", "administration/classes");

require(PATH_TO_CLASSES. "/cls_db_mysql.php"); // dodawanie pliku konfigurujacego bibliotekę baz danych
require("administration/inc/config.php");

require("inc/main_functions.php");
require("inc/i18n.php");

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

printf("	
<channel>
    <title>%1\$s</title>
    <link>http://" . $_SERVER['HTTP_HOST'] . "</link>
    <description>%2\$s</description>
    <language>%3\$s</language>
    <copyright>%4\$s</copyright>
",
  
    $i18n['rss'][0],
    $i18n['rss'][1],
    $i18n['rss'][2],
    $i18n['rss'][3]
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
	
	echo "<item>\n";
	echo "	<pubDate>" . $date . " GMT</pubDate>\n";
	echo "	<title>" . stripslashes($title) . "</title>\n";
	echo "	<dc:creator>" . $author . "</dc:creator>\n";
	echo "	<link>http://" . $_SERVER['HTTP_HOST'] . "/1," . $id . ",1,item.html</link>\n";
	echo "	<description>" . stripslashes($text) . "</description>\n";
	echo "	<category>" . $c_name . "</category>\n";
	echo "	<comments>http://" . $_SERVER['HTTP_HOST'] . "/1," . $id . ",2,item.html</comments>\n";
	echo "</item>\n\n";
}

echo "</channel>\n\n";
echo "</rss>"

?>