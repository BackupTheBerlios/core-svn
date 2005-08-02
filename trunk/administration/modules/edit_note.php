<?php

// deklaracja zmiennej $action::form
$action     = empty($_GET['action']) ? '' : $_GET['action'];
//$preview    = empty($_POST['preview']) ? '' : $_POST['preview'];
//$post       = empty($_POST['post']) ? '' : $_POST['post'];

function get_news_from_post($id_news) {
    $data['date'] = isset($_POST['now']) ? date('Y-m-d H:i:s') : $_POST['now'];
    $data['title']  = str_entit($_POST['title']);
    $data['text']   = str_entit($_POST['text']);
    $data['author'] = str_entit($_POST['author']);
    $data['image']  = str_entit($_POST['image']);
    $data['id']     = $id_news;
    $data['oic']    = (@$_POST['only_in_category'] == 1);
    $data['ca']     = (@$_POST['comments_allow'] == 1);
    $data['p']      = (@$_POST['published'] == 1);

    //pobieramy do jakich kategorii nalezy dany wpis
    $data['cats'] = isset($_POST['assign2cat']) ? $_POST['assign2cat'] : array();
    return $data;
}

function get_news_from_db($id_news) {
    global $db;
    $data = array();

    $query = sprintf("
        SELECT
            id,
            DATE_FORMAT(date, '%%Y-%%m-%%d %%T') AS date,
            title,
            author,
            text,
            image,
            comments_allow,
            published, 
            only_in_category
        FROM 
            %1\$s 
        WHERE 
            id = '%2\$d'", 

        TABLE_MAIN,
        $_GET['id']
    );

    $db->query($query);
    $db->next_record();

    $data['date']   = $db->f('date');
    $data['title']  = $db->f('title');
    $data['text']   = str_br2nl($db->f('text'));
    $data['author'] = $db->f('author');
    $data['image']  = $db->f('image');
    $data['id']     = $id_news;
    $data['oic']    = ($db->f('only_in_category') == 1);
    $data['ca']     = ($db->f('comments_allow') == 1);
    $data['p']      = ($db->f('published') == 1);

    //pobieramy do jakich kategorii nalezy dany wpis
    $data['cats'] = array();
    $query = sprintf("
        SELECT
            id,
            news_id,
            category_id
        FROM
            %1\$s
        WHERE
            news_id = %2\$d",
        
        TABLE_ASSIGN2CAT,
        $data['id']
    );
    $db->query($query);
    while ($db->next_record()) {
        $data['cats'][] = $db->f('category_id');
    }

    /*
    $query = sprintf("
        SELECT 
            category_id, 
            category_parent_id, 
            category_name 
        FROM 
            %1\$s 
        WHERE 
            category_parent_id = 0", 

        TABLE_CATEGORY
    );
    $sql = new DB_SQL;

    $db->query($query);
    while($db->next_record()) {
    
        $c_id 	= $db->f("category_id");
        $c_name = $db->f("category_name");
    
        $query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                category_id = '%2\$d' 
            AND 
                news_id = '%3\$d'", 

            TABLE_ASSIGN2CAT, 
            $c_id, 
            $_GET['id']
        );
    
        $sql->query($query);
        $sql->next_record();
    
        $assigned = $sql->f("category_id");

        $ft->assign(array(
            'C_ID'		    =>$c_id,
            'C_NAME'	   =>$c_name, 
            'PAD'           =>'', 
            'CURRENT_CAT'   =>$c_id == $assigned ? 'checked="checked"' : ''
        ));
    
        $ft->define("form_noteedit", "form_noteedit.tpl");
        $ft->define_dynamic("cat_row", "form_noteedit");

        $ft->parse('CAT_ROW', ".cat_row");
            
        get_editnews_assignedcat($c_id, 2);
    }*/
    return $data;
}

function get_news($id_news) {
    return empty($_POST) ? get_news_from_db($_GET['id']) : get_news_from_post($_GET['id']);
}

$monit = array();
$ft->assign('NOTE_PREVIEW', false);
if (isset($_POST['sub_commit'])) { //modyfikujemy wpis
    $monit[] = 'wpis zapisany';
} elseif (isset($_POST['sub_preview'])) { //podglad wpisanej tresci
    $ft->assign( 'NOTE_PREVIEW', str_nl2br(parse_markers(stripslashes($_POST['text']), 1)) );
} elseif (isset($_POST['sub_img_delete'])) { //usuwamy foto
    $monit[] = 'usuniete foto';
}



$data = get_news($_GET['id']);

