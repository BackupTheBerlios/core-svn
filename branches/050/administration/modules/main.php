<?php
// $Id: main.php 1128 2005-08-03 22:16:55Z mysz $

$ft->define("main_site", "main_site.tpl");


if ((bool)get_config('core_rss'))
{
    $ft->assign('CORE_RSS', true);
    $file = 'http://core-cms.com/rss';       // iSyndicate RSS
    $data = !function_exists('file_get_contents') ? implode('', file($file)) : file_get_contents($file);

    $simple = 1;

    $replacement = array(
        "&",
        "<br />", 
        "<",
        ">"
    );
    
    $pattern = array(
        " &amp; ",
        "&lt;br /&gt;",
        "&lt;",
        "&gt;"
    );
    
    $data   = str_replace($pattern, $replacement, $data);
    $rss    = new rss_parser($data, $simple);

    $allItems   = $rss->getAllItems();
    $itemCount  = count($allItems);

    $ft->define_dynamic("rss_row", "main_site");

    function str_cut($s, $i=110, $c=' ') {
        return substr($s, 0, strrpos(substr($s, 0, $i), $c));
    }

    for($y = 0; $y < 5; $y++) {

        $ft->assign(array(
            'PERMA_LINK'    =>$allItems[$y]['LINK'],
            'NEWS_TITLE'    =>$allItems[$y]['TITLE'], 
            'DATE'          =>$allItems[$y]['DATE'],
            'NEWS_TEXT'     =>str_cut(strip_tags($allItems[$y]['DESCRIPTION'])) . '...'
        ));
    
        $ft->parse('ROWS', ".rss_row");
    }
}
else
{
    $ft->assign('CORE_RSS', false);
}

// Inicjowanie egzemplarza klasy do obs�ugi Bazy Danych
$db = new DB_SQL;

// Zliczenie wszystkich publikowanych wpis�w
$query = sprintf("
    SELECT 
        count(*) AS id 
    FROM 
        %1\$s 
    WHERE 
        published = 1
    ORDER BY 
        date", 

    TABLE_MAIN
);

$db->query($query);
$db->next_record();
$published_items 	= $db->f("id");

// Zliczenie wszystkich nie publikowanych wpis�w
$query = sprintf("
    SELECT 
        count(*) AS id 
    FROM 
        %1\$s 
    WHERE 
        published = -1
    ORDER BY 
        date", 

    TABLE_MAIN 
);

$db->query($query);
$db->next_record();
$nonpublished_items 	= $db->f("id");

// Zliczenie wszystkich wpis�w
$num_items 	= $published_items + $nonpublished_items;

$ft->assign(array(
    'COUNT_NOTES'		=>$num_items,
    'PUBLISHED_NOTES'	=>$published_items,
    'NONPUBLISHED_NOTES'=>$nonpublished_items
));
					
$ft->parse('ROWS', "main_site");

?>
