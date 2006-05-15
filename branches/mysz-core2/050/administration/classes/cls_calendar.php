<?php
// $Id: cls_calendar.php 1213 2005-11-05 13:03:06Z mysz $

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

class calendar {
    
    var $Days = array(
        'Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'So', 'N'
    );
    
    var $Months = array(
        'pl'    =>array( 
            'Styczeń', 
            'Luty', 
            'Marzec', 
            'Kwiecień', 
            'Maj', 
            'Czerwiec', 
            'Lipiec', 
            'Sierpień', 
            'Wrzesień', 
            'Październik', 
            'Listopad', 
            'Grudzień'
        ), 
        'en'    =>array( 
            'January', 
            'February', 
            'March', 
            'April', 
            'May', 
            'June', 
            'July', 
            'August', 
            'September', 
            'October', 
            'November', 
            'December'
        )
    );
    
    var $intYear;
    var $intMonth;
    var $intTime;
    var $intFirstDay;
    var $intDay = 1;
    
    function calendar() {
        
        $this->intYear      = date('Y');
        $this->intMonth     = date('m');
        $this->intTime      = mktime(0, 0, 0, $this->intMonth, 1, $this->intYear);
        $this->intFirstDay  = date('w', $this->intTime);
    }
    
    function display_calendar() {
        
        global 
            $ft, 
            $db, 
            $rewrite, 
            $assigned_tpl, 
            $lang, 
            $CoreRewrite;
        
        $ft->assign('LONGMONTHS', $this->Months[$lang][($m = date('n'))-1] . ', ' . ($y = date('Y')));
        
        $ft->define_dynamic("shortdays_row", $assigned_tpl);
        $ft->define_dynamic("days_row", $assigned_tpl);
        
        for($nr=0; $nr<7; $nr++) {
            
            $ft->assign('SHORTDAYS', $this->Days[$nr]);
            $ft->parse('SHORTDAYS_ROW', ".shortdays_row");
        }
        
        $query = sprintf("
            SELECT 
                title, UNIX_TIMESTAMP(date) as date 
            FROM 
                %1\$s 
            WHERE 
                MONTH(date) = '%2\$d'
            AND YEAR(date) = '%3\$d'", 
        
            TABLE_MAIN, 
            $this->intMonth, 
            $this->intYear
        );
        
        $date = array();

        $db->query($query);
        while($db->next_record()) {
        
            $date[] = date('d', $db->f('date'));
        }
        
        for($a=1; $a<=(date('t', $this->intTime)+$this->intFirstDay-1); $a++) {
            
            $ft->assign('TABLE_D', $a < $this->intFirstDay ? true : false);
            
            if($a >= $this->intFirstDay) {
                if($this->intDay == date('d')) {
                    
                    $ft->assign(array(
                        'LINKED'    =>false, 
                        'DAY'       =>$this->intDay, 
                        'DAYS_CLASS'=>'day_current'
                    ));
                }
                
                if(in_array($this->intDay, $date)) {
                    
                    $ft->assign(array(
                        'LINKED'    =>true, 
                        'DAY_LINKED'=>$CoreRewrite->current_date($rewrite, $this->intMonth, $this->intDay),
                        'DAY'       =>$this->intDay, 
                        'DAYS_CLASS'=>$this->intDay == date('d') ? 'day_current_hit' : 'day_hit'
                    ));
                } elseif($this->intDay != date('d')) {
                    
                    $ft->assign(array(
                        'LINKED'    =>false, 
                        'DAY'       =>$this->intDay, 
                        'DAYS_CLASS'=>'day'
                    ));
                }
                
                $this->intDay++;
            }
            
            $ft->assign('TABLE_R', $a%7 == 0 ? true : false);
            $ft->parse('DAYS_ROW', ".days_row");
        }
    }
    
}

?>