$oic_y = 'checked="checked"';
$oic_n = '';
$ca_y = 'checked="checked"';
$ca_n = '';
$p_y = 'checked="checked"';
$p_n = '';
if (!$data['oic']) {
    $oic_y = '';
    $oic_n = 'checked="checked"';
}
if (!$data['ca']) {
    $ca_y = '';
    $ca_n = 'checked="checked"';
}
if (!$data['p']) {
    $p_y = '';
    $p_n = 'checked="checked"';
}

$ft->assign(array(
    'AUTHOR'		        => $data['author'],
    'DATE' 			        => $data['date'],
    'ID'			        => $_GET['id'],
    'TITLE'                 => $data['title'],
    'TEXT'                  => $data['text'],
    'ONLY_IN_CAT_YES'       => $oic_y,
    'ONLY_IN_CAT_NO'        => $oic_n,
    'COMMENTS_ALLOW_YES'    => $ca_y,
    'COMMENTS_ALLOW_NO'     => $ca_n,
    'PUBLISHED_YES'         => $p_y,
    'PUBLISHED_NO'          => $p_n,
    'IMG_FILENAME'          => empty($data['image']) ? false : $data['image']
));
unset($oic_y, $oic_n, $ca_y, $ca_n, $p_y, $p_n);



//lista kategorii
$query = sprintf("
    SELECT 
        category_id, 
        category_parent_id, 
        category_name 
    FROM 
        %1\$s", 

    TABLE_CATEGORY
);
$cats = array();
$db->query($query);
while ($db->next_record()) {
    $parent_id = $db->f('category_parent_id');
    $c_name = $db->f('category_name');
    $c_id = $db->f('category_id');

    if (!array_key_exists($parent_id, $cats)) {
        $cats[$parent_id] = array();
    } 

    $cats[$parent_id][$c_id] = $c_name;
    
}
function c(&$cat, $parent, $pad = 0) {
    if ( !($ul = array_key_exists($parent, $cat)) ) return false;

    $pad_str = str_repeat("\t", $pad);
    echo $ul ? $pad_str . "<ul>\n" : '';

    foreach ($cat  as $parent_id => $c_data) {
        if ($parent == $parent_id) {
            foreach ($c_data as $c_id => $c_name) {
                printf("%s<li title='id: %s'>%s\n", $pad_str . "\t", $c_id, $c_name);
                c($cat, $c_id, $pad+1);
                printf("%s</li>\n", $pad_str . "\t");
            }
        }
    }
    echo $ul ? $pad_str . "</ul>\n" : '';
}

//c($cats, 0);
//v_array($cats);
//exit;


while($db->next_record()) {

    $c_id 	= $db->f("category_id");
    $c_name = $db->f("category_name");

    $selected_cat = $sql->f("category_id");

    $ft->assign(array(
        'C_ID'		    =>$c_id,
        'C_NAME'	   =>$c_name, 
        'PAD'           =>'', 
        'CURRENT_CAT'   =>$c_id == $assigned ? 'checked="checked"' : ''
    ));

    $ft->define("form_noteedit", "form_noteedit.tpl");
    $ft->define_dynamic("cat_row", "form_noteedit");

    $ft->parse('CAT_ROW', ".cat_row");
        
    get_editnews_assignedcat($c_id, 2);
}

$ft->parse('ROWS',	"form_noteedit");


