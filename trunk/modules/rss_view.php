<?php

$xmladdress = "http://www.osnews.com/files/recent.rdf";
$handle = fopen($xmladdress, "r");

while ($line=fgets($handle,1000)) {
	
	$data.=$line;
}
fclose($handle);

$rss = new RSS ($data, 1);

$allItems = $rss->getAllItems();
$itemCount = count($allItems);
for($y=0;$y<$itemCount;$y++) {
	
	if (strlen($allItems[$y]['DESCRIPTION']) > 70 ) {
			
		$allItems[$y]['DESCRIPTION'] = substr_replace($allItems[$y]['DESCRIPTION'], '...', 70);
	} else {
		$allItems[$y]['DESCRIPTION'] = $allItems[$y]['DESCRIPTION'];
	}
	
	if(strlen($allItems[$y]['DATE']) > 10 )
		$allItems[$y]['DATE'] = substr_replace($allItems[$y]['DATE'], '', 10);
	
	$ft->assign(array(	'RSS_TITLE'		=>$allItems[$y]['TITLE'],
						'RSS_LINK'		=>$allItems[$y]['LINK'],
					//	'RSS_DESC'		=>strip_tags($allItems[$y]['DESCRIPTION'], '<i></i>'),
						'RSS_DESC'		=>'',
						'RSS_DATE'		=>$allItems[$y]['DATE']));
						
	$ft->parse('RSS_PARSER', ".rss_view");

}

?>