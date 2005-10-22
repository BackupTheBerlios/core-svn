<?php
// $Id: cls_view.php 1133 2005-08-03 23:42:59Z lark $

class view {
    
    var $db;
    
    /*
     * constructor
     */
    function view() {
        $this->db = new DB_Sql;
    }
    
    
    /**
     * @return reference
     */
    function &instance() {
        static $view;
        
        if(!isset($view)) {
            $view = new view;
        }
        
        return $view;
    }
    
    // example
    // $view = &view::instance();

}

?>