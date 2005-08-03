<?php
// $Id$

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