/*
$ft->assign('IMG_FILENAME', false);


switch ($action) {
	
	case "show":// wy¶wietlanie wpisu pobranego do modyfikacji
	
        // podglad
        if(!empty($preview)) {
        } else {
        }
        
        // submit formularza
        if(!empty($post)) {

            $query = sprintf("
                SELECT * FROM 
                    %1\$s 
                WHERE 
                    id = '%2\$d'", 
		
                TABLE_MAIN,
                $_GET['id']
            );
		
            $db->query($query);
            $db->next_record();
		
            $note_author = $db->f("author");
		
            if($permarr['moderator'] || ($permarr['writer'] && $note_author == $_SESSION['login'])) {
                
                $text		= $_POST['text'];
                $title		= $_POST['title'];
                $author		= $_POST['author'];
                $published	= $_POST['published'];
            
                $comments_allow = $_POST['comments_allow'];
                $only_in_cat    = $_POST['only_in_category'];
                $assign2cat     = $_POST['assign2cat'];

                //sprawdzania daty
                if (isset($_POST['now']) || !preg_match('#^([0-9][0-9])-([0-9][0-9])-([0-9][0-9][0-9][0-9]) ([0-9][0-9]:[0-9][0-9]:[0-9][0-9])$#', $_POST['date'], $matches)) {
                    $date = date("Y-m-d H:i:s");
                } else {
                    $date = sprintf('%s-%s-%s %s', $matches[3], $matches[2], $matches[1], $matches[4]);
                }
            
                $text = parse_markers($text, 1);
		
                $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        title			= '%2\$s', 
                        author			= '%3\$s', 
                        text			= '%4\$s', 
                        published		= '%5\$s', 
                        comments_allow	= '%6\$d',
                        date            = '%7\$s', 
                        only_in_category= '%8\$s'
                    WHERE 
                        id = '%9\$d'", 
            
                    TABLE_MAIN, 
                    $title, 
                    $author, 
                    $text, 
                    $published, 
                    $comments_allow, 
                    $date, 
                    $only_in_cat, 
                    $_GET['id']
                );
            
                $db->query($query);
            
                $query = sprintf("
                    DELETE FROM 
                        %1\$s 
                    WHERE 
                        news_id = '%2\$d'", 
            
                    TABLE_ASSIGN2CAT, 
                    $_GET['id']
                );
                $db->query($query);
            
                // wprowadzamy informacje o przynaleznych kategoriach
                foreach ($assign2cat as $selected_cat) {
                    $query = sprintf("
                        INSERT INTO 
                            %1\$s 
                        VALUES('', '%2\$d', '%3\$d')", 
                
                        TABLE_ASSIGN2CAT, 
                        $_GET['id'], 
                        $selected_cat
                    );
                    $db->query($query);
                }
            
                // usuwamy istniej±ce zdjêcie
                if(isset($_POST['delete_image']) && (($_POST['delete_image']) == 1)) {
                    $query = sprintf("
                        UPDATE 
                            %1\$s 
                        SET 
                            image = '' 
                        WHERE 
                            id = '%2\$d'", 
                
                        TABLE_MAIN, 
                        $_GET['id']
                    );
                    $db->query($query);
                }
            
                // dodajemy zdjêcie do wpisu
                if(!empty($_FILES['file']['name'])) {
                
                    $up = new upload;
                    $upload_dir = "../photos";
			
                    // upload pliku na serwer.
                    $file = $up->upload_file($upload_dir, 'file', true, true, 0, "jpg|jpeg|gif");
                    if($file == false) {
				
				        echo $up->error;
                    } else {
			    
                        $query = sprintf("
                            UPDATE 
                                %1\$s 
                            SET 
                                image = '%2\$s' 
                            WHERE 
                                id = '%3\$d'", 
			    
                            TABLE_MAIN,
                            $file,
                            $_GET['id']
                        );
                
				        $db->query($query);
                    }
                }
            
                $ft->assign('CONFIRM', $i18n['edit_note'][0]);
                $ft->parse('ROWS',	".result_note");
                
            } else {
                $monit[] = $i18n['edit_note'][3];

                foreach ($monit as $error) {
    
                    $ft->assign('ERROR_MONIT', $error);
                    $ft->parse('ROWS',	".error_row");
                }
                $ft->parse('ROWS', "error_reporting");
            }
        } else {
            
        }              
		break;
		
	default:
        if (isset($_POST['selected_notes']) && is_array($_POST['selected_notes']))
        {
            if (isset($_POST['sub_status'])) {
                if($permarr['moderator']) {
                    $query = sprintf("
                        UPDATE 
                            %1\$s 
                        SET 
                            published = published * -1 
                        WHERE 
                            id IN (%2\$s)", 
        
                        TABLE_MAIN,
                        implode(',', $_POST['selected_notes'])
                    );

                    $db->query($query);

                    $ft->assign('CONFIRM', $i18n['confirm'][3]);
                    $ft->parse('ROWS', ".result_note");
                } else {
            
                    $monit[] = $i18n['edit_note'][2];

                    foreach ($monit as $error) {

                        $ft->assign('ERROR_MONIT', $error);
                    
                        $ft->parse('ROWS',	".error_row");
                    }
                        
                    $ft->parse('ROWS', "error_reporting");
                }
            } elseif (isset($_POST['sub_delete'])) {
                if($permarr['moderator']) {
                    $query = sprintf("
                        DELETE FROM 
                            %1\$s 
                        WHERE 
                            id IN (%2\$s)",
        
                        TABLE_MAIN,
                        implode(',', $_POST['selected_notes'])
                    );

                    $db->query($query);

                    $ft->assign('CONFIRM', $i18n['edit_note'][1]);
                    $ft->parse('ROWS', ".result_note");
                } else {
            
                    $monit[] = $i18n['edit_note'][2];

                    foreach ($monit as $error) {

                        $ft->assign('ERROR_MONIT', $error);
                    
                        $ft->parse('ROWS',	".error_row");
                    }
                        
                    $ft->parse('ROWS', "error_reporting");
                }
            } else {
                $default = true;
            }

        } else {
            $default = true;
        }



        if (isset($default) && $default) {
	
        }
}
*/
?>
