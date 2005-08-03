<?php
// $Id$

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
