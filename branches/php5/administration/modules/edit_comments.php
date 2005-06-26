<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch ($action) {
	
	case "show": // wy¶wietlanie wpisu pobranego do modyfikacji
	
		$query = sprintf("
            SELECT
                 id,
                 DATE_FORMAT(date, '%%d-%%m-%%Y %%T') AS date,
                 comments_id,
                 author,
                 author_ip,
                 email,
                 text
            FROM 
                %1\$s 
            WHERE 
                id = '%2\$d'", 
		
            TABLE_COMMENTS, 
            $_GET['id']
        );
		
		$db->query($query);
		$db->next_record();
		
		$date 		= $db->f("date");
		$title 		= $db->f("title");
		$text 		= $db->f("text");
		$author		= $db->f("author");
		$published	= $db->f("published");
		
		$ft->assign(array(
            'AUTHOR'    =>$author,
            'DATE'      =>$date,
            'ID'        =>$_GET['id'],
            'TEXT'      =>br2nl($text)
        ));

		$ft->define('form_commentsedit', "form_commentsedit.tpl");
		$ft->parse('ROWS',	".form_commentsedit");
		break;
	
	case "edit": // edycja wybranego wpisu
	
        if($permarr['moderator']) {
	
            $text     = parse_markers($_POST['text'], 1);
            $author   = $_POST['author'];
		
            //sprawdzania daty
            if(isset($_POST['now']) || !preg_match('#^([0-9][0-9])-([0-9][0-9])-([0-9][0-9][0-9][0-9]) ([0-9][0-9]:[0-9][0-9]:[0-9][0-9])$#', $_POST['date'], $matches)) {
                $date = date("Y-m-d H:i:s");
            } else {
                $date = sprintf('%s-%s-%s %s', $matches[3], $matches[2], $matches[1], $matches[4]);
            }

            $query = sprintf("
                UPDATE 
                    %1\$s 
                SET 
                    author	= '%2\$s', 
                    text	= '%3\$s',
                    date    = '%4\$s'
                WHERE 
                    id = '%5\$d'", 
		
                TABLE_COMMENTS, 
                $author, 
                $text, 
                $date,
                $_GET['id']
            );
		
            $db->query($query);
		
            $ft->assign('CONFIRM', $i18n['edit_comments'][0]);
            $ft->parse('ROWS',	".result_note");
        } else {
            
            $monit[] = $i18n['edit_comments'][3];

            foreach ($monit as $error) {
    
                $ft->assign('ERROR_MONIT', $error);
                    
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
        }
		break;
	
	case "delete": // usuwanie wybranego wpisu
	
        // potwierdzenie usuniecia komentarza
        $confirm = empty($_POST['confirm']) ? '' : $_POST['confirm'];
        switch ($confirm) {
            
            case $i18n['confirm'][0]:
            
                $post_id = empty($_POST['post_id']) ? '' : $_POST['post_id'];
	
                if($permarr['moderator']) {
	
                    $query = sprintf("
                        DELETE FROM 
                            %1\$s 
                        WHERE 
                            id = '%2\$d'", 
            
                        TABLE_COMMENTS, 
                        $post_id
                    );
		
                    $db->query($query);
            
                    $ft->assign('CONFIRM', $i18n['edit_comments'][1]);
                    $ft->parse('ROWS', ".result_note");
                } else {
            
                    $monit[] = $i18n['edit_comments'][4];

                    foreach ($monit as $error) {
    
                        $ft->assign('ERROR_MONIT', $error);
                    
                        $ft->parse('ROWS',	".error_row");
                    }
                        
                $ft->parse('ROWS', "error_reporting");
                }
            break;
            
        case $i18n['confirm'][1]:
        
            header("Location: main.php?p=5");
            exit;
            break;
            
        default:
        
            $ft->define('confirm_action', 'confirm_action.tpl');
            $ft->assign(array(
                'PAGE_NUMBER'   =>$p, 
                'POST_ID'       =>$_GET['id'], 
                'CONFIRM_YES'   =>$i18n['confirm'][0],
                'CONFIRM_NO'    =>$i18n['confirm'][1]
            ));
            
            $ft->parse('ROWS', ".confirm_action");
            break;
        }
    break;

	default:
	
        $mainposts_per_page = get_config('editposts_per_page');

        // zliczamy posty
        $query = sprintf("
            SELECT 
                COUNT(*) AS id 
            FROM 
                %1\$s 
            ORDER BY 
                date", 
	
            TABLE_COMMENTS
        );

        $db->query($query);
        $db->next_record();
	
        $num_items = $db->f("0");

        // inicjowanie funkcji stronnicuj±cej wpisy
        $pagination = pagination('main.php?p=5&amp;start=', $mainposts_per_page, $num_items);
		
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            ORDER BY 
                date 
            DESC LIMIT 
                %2\$d, %3\$d", 
		
            TABLE_COMMENTS, 
            $start, 
            $mainposts_per_page
        );
		
		$db->query($query);
		
		// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
		if($db->num_rows() > 0) {
		
			// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
			while($db->next_record()) {
		
				$id 		= $db->f("id");
				$text 		= $db->f("text");
				$date 		= $db->f("date");
				$author		= $db->f("author");
				$author_ip	= $db->f("author_ip");
			
				$date = explode(' ', $date);
			
				if (strlen($text) > 70 ) {
				
					$text = substr_replace($text, '...',70);
				} else {
					$text = $text;
				}
			
				$ft->assign(array(
				    'ID'		=>$id,
				    'TEXT'		=>$text,
				    'DATE'		=>$date[0],
				    'AUTHOR'	=>$author,
				    'AUTHOR_IP'	=>$author_ip, 
                    'PAGINATED' =>!empty($pagination['page_string']) ? true : false, 
                    'STRING'    =>$pagination['page_string']
				));
			
				// deklaracja zmiennej $idx1::color switcher
				$idx1 = empty($idx1) ? '' : $idx1;
				
				$idx1++;
			
				$ft->define("editlist_comments", "editlist_comments.tpl");
				$ft->define_dynamic("row", "editlist_comments");
				
				// naprzemienne kolorowanie wierszy tabeli
				$ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');
				
				$ft->parse('ROW', ".row");
			}
		
			$ft->parse('ROWS', "editlist_comments");;
		} else {
		
			$ft->assign('CONFIRM', $i18n['edit_comments'][2]);

			$ft->parse('ROWS',	".result_note");
		}
}

?>