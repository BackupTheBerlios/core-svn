<?php
// $Id: cls_errors.php 1213 2005-11-05 13:03:06Z mysz $

/*
 * This file is internal part of Core CMS (http://core-cms.com/) engine.
 *
 * Copyright (C) 2004-2005 Core Dev Team (more info: docs/AUTHORS).
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published
 * by the Free Software Foundation; version 2 only.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 */

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
