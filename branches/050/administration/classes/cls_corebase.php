<?php

class CoreBase {
    
    var $error = array();
    var $debug = false;

    /*
     * constructor
     */
    function CoreBase() {
        
        global $i18n;

        $view       =& view::instance();
        $this->db   =& $view->db;
        $this->i18n =& $i18n;
    }
    

    /**
     * @param $msg
     * @return error array
     */
    function error_set($msg) {

        is_array($msg) ? $this->error = array_merge($this->error, $msg) : $this->error[] = (string)$msg;
        return true;
    }
    
    /**
     * @param $last - error msg
     * @return array
     */
    function error_get($last = false) {
        if((bool)count($this->error)) {
            if($last) {
                return end($this->error);
            }
            return $this->error;
        }
        return array();
    }
    
    /**
     * @return array
     */
    function error_clear() {
        $this->error = array();
        return true;
    }
    
    
    /**
     * @return number of errors
     */
    function is_error() {
        return (bool)count($this->error);
    }
}

?>