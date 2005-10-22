<?php
// $Id: cls_links.php 1128 2005-08-03 22:16:55Z mysz $

class links {
    
    var $ID;
    var $db;
    var $sql;
    var $link_name;
    var $link_url;
    var $errors;
    
    var $confirm;
    var $post_id;
    
    var $selected_links;
    
    /**
     * Constructor - PHP4
     * Initialize variables
     */
    function links() {
        
        $this->ID = !empty($_GET['id']) ? $_GET['id'] : $this->ID;
        $this->db =& new DB_SQL;
        
        $this->errors   = &new errors();
    }
    
    
    /**
     * Initialize data - template parse
     * @param $ID - request link ID($_GET)
     * @return - parsed template
     */
    function show($ID) {
        
        global $ft;
        
        $query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                id = '%2\$d'", 
		
            TABLE_LINKS, 
            $this->ID
        );
        
        $this->db->query($query);
		$this->db->next_record();
		
		$link_id	= $this->db->f("id");
		$link_name	= $this->db->f("title");
		$link_url	= $this->db->f("url");
        
        $ft->assign(array(
            'LINK_ID'   =>$link_id,
            'LINK_NAME' =>$link_name,
            'LINK_URL'  =>$link_url
		));

		$ft->define('form_linkedit', "form_linkedit.tpl");
		$ft->parse('ROWS',	".form_linkedit");
    }
    
    
    /**
     * Update current link
     * @param $ID - request link ID($_GET)
     */
    function edit($ID) {
        
        global $monit, $i18n, $ft, $permarr;
        
        $this->link_name    = $_POST['link_name'];
        $this->link_url     = $_POST['link_url'];
        
        if($permarr['moderator']) {
            if ( !preg_match('#^(https?|ftp)://#i', $this->link_url) ) {

                $this->link_url = 'http://' . $this->link_url;
            }

            $monit = array();
	
            // Obs³uga formularza, jesli go zatwierdzono
            if(strlen($this->link_name) <= 2) {
                
                $monit[] = $i18n['edit_links'][2];
            }
            
            /*
             * TODO:
             * czy na pewno tak ? do msie jest plugin rozszerzajacy o 
             * linki: gg:IDGADUGADU
             * niedlugo do jabbera prawdopodobnie wejdzie protokol xmpp:
             * blokujesz takze mailto:
             * teraz nie pozwalamy na takie linki - dlaczego ?
             * nie sprawdzac niczego, poza tym czy cos w ogole jest wpisane.
             * jesli jest, to przechodzi
             *
             * zostawiam dla Twoich przemyslen :)
             *
             * poza tym - staraj sie uzywac wyrazen regularnych preg_*,
             * zamiast ereg*. sprobuj potestowac wydajbnosc jednych i drugich,
             * to zrozumiesz dlaczego :)
             *
             */
            if(!eregi("^(ftp|https?)://([-a-z0-9]+\.)+([a-z]{2,})$", $this->link_url)) {
                
                $monit[] = $i18n['edit_links'][3];
            }
		
            if(empty($monit)) {
            
                $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        title	= '%2\$s', 
                        url		= '%3\$s' 
                    WHERE 
                        id = '%4\$d'", 
			
                    TABLE_LINKS, 
                    $this->link_name, 
                    $this->link_url, 
                    $this->ID
                );
			
                $this->db->query($query);
		
                $ft->assign('CONFIRM', $i18n['edit_links'][4]);
                $ft->parse('ROWS',	".result_note");
            } else {

                $this->errors->parse_errors($monit);
            }
        } else {
            
            $monit[] = $i18n['edit_links'][7];

            $this->errors->parse_errors($monit);
        }
    }
    
    
    /**
     * Remark links order
     * @param $ID - request link ID($_GET)
     */
    function remark($ID) {
        
        global $ft, $permarr, $i18n, $monit;
        
        if($permarr['moderator']) {
            
            $move = intval($_GET['move']);
	
            $query = sprintf("
                UPDATE 
                    %1\$s 
                SET 
                    link_order = link_order + '%2\$d' 
                WHERE 
                    id = '%3\$d'", 
		
                TABLE_LINKS, 
                $move, 
                $this->ID
            );
		
            $this->db->query($query);
            
            $query = sprintf("
                SELECT * FROM 
                    %1\$s 
                ORDER BY 
                    link_order 
                ASC", 
    
                TABLE_LINKS
            );
    
            $this->db->query($query);
    
            $i = 10;
            $inc = 10;
            
            // instancja potrzebna
            $this->sql =& new DB_SQL;
    
            while($this->db->next_record()) {
        
                $lid = $this->db->f("id");
        
                $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        link_order = '$i' 
                    WHERE 
                        id = '$lid'", 
        
                    TABLE_LINKS
                );
                    
                $this->sql->query($query);
                    
                $i += 10;
            }
            
            header("Location: main.php?p=12");
            exit;
		
        } else {
            
            $monit[] = $i18n['edit_category'][6];
            
            $this->errors->parse_errors($monit);
        }
    }
    
    
    /**
     * Delete selected link
     */
    function delete() {
        
        global $ft, $permarr, $i18n, $monit, $p;
        
        $this->confirm  = empty($_POST['confirm']) ? '' : $_POST['confirm'];
        
        switch($this->confirm) {
            
            case $i18n['confirm'][0]:
	
                if($permarr['moderator']) {
                    $this->post_id = empty($_POST['post_id']) ? '' : $_POST['post_id'];
	
                    $query = sprintf("
                        DELETE FROM 
                            %1\$s 
                        WHERE 
                            id = '%2\$d'", 
		
                        TABLE_LINKS, 
                        $this->post_id
                    );
		
                    $this->db->query($query);
		
                    $ft->assign('CONFIRM', $i18n['edit_links'][5]);
                    $ft->parse('ROWS', ".result_note");
                } else {
            
                    $monit[] = $i18n['edit_links'][6];
            
                    $this->errors->parse_errors($monit);
                }
            break;
                                    
        case $i18n['confirm'][1]:
        
            header("Location: main.php?p=12");
            exit;
            break;
            
        default:
        
            $ft->define('confirm_action', 'confirm_action.tpl');
            $ft->assign(array(
                'PAGE_NUMBER'   =>$p, 
                'POST_ID'       =>$this->ID, 
                'CONFIRM_YES'   =>$i18n['confirm'][0],
                'CONFIRM_NO'    =>$i18n['confirm'][1]
            ));
            
            $ft->parse('ROWS', ".confirm_action");
            break;
        }
    }
    
    
    /**
     * Multidelete selected links
     */
    function multidelete() {
        
        global $ft, $permarr, $i18n, $monit;
        
        if($permarr['moderator']) {
            
            $this->selected_links = empty($_POST['selected_links']) ? '' : $_POST['selected_links'];
            
            if(!empty($this->selected_links)) {
                
                $query = sprintf("
                    DELETE FROM 
                        %1\$s 
                    WHERE 
                        id 
                    IN(".implode(',', $this->selected_links).")", 
		
                    TABLE_LINKS
                );
		
                $this->db->query($query);
                $ft->assign('CONFIRM', 'Linki zosta³y usuniête.');
            } else {
                $ft->assign('CONFIRM', 'Nie zaznaczono ¿adnych linków.');
                
            }
            
            $ft->parse('ROWS', ".result_note");
        } else {
            
            $monit[] = $i18n['edit_note'][2];

            $this->errors->parse_errors($monit);
        }
    }
    
    
    /**
     * List links
     * @return parsed template
     */
    function list_links() {
        
        global $ft, $i18n, $monit;
        
        $query = sprintf("
            SELECT 
                MIN(link_order) as min_order, 
                MAX(link_order) as max_order 
            FROM 
                %1\$s",
        
            TABLE_LINKS
        );
            
        $this->db->query($query);
        $this->db->next_record();
			
        // Przypisanie zmiennej $id
        $max_order = $this->db->f("max_order");
        $min_order = $this->db->f("min_order");
	
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            ORDER BY 
                link_order 
            ASC", 
		
            TABLE_LINKS
        );
		
		$this->db->query($query);
	
		// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
		if($this->db->num_rows() > 0) {
		
		    // Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
            while($this->db->next_record()) {
		
                $link_id	= $this->db->f("id");
                $link_order = $this->db->f("link_order");
                $link_name	= $this->db->f("title");
                $link_url	= $this->db->f("url");
                
                $link_url = strlen($link_url) > 30 ? substr_replace($link_url, '...', 30) : $link_url;
			
                $ft->assign(array(
                    'LINK_ID'	=>$link_id,
                    'LINK_NAME'	=>$link_name,
                    'LINK_URL'	=>$link_url
                ));
                
                if($link_order == $max_order) {
                    
                    $ft->assign(array(
                        'REORDER_DOWN'  =>false, 
                        'REORDER_UP'    =>true
                    ));
                } elseif ($link_order == $min_order) {
                    
                    $ft->assign(array(
                        'REORDER_DOWN'  =>true, 
                        'REORDER_UP'    =>false
                    ));
                } else {

                    $ft->assign(array(
                        'REORDER_DOWN'  =>true, 
                        'REORDER_UP'    =>true
                    ));
                }		
			
                // deklaracja zmiennej $idx1::color switcher
                $idx1 = empty($idx1) ? '' : $idx1;
			
                $idx1++;
			
                $ft->define("editlist_links", "editlist_links.tpl");
                $ft->define_dynamic("row", "editlist_links");
			
                // naprzemienne kolorowanie wierszy tabeli
				$ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');
				
				$ft->parse('ROW', ".row");
            }
            $ft->parse('ROWS', "editlist_links");
		} else {
		    
		    $ft->assign('CONFIRM', $i18n['edit_links'][8]);
			$ft->parse('ROWS',	".result_note");
		}
    }
}

?>
