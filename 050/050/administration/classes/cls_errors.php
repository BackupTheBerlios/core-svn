<?php
// $Id: cls_errors.php 1128 2005-08-03 22:16:55Z mysz $

class errors {
    
    var $error;
    
    /**
     * Constructor
     * Initialize variable
     */
    function errors() {
        
        //$this->monit = $monit;
    }
    
    
    /**
     * Parse template with error monits
     * @param $monit - errors array
     */
    function parse_errors($monit) {
        
        global $ft;
        
        foreach ($monit as $this->error) {
            
            $ft->assign('ERROR_MONIT', $this->error);
            $ft->parse('ROWS', ".error_row");
        }
        
        $ft->parse('ROWS', "error_reporting");
    }
}

?>
