<?php
// $Id$

class calendar {
    
    var $Days = array(
        'Pn', 'Wt', '¦r', 'Cz', 'Pt', 'So', 'N'
    );
    
    var $Months = array(
        'pl'    =>array( 
            'Styczeñ', 
            'Luty', 
            'Marzec', 
            'Kwiecieñ', 
            'Maj', 
            'Czerwiec', 
            'Lipiec', 
            'Sierpieñ', 
            'Wrzesieñ', 
            'Pa¼dziernik', 
            'Listopad', 
            'Grudzieñ'
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
            $lang;
        
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
            
            $datelink = (bool)$rewrite ? '<a href="1,'. $this->intMonth . '-' . $this->intDay . ',9,date.html">'.$this->intDay.'</a>' : '<a href="index.php?p=9&amp;date='. $this->intMonth . '-' . $this->intDay . '">'.$this->intDay.'</a>';
            
            if($a >= $this->intFirstDay) {
                if($this->intDay == date('d')) {
                    
                    $ft->assign(array(
                        'DAY'       =>$this->intDay, 
                        'DAYS_CLASS'=>'day_current'
                    ));
                }
                
                if(in_array($this->intDay, $date)) {
                    
                    $ft->assign(array(
                        'DAY'       =>$datelink, 
                        'DAYS_CLASS'=>$this->intDay == date('d') ? 'day_current_hit' : 'day_hit'
                    ));
                } elseif($this->intDay != date('d')) {
                    
                    $ft->assign(array(
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
