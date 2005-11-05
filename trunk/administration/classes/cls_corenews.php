<?php
// $Id$

class CoreNews extends CoreBase {
    
    var $news = array();

    /*
     * constructor
     */
    function CoreNews() {
        
        CoreBase::CoreBase();

        global $permarr;
        $this->permarr      = $permarr;
        $this->db_config    = $db_config =& new db_config;
    }

    
    function news_add() {
        
        if($this->is_error()) {
            return false;
        }
        
        if(count($_POST) == 0) {
            $this->error_set('CoreNews::NewsAdd:: $_POST is empty.');
            return false;
        }

        $title      = trim($_POST['title']);
        $timestamp  = isset($_POST['now']) ? time() : null;

        //sprawdzamy czy ma uprawnienia do dodawania newsow
        if(!$this->permarr['writer']) {
            
            $this->error_set('CoreNews::NewsAdd:: ' . $this->i18n['add_note'][2]);
        }
        
        //sprawdzamy czy format czasu jest prawid�owy
        if(is_null($timestamp)) {
            $regexp = '/
                ^
                ([0-9]{4}) #rok (index:1)
                -
                ([0-9]{2}) #miesiac (index:2)
                -
                ([0-9]{2}) #dzien (index:3)
                [ ]
                ([0-9]{2}) #godzina (index:4)
                :
                ([0-9]{2}) #minuta (index:5)
                :
                ([0-9]{2}) #sekunda (index:6)
                $
                /ix';

            // wlasciwy format czasu ?
            if(!preg_match($regexp, $_POST['date'], $date_match)) {
                $this->error_set('CoreNews::NewsAdd:: ' . $this->i18n['add_note'][5]);
            } else {
                $timestamp = mktime(
                    (int)$date_match[4],
                    (int)$date_match[5],
                    (int)$date_match[6],
                    (int)$date_match[2],
                    (int)$date_match[3],
                    (int)$date_match[1],
                    -1
                );
            }
        }
        
        // sprawdzamy czy news zosta� przypisany do jakichs kategorii
        if( !isset($_POST['assign2cat']) || 
            !is_array($_POST['assign2cat']) || 
            count($_POST['assign2cat']) == 0) {
                
            $this->error_set('CoreNews::NewsAdd:: news must be assigned to at least one category.');
        }

        if($this->is_error()) { 
            return false;
        }

        //jesli nie ma bledow, to dodajemy
        //ukladamy wlasciwa tablice
        $news_data = array(
            'id_cat'            =>$_POST['assign2cat'],
            'timestamp'         =>$timestamp,
            'title'             =>$title,
            'author'            =>$_POST['author'],
            'text'              =>parse_markers($_POST['text'], 1),
            'comments_allow'    =>$_POST['comments_allow'],
            'published'         =>$_POST['published'],
            'only_in_category'  =>$_POST['only_in_category']
        );

        //let's do it
        $news = new News();
        $news->set_from_array($news_data);
        $test = $news->commit();
        if(!$test) {
            $this->error_set($news->error_get());
            return false;
        }

        return true;
    }
    
    
    /**
     * @param $id_list
     */
    function news_remove($id_list) { // TODO !!!
    
        if(is_array($id_list)) {
            while(list($k, $id) = each($id_list)){
                if(!$this->news[$id]->remove()) {
                    $this->error_set($this->news[$id]->error_get());
                }
            }
        } else {
            if(!$this->news[$id_list]->remove()) {
                $this->error_set($this->news[$id_list]->error_get());
            }
        }

        return !$this->is_error();
    }
    
    
    function news_update() {
        
        if ($this->is_error()) {
            return false;
        }
        
        if(count($_POST) == 0) {
            $this->error_set('CoreNews::NewsUpdate:: $_POST is empty.');
            return false;
        }

        $title      = trim($_POST['title']);
        $timestamp  = isset($_POST['now']) ? time() : null;

        //sprawdzamy czy ma uprawnienia do dodawania newsow
        if(!$this->permarr['writer']) {
            $this->error_set('CoreNews::NewsUpdate:: ' . $this->i18n['update_note'][2]);
        }
        
        //sprawdzamy czy format czasu jest prawid�owy
        if(is_null($timestamp)){
            $regexp = '/
                ^
                ([0-9]{4}) #rok (index:1)
                -
                ([0-9]{2}) #miesiac (index:2)
                -
                ([0-9]{2}) #dzien (index:3)
                [ ]
                ([0-9]{2}) #godzina (index:4)
                :
                ([0-9]{2}) #minuta (index:5)
                :
                ([0-9]{2}) #sekunda (index:6)
                $
                /ix';

            // wlasciwy format czasu ?
            if(!preg_match($regexp, $_POST['date'], $date_match)) {
                $this->error_set('CoreNews::NewsUpdate:: ' . $this->i18n['update_note'][5]);
            } else {
                $timestamp = mktime(
                    (int)$date_match[4],
                    (int)$date_match[5],
                    (int)$date_match[6],
                    (int)$date_match[2],
                    (int)$date_match[3],
                    (int)$date_match[1],
                    -1
                );
            }
        }
        
        //sprawdzamy czy news zosta� przypisany do jakichs kategorii
        if( !isset($_POST['assign2cat']) || 
            !is_array($_POST['assign2cat']) || 
            count($_POST['assign2cat']) == 0) {
                
            $this->error_set('CoreNews::NewsUpdate:: news must be assigned to at least one category.');
        }

        if($this->is_error()) { 
            return false;
        }

        //jesli nie ma bledow, to dodajemy
        //ukladamy wlasciwa tablice
        $news_data = array(
            'id'                =>$_POST['id'],
            'id_cat'            =>$_POST['assign2cat'],
            'timestamp'         =>$timestamp,
            'title'             =>$title,
            'author'            =>$_POST['author'],
            'text'              =>parse_markers($_POST['text'], 1),
            'comments_allow'    =>(bool)$_POST['comments_allow'],
            'published'         =>(bool)$_POST['published'],
            'only_in_category'  =>(bool)$_POST['only_in_category']
        );

        //let's do it
        $news = new News();
        $news->set_from_array($news_data);
        $news->commit();
        
        if($news->is_error()) {
            $this->error_set($news->error_get());
            return false;
        }

        return true;
    }
    
    
    /**
     * @param $id_cat
     * @param $published
     * @param $comments_allow
     * @param $only_in_category
     * @param $start
     * @param $limit
     * @param $order
     */
    function news_list($id_cat, $published = true, $comments_allow = null,
            $only_in_category=null, $start = 0, $limit = -1, $order = 'asc')
    {
        //sprawdzamy poprawnosc argumentow
        if($start < 0 || ($start > 0 && $limit < 0)) {
            $this->error_set('CoreNews::NewsList:: incorrect values of $start and/or $limit.');
        }
        
        if(!in_array($order, array('asc', 'desc'))) {
            $this->error_set('CoreNews::NewsList:: incorrect value of $order - none of "asc" or "desc".');
        }
        
        if(!is_null($published)) {
            $published = (bool)$published;
        }
        
        if(!is_null($comments_allow)) {
            $ca = (bool)$ca;
        }
        
        if(!is_null($only_in_category)) {
            $oic = (bool)$oic;
        }

        if($this->is_error()) {
            return false;
        }

        //budowanie query
        $query = sprintf("
            SELECT
                id,
                UNIX_TIMESTAMP(date) AS timestamp,
                title,
                author,
                text,
                comments_allow,
                published,
                only_in_category
            FROM
                %s",

            TABLE_MAIN
        );
        $and = 'WHERE';

        //budowania query ciag dalszy: warunki
        //skracamy sprawdzanie po kolei warunkow if/elseif i wrzucamy
        //to w petle
        $keys = array('published', 'only_in_category', 'comments_allow');
        foreach($keys as $key) {
            
            $var = $$key;
            if($var === true) {
                $val = 1;
            } elseif($var === false) {
                $val = -1;
            }

            if(!is_null($var)) {
                $query .= sprintf("
                    %s
                        %s = %d",

                    $and,
                    $key,
                    $val
                );

                $and = 'AND';
            }
        }

        //budowanie query cd: LIMIT
        if($limit > -1) {
            $query .= sprintf("
                LIMIT %d, %d",

                $start,
                $limit
            );
        }

        $this->db->query($query);

        //poniewaz id kategorii do jakich zostal przydzielony wpis trzeba
        //pobrac w osobnym zapytaniu, rozbijamy to na 2 etapy: najpierw
        //pobranie do tablicy $entries zawartosci wpisow, pozniej
        //dopiero dodajemy, w nastepnej petli iteracyjnej, liste kategorii
        //dopiero na koniec tworzymy obiekt klasy News
        $entries = array();
        
        while($this->db->next_record()) {
            $id = $this->db->f('id');
            $entries[$id] = $this->db->get_record();
        }

        while(list($k,) = each($entries)) {
            
            $id_cat = array();
            $query = sprintf("
                SELECT
                    category_id
                FROM
                    %s
                WHERE
                    news_id = %d",
                
                TABLE_ASSIGN2CAT,
                $k
            );
            
            $this->db->query($query);
            
            while($this->db->next_record()) {
                $id_cat[] = $this->db->f('category_id');
            }
            
            $entries[$k]['id_cat'] = $id_cat;

            $entries[$k]['published']           = ($entries[$k]['published']        == 1);
            $entries[$k]['comments_allow']      = ($entries[$k]['comments_allow']   == 1);
            $entries[$k]['only_in_category']    = ($entries[$k]['only_in_category'] == 1);

            $this->news[$k] =& new News();
            $this->news[$k]->set_from_array($entries[$k]);
        }

        krsort($this->news);
        reset($this->news);

        return true;
    }
    
    
    /**
     * @param $id_news
     */
    function news_get($id_news) {
        $this->news[$id_news] =& new News($id_news);
        
        krsort($this->news, SORT_NUMERIC);
        reset($this->news);
        
        return true;
    }
    
    
    /**
     * @param $id_cat
     */
    function news_count($id_cat = null) {
        if(is_null($id_cat)) {
            $query = sprintf("
                SELECT
                    COUNT(D.id) AS count
                FROM
                    %s D
                LEFT JOIN
                    %s A
                ON  
                    A.news_id = D.id
                WHERE
                    A.category_id =1
                AND
                    D.published =1
                AND
                    D.only_in_category = -1",

               TABLE_MAIN,
               TABLE_ASSIGN2CAT
            );
        } elseif(is_numeric($id_cat) && $id_cat > 0) {
            $query = sprintf("
                SELECT
                    COUNT(id) AS count
                FROM
                    %s
                WHERE
                   category_id = %d",

               TABLE_ASSIGN2CAT,
               $id_cat
            );
        } else {
            $this->error_set('CoreNews::NewsCount:: incorrect category id.');
            return false;
        }

        $this->db->query($query);
        $this->db->next_record();
        
        return $this->db->f('count');
    }

    
    /**
     *
     */
    function cmnt_add() {
        return true;
    }
    
    
    /**
     * @param $id_list
     * @param type
     */
    function cmnt_remove($id_list, $type) {
        if(!in_array($type, array('news', 'comment'))) {
            return false;
        }
        
        if(!is_array($id_list)) {
            $id_list = array($id_list);
        }

        switch($type) {
            case 'news':
                foreach($id_list as $id) {
                    
                    $cmnt = new Comments();
                    $cmnt->delByNewsId($id);
                    
                    unset($cmnt);
                }
                break;

            case 'comment':
                foreach($id_list as $id) {
                    
                    $cmnt = new Comments($id);
                    $cmnt->del($id);
                    
                    unset($cmnt);
                }
                break;
        }

        return true;
    }
    
    
    /**
     * @param $id_cmnt
     */
    function cmnt_update($id_cmnt) {
        return true;
    }
    
    
    /**
     * @param $id_news
     * @param $order - comments sort order
     */
    function cmnt_list($id_news, $order) {
        
        if(!in_array($order, array('asc', 'desc'))) {
            $this->error_set('CoreNews::CmntList:: incorrect value of $order - none of "asc" or "desc".');
        }

        if($this->is_error()) {
            return false;
        }

        // building query
        $query = sprintf("
            SELECT 
                id,
                UNIX_TIMESTAMP(date) AS date,
                id_news,
                author,
                author_ip,
                email,
                text
            FROM
                %s 
            WHERE 
                id_news = %d 
            ORDER BY 
                date 
            %s",

            TABLE_COMMENTS, 
            $id_news, 
            $order
        );

        $this->db->query($query);

        $cmnt_entries = array();
        
        while($this->db->next_record()) {
            
            $id = $this->db->f('id');
            
            $cmnt_entries[$id]['id']         = $id;
            $cmnt_entries[$id]['date']       = $this->db->f('date');
            $cmnt_entries[$id]['id_news']    = $id_news;
            $cmnt_entries[$id]['author']     = $this->db->f('author');
            $cmnt_entries[$id]['author_ip']  = $this->db->f('author_ip');
            $cmnt_entries[$id]['email']      = $this->db->f('email');
            $cmnt_entries[$id]['text']       = $this->db->f('text');
            
            $this->comments[$id_news][$id_cmnt] =& new Comments();
            $this->comments[$id_news][$id_cmnt]->set_from_array($cmnt_entries[$id]);
        }

        krsort($this->comments);
        reset($this->comments);

        return true;
    }
    
    
    /**
     *
     */
    function cmnt_get() {
        $this->comments[$id_news] =& new Comments($id_news);
        
        krsort($this->comments, SORT_NUMERIC);
        reset($this->comments);
        
        return true;
    }
    
    
    /**
     * @param $id_news
     */
    function cmnt_count($id_news) {
        return false;
    }

    
    /**
     * @param $id_news
     */
    function is_news($id_news) {
        
        $news = new News($id_news);
        return $news->is();
    }
}

?>
