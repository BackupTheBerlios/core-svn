<?php

$db = new MySQL_DB;

//skoro uzywasz juz printfa, to daj mu popracowac...
//no i ladnie uloz cale zapytanie, wtedy bedzie czytelniejsze
$query = sprintf("
  SELECT 
    a.*,
    b.*,
    c.comments_id,
    count(c.id) AS comments 
  FROM 
    %s a,
    %s b 
  LEFT JOIN 
      %s c 
    ON 
      a.id = c.comments_id
  WHERE 
    a.id = '%d' 
  AND 
    b.category_id = a.c_id 
  AND 
    published = 'Y' 
  GROUP BY 
    a.date 
  DESC 
  LIMIT 1",

  $mysql_data['db_table'],
  $mysql_data['db_table_category'],
  $mysql_data['db_table_comments'],
  $_GET['id']
);

$db->query($query);

if($db->num_rows() > 0) {

	$db->next_record();

	$date 			= $db->f("date");
	$title 			= $db->f("title");
	$text 			= $db->f("text");
	$author 		= $db->f("author");
	$id 			= $db->f("id");
	$c_id 			= $db->f("c_id");
	$image			= $db->f("image");
	$comments_allow = $db->f("comments_allow");
	
	$c_id 			= $db->f("category_id");
	
  //do tego przydalaby sie konkretna funkcja - jesli cos wykonujesz 2 razy w ten sam sposob, stworz temu funkcje. przyspieszy to wykonanie.
	$c_name	= str_replace('&', '&amp;', $db->f("category_name"));
		
	$comments 		= $db->f("comments");

	$date			= coreDateConvert($date);
	
  //to tez landie mozna poukladac - zwiekszysz czytelnosc kodu. php umozliwia zrobienie z kodu smietnika, ale pozwala Ci tez na ulozenie go...
  $ft->assign(array(
    'DATE'				=>$date,
		'NEWS_TITLE'		=>$title,
		'NEWS_TEXT'			=>$text,
		'NEWS_AUTHOR'		=>$author,
		'NEWS_ID'			=>$id,
		'CATEGORY_NAME'		=>$c_name,
		'NEWS_CATEGORY'		=>$c_id,
    'STRING'			=>""
  ));

  //czytelnosc: dla oka i mozgu latwiej jest zrozumiec zapis:
  if(! $comments_allow) {
  //niz :
	//if(($comments_allow) == 0 ) {
			
		$ft->assign(array('COMMENTS_ALLOW'	=>"<br />"));
	} else {

    //tu ew. tez mozna uzyc wersji z ! - w php 0 jest tozsame (dla porownania '==') z false
		if($comments == 0) {
			
			$ft->define('comments_link_empty', "comments_link_empty.tpl");
				
			$ft->parse('COMMENTS_ALLOW', "comments_link_empty");
		} else {
			
			$ft->define('comments_link_alter', "comments_link_alter.tpl");
			$ft->assign('COMMENTS', $comments);
				
			$ft->parse('COMMENTS_ALLOW', "comments_link_alter");
		}	
	}
	
	if(empty($image)) {

		$ft->assign(array('IMAGE' =>""));
	} else {

    //w main_functions.php masz funkcje dodane preze mnie, w tym get_root(). tam, gdzie korzystasz z takich sciezek, warto korzystac z tejze funkcji - zwraca ona katalog domowy core.
    //ponizej 2x korzystasz z tworzeni=a sciezki do obrazka - w if'ie, i przy pobieraniu wymiarow. wstaw ta sciezke do zmiennej:
    $img_path = get_root() . '/photos/' . $image;
  //tak btw - parsowanie tekstu w '' jest szybsze niz w "" - robilem swego czasu testy. wszystko, czego nie potrzebujesz parsowac, jak np ponizej w linii 118, warto wrzucac w '' zamiast w "". a poza tym, staraj sie zawsze unikac wsadzania tekstu i zmiennych w "", bo raz, ze obniza to czytelnosc, dwa, ze powoduje problemy z bezpieczenstwem czasem - [s]printf pozwala uniknac atakow html i sql injection
		if(is_file($img_path)) {
			list($width, $height) = getimagesize($img_path);
		
      //czytelnosc - takie ulozenie nawiasow pozwala na latwiejsze wychwycenie blokow kodu
      $ft->assign(array(
        'WIDTH'		=>$width,
        'HEIGHT'	=>$height
      ));
		
      //takie cos nie powinno byc na stale, tylko gdzies konfigurowalne. mamy zakladke settings w core ? a jesli nie chcesz tam, to w config.php utworz stala ktora bedzie sie tam modyfikowac, a ktora w tym przypadku bedzie zawierac 440
			if($width > 440) {
			
				// template prepare
				$ft->define('image_alter', "image_alter.tpl");
				$ft->assign('UID', $id);
				
				$ft->parse('IMAGE', "image_alter");
			} else {
			
				// template prepare
				$ft->define('image_main', "image_main.tpl");
				$ft->assign('IMAGE_NAME', $image);

				$ft->parse('IMAGE', "image_main");
			}
		}
	}

	$ft->parse('ROWS',".single_rows");
} else {
	
  //czytelnosc. zebys wiedzial o czym mowie, zostawiam Twoja wersje zakomentowana
	//$ft->assign(array(	'QUERY_FAILED'	=>"W bazie danych nie ma wpisu o ¿±danym id",
	//					'STRING'			=>""));
  $ft->assign(array(
    'QUERY_FAILED'	=>"W bazie danych nie ma wpisu o ¿±danym id",
		'STRING'			=>""
  ));
	
	$ft->parse('ROWS',".query_failed");
}

//ps. umawialismy sie na to, zeby zmienic wciecia i uzywac spacji zamiast tabow... abstrachuje tu od ilosci tychze soacji, dla mnie najwygodniejsze jest 2, ale to mozna sie dogadac jeszcze.. :)
?>
