<?php
// $Id: cls_tree.php 1138 2005-08-06 18:29:53Z lark $

class News extends CoreBase {
    
    
    // deklaracje wlasciowsci
    var $id = null;
    var $id_cat = array(); // lista id kategorii do ktorych przynalezy news
    var $timestamp = 0;
    var $time = null;
    var $date = null;
    var $title = '';
    var $author = '';
    var $text = '';
    var $comments_allow = null;
    var $published = null;
    var $only_in_category = null;

    
    /**
     * @param $id - news id
     */
    function _id_check($id = null) {
        
        if(is_null($id)) {
            $id = $this->get_id();
        }

        if(!is_numeric($id) || $id < 0) {
            $this->error_set('News::IdCheck:: incorrect news ID.');
            return false;
        }
        
        return true;
    }

    
    /**
     * @param $id_news - news id
     */
    function News($id_news = null) {
        
        // konstruktor klasy bazowej
        CoreBase::CoreBase();

        if(!is_null($id_news) && $this->_id_check($id_news)) {
            
            $this->set_id($id_news);
            $data = $this->retrieve();
            
            $data['published']          = ($data['published']           == 1);
            $data['comments_allow']     = ($data['comments_allow']      == 1);
            $data['only_in_category']   = ($data['only_in_category']    == 1);
            
            $this->set_from_array($data);
        }

        return true;
    }

    
    /**
     * @param $id
     */
    function set_id($id) {
        
        if(!$this->_id_check($id)) {
            return false;
        }

        $this->id = (int)$id;
        return true;
    }
    
    
    /**
     * @param $id_cat
     */
    function set_id_cat($id_cat) {
        
        if(!is_array($id_cat)) {
            $id_cat = (array)$id_cat;
        }

        $this->id_cat = $id_cat;
        return true;
    }
    
    
    /**
     * @param $timestamp
     * @param $format - date format
     */
    function set_timestamp($timestamp, $format = 'H:i:s Y-m-d') {
        
        $this->timestamp = $timestamp;

        list($this->time, $this->date) = explode(' ', date($format, $timestamp));
        return true;
    }
    
    
    /**
     * @param $title - news title
     */
    function set_title($title) {
        
        $this->title = trim($title);
        return true;
    }
    
    
    /**
     * @param $author - news author
     */
    function set_author($author) {
        $this->author = trim($author);
        return true;
    }
    
    
    /**
     * @param $text - news text
     */
    function set_text($text) {
        $this->text = trim($text);
        return true;
    }
    
    
    /**
     * @param $data - define news comments status
     * @return boolean
     */
    function set_comments_allow($data) {
        $this->comments_allow = (bool)$data;
        return true;
    }
    
    
    /**
     * @param $data - define news published status
     * @return boolean
     */
    function set_published($data) {
        $this->published = (bool)$data;
        return true;
    }
    
    
    /**
     * @param $data - define news category status
     * @return boolean
     */
    function set_only_in_category($data) {
        $this->only_in_category = (bool)$data;
        return true;
    }

