<?php

$ft->define('main_site', 'main_site.tpl');

if (get_config('get_rss') == 1) {
  $ft->assign('GET_RSS', true);

  $file = 'http://core-cms.com/rss';       // iSyndicate RSS
  $data = !function_exists('file_get_contents') ? implode('', file($file)) : file_get_contents($file);

  $simple = 1;

  $replacement = array(
      '&',
      '<br />', 
      '<',
      '>'
  );
    
  $pattern = array(
      ' &amp; ',
      '&lt;br /&gt;',
      '&lt;',
      '&gt;'
  );
    
  $data   = str_replace($pattern, $replacement, $data);
  $rss    = new rss_parser($data, $simple);

  $allItems   = $rss->getAllItems();
  $itemCount  = count($allItems);

  $ft->define_dynamic('rss_row', 'main_site');


  for($y = 0; $y < 5; $y++) {
    
    $ft->assign(array(
        'PERMA_LINK'    =>$allItems[$y]['LINK'],
        'NEWS_TITLE'    =>$allItems[$y]['TITLE'], 
        'DATE'          =>$allItems[$y]['DATE'],
        'NEWS_TEXT'     =>str_cut(strip_tags($allItems[$y]['DESCRIPTION'])) . '...'
    ));
    
    $ft->parse('ROWS', '.rss_row');
    
  }
} else {
  $ft->assign('GET_RSS', false);
}

// Inicjowanie egzemplarza klasy do obs³ugi Bazy Danych
$db = new DB_SQL;

// Zliczenie wszystkich publikowanych wpisów
$query = sprintf("
    SELECT 
        count(*) AS id 
    FROM 
        %1\$s 
    WHERE 
        published = '%2\$d' 
    ORDER BY 
        date", 

    TABLE_MAIN, 
    1
);

$db->query($query);
$db->next_record();
$published_items 	= $db->f('id');

// Zliczenie wszystkich nie publikowanych wpisów
$query = sprintf("
    SELECT 
        count(*) AS id 
    FROM 
        %1\$s 
    WHERE 
        published = '%2\$d' 
    ORDER BY 
        date", 

    TABLE_MAIN, 
    -1
);

$db->query($query);
$db->next_record();
$nonpublished_items 	= $db->f('id');

// Zliczenie wszystkich wpisów
$num_items 	= $published_items + $nonpublished_items;

$ft->assign(array(
    'COUNT_NOTES'		=>$num_items,
    'PUBLISHED_NOTES'	=>$published_items,
    'NONPUBLISHED_NOTES'=>$nonpublished_items
));
					
$ft->parse('ROWS', 'main_site');

?>
