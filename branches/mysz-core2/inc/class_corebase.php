<?php
// $Id$

require_once 'core_exceptions.php';

abstract class CoreBase {
    
    protected $errors = array();
    
    public function is_error()
    {
        return (bool)count($this->errors);
    }
    
    protected function error_set($msg, $code)
    {
        $errors[] = array($code, $msg);
    }
    
    public function error_get($last = true)
    {
        if($last) {
            return end($this->errors);
        }
        return $this->errors;
    }
    
    public function error_clear()
    {
        $this->errors = array();
    }
}

// vim: expandtab:shiftwidth=4:softtabstop=4:tabstop=4
?>