    /**
     * @param $array
     */
    function set_from_array($array) {
        
        if (!is_array($array)) {
            $this->error_set('News::SetFromArray:: incorrect input data (not an array).');
            return false;
        }

        $bad_prop = array();
        foreach($array as $prop => $value) {
            
            $test = $this->set_prop($prop, $value);
            
            if(!$test) {
                $bad_prop[] = $prop;
            }
        }

        if((bool)count($bad_prop)) {
            return $bad_prop;
        }

        return true;
    }
    
    
    /**
     * @param $prop
     * @param $val
     */
    function set_prop($prop, $val) {
        
        if(!method_exists($this, sprintf('set_%s', $prop))) {
            return false;
        }

        $method = 'set_' . $prop;
        $this->$method($val);
        
        return true;
    }

    
    /**
     * @return $id
     */
    function get_id() {
        return $this->id;
    }
    
    
    /**
     * @return $id_cat
     */
    function get_id_cat() {
        return $this->id_cat;
    }
    
    
    /**
     * @return $timestamp
     */
    function get_timestamp() {
        return $this->timestamp;
    }
    
    
    /**
     * @return $time
     */
    function get_time() {
        return $this->time;
    }
    
    
    /**
     * @return $date
     */
    function get_date() {
        return $this->date;
    }
    
    
    /**
     * @return $title
     */
    function get_title() {
        return $this->title;
    }
    
    
    /**
     * @return $author
     */
    function get_author() {
        return $this->author;
    }
    
    
    /**
     * @return $text
     */
    function get_text() {
        return $this->text;
    }
    
    
    /**
     * @return $comments_allow
     */
    function get_comments_allow() {
        return $this->comments_allow;
    }
    
    
    /**
     * @return $published
     */
    function get_published() {
        return $this->published;
    }
    
    
    /**
     * @return $only_in_category
     */
    function get_only_in_category() {
        return $this->only_in_category;
    }

    
    /**
     *
     */
    function switch_published() {
        $this->set_published(!$this->get_published());
        return true;
    }
    
    
    /**
     *
     */
    function switch_comments_allow() {
        $this->set_comments_allow(!$this->get_comments_allow());
        return true;
    }
    
    
    /**
     *
     */
    function switch_only_in_category() {
        $this->set_only_in_category(!$this->get_only_in_category());
        return true;
    }
    
    
    /**
     *
     */
    function retrieve() {
        
        $this->_id_check();
        
        if($this->is_error()) {
            return false;
        }

        $query = sprintf("
            SELECT
                id,
                UNIX_TIMESTAMP(date) AS timestamp,
                DATE_FORMAT(date, '%%T') AS time,
                DATE_FORMAT(date, '%%Y-%%m-%%d') AS date,
                title,
                author,
                text,
                comments_allow,
                published,
                only_in_category
            FROM
                %s
            WHERE
                id = %d",
            
            TABLE_MAIN,
            $this->get_id()
        );
        
        $this->db->query($query);
        
        if(!$this->db->next_record()) {
            $this->error_set(sprintf('News::Retrieve:: nonexistent news ID \'%s\'.', $this->get_id()));
            return false;
        }
        
        $entry = $this->db->get_record();

        // kategorie
        $id_cat = array();
        
        $query = sprintf("
            SELECT
                category_id
            FROM
                %s
            WHERE
                news_id = %d",
            
            TABLE_ASSIGN2CAT,
            $this->get_id()
        );
        
        $this->db->query($query);
        
        while($this->db->next_record()) {
            $id_cat[] = $this->db->f('category_id');
        }
        
        $entry['id_cat'] = $id_cat;

        $entry['published']         = ($entry['published']          == 1);
        $entry['only_in_category']  = ($entry['only_in_category']   == 1);
        $entry['comments_allow']    = ($entry['comments_allow']     == 1);

        return $entry;
    }
    
    
    /**
     *
     */
    function commit() {
        
        // poczatkowe sprawdzanie poprawnosci danych
        if($this->is_error()) {
            return false;
        }
        
        $id = $this->get_id();
        
        if(!is_null($id)) {
            $this->_id_check();
        }

        // kontynuujemy sprawdzanie poprawnosci danych
        $title      = $this->get_title();
        $timestamp  = $this->get_timestamp();
        $id_cat     = $this->get_id_cat();
        
        if(strlen($title) == 0) {
            $this->error_set('News::Commit:: `Title` cannot be empty.');
        }
        
        if($timestamp <= 0) {
            $this->error_set('News::Commit:: incorrect timestamp.');
        }
        
        if(!is_array($id_cat) || count($id_cat) == 0) {
            $this->error_set('News::Commit:: assign news to some category.');
        }
        
        if($this->is_error()) return false;
        


        // konstruujemy zapytanie sql
        // typ zpaytania
        if(is_null($id)) {
            $query = "
                INSERT INTO";
        } else {
            $query = "
                UPDATE";
        }

        // tabela i dane
        $query .= sprintf("
                    %s
                SET
                    date = FROM_UNIXTIME(%d),
                    title = '%s',
                    author = '%s',
                    text = '%s',
                    comments_allow = %d,
                    published = %d,
                    only_in_category = %d",
        
            TABLE_MAIN,
            $this->get_timestamp(),
            $this->get_title(),
            $this->get_author(),
            $this->get_text(),
            $this->get_comments_allow() ? 1 : -1,
            $this->get_published() ? 1 : -1,
            $this->get_only_in_category() ? 1 : -1
        );
        
        // jesli trzeba (UPDATE), to klauzula WHERE
        if(!is_null($id)) {
            $query .= sprintf("
                WHERE
                    id = %d",
                
                $id
            );
        }

        // v_array($query, 1);
        $this->db->query($query);

        if(is_null($id)) {
            $this->set_id(mysql_insert_id($this->db->link_id()));
        }

        // przypisujemy do wlasciwych kategorii
        $this->_assign2cat();

        return true;
    }
    
    
    /**
     * removes a note
     */
    function remove() {
        
        $this->_id_check();
        
        if($this->is_error()) {
            return false;
        }

        // usuwamy przypisania do kategorii
        if (!$this->_remove_from_cat()) return false;

        // usuwamy sam wpis
        $query = sprintf("
            DELETE FROM
                %s
            WHERE
                id  = %d",

            TABLE_MAIN,
            $this->get_id()
        );
        
        $this->db->query($query);

        return true;
    }

    
    /**
     * @param id_news
     * @return boolean
     */
    function is($id_news = null) {
        
        if(!is_null($id_news)) {
            $id_news = $this->get_id();
        }
        
        if(!$this->_id_check($id_news)) {
            return false;
        }

        $query = sprintf("
            SELECT
                COUNT(id) AS count
            FROM
                %s
            WHERE
                id = %d",
            
            TABLE_MAIN,
            $id_news
        );
        
        $this->db->query($query);
        $this->db->next_record();
        
        return (bool)$this->db->f('count');
    }

    
    /**
     *
     */
    function _assign2cat() {
        
        if(!$this->_id_check()) {
            return false;
        }
        
        $id_cat = $this->get_id_cat();
        $id     = $this->get_id();

        if(!count($id_cat)) {
            $this->error_set('News::_Assign2Cat:: isn\'t assigned to any category.');
        }
        
        if($this->is_error()) {
            return false;
        }

        // na wszelki wypadek czyscimy przypisania newsa do kategorii
        $this->_remove_from_cat();

        // przypisujemy newsa do wlasciwych kategorii
        // konstruujemy query
        $query = sprintf("
            INSERT INTO
                %1\$s
            VALUES",

            TABLE_ASSIGN2CAT
        );

        $values = array();
        
        foreach($id_cat as $selected_cat) {
            $values[] = sprintf("
                (null, %1\$d, %2\$d)", 

                $id,
                $selected_cat
            );
        }
        $query .= implode(',', $values);

        $this->db->query($query);

        return true;
    }
    
    
    /**
     *
     */
    function _remove_from_cat() {
        
        $this->_id_check();
        
        if($this->is_error()) {
            return false;
        }

        $query = sprintf("
            DELETE FROM
                %1\$s
            WHERE
                news_id = %2\$d",

            TABLE_ASSIGN2CAT,
            $this->get_id()
        );
        $this->db->query($query);

        return true;
    }
}

?>
