<?php
// $Id$

class xml_feed {
    
    var $db;
    var $ft;
    var $rewrite;
    var $pattern        = array("&", "<br />", "<", ">");
    var $replacement    = array(" &amp; ", "&lt;br /&gt;", "&lt;", "&gt;");
    var $http_root;
    
    
    /**
     * Constructor
     */
    function xml_feed() {
        
        $this->db =& new DB_SQL;
        $this->rewrite = get_config('mod_rewrite');
        $this->http_root = get_httproot();
    }
    
    
    /**
     * Convert date to valid xml format
     * @param $date - date value
     * @return $date - converted
     */
    function date_convert($date) {
	
        $newdate = explode(' ', $date);
        $date_ex = explode('-', $newdate[0]);
	
        $months = array(
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Aug',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec'
        );
        
        $date_ex[1] = $months[$date_ex[1]];
        $date		= $date_ex[2] . " " . $date_ex[1] . " " . $date_ex[0] . " " . $newdate[1];
        
        return $date;
    }
    
    /**
     * Parse template contains xml_news_feed
     * @return parsed template
     */
    function parse_news_feed() {
        
        global $ft;
        
        $query  = sprintf("
            SELECT 
                a.*, b.*, c.comments_id, count(DISTINCT c.id) 
            AS 
                comments 
            FROM 
                %1\$s a, 
                %2\$s b 
            LEFT JOIN 
                %3\$s c 
            ON 
                a.id = c.comments_id 
            LEFT JOIN 
                %4\$s d 
            ON 
                a.id = d.news_id
            WHERE 
                published = '1' 
            GROUP BY 
                a.date 
            DESC 
            LIMIT 
                %5\$d", 

            TABLE_MAIN, 
            TABLE_CATEGORY, 
            TABLE_COMMENTS, 
            TABLE_ASSIGN2CAT, 
            10
        );

        $this->db->query($query);
        
        $ft->define('xml_feed', 'xml_feed.tpl');
        $ft->define_dynamic('xml_row', 'xml_feed');
        $ft->define_dynamic("cat_row", "xml_feed");

        $ft->assign(array(
            'MAINSITE_LINK' =>'http://' . $this->http_root,
            'NEWS_FEED'     =>true
        ));
        
        if($this->db->num_rows() > 0) {
            while($this->db->next_record()) {
	
                $date           = $this->db->f("date");
                $title          = $this->db->f("title");
                $text           = $this->db->f("text");
                $author         = $this->db->f("author");
                $id             = $this->db->f("id");
                $image          = $this->db->f("image");
                $comments_allow = $this->db->f("comments_allow");
                $comments       = $this->db->f("comments");
	           
                $date = $this->date_convert($date);
                
                $text = str_replace($this->pattern, $this->replacement, $text);
    
                list_assigned_categories($id);
    
                if((bool)$this->rewrite) {
            
                    $comments_link  = sprintf('%s1,%s,2,item.html', $this->http_root, $id);
                    $permanent_link = sprintf('%s1,%s,1,item.html', $this->http_root, $id);
                } else {

                    $comments_link  = sprintf('%sindex.php?p=2&amp;id=%s', $this->http_root, $id);
                    $permanent_link = sprintf('%sindex.php?p=1&amp;id=%s', $this->http_root, $id);
                }
   
                $ft->assign(array(
                    'DATE'          =>$date, 
                    'TITLE'         =>$title, 
                    'AUTHOR'        =>$author, 
                    'PERMALINK'     =>$permanent_link, 
                    'TEXT'          =>$text, 
                    'COMMENTS_LINK' =>$comments_link, 
                    'DISPLAY_XML'   =>true
                ));
    
                $ft->parse('XML_ROW', ".xml_row");
            }
        } else {
    
            $ft->assign('DISPLAY_XML', false);
            $ft->parse('XML_ROW', ".xml_row");
        }

        $this->display();
    }
    
    
    /**
     * Parse template contains xml_comments_feed
     * @return parsed template
     */
    function parse_comments_feed() {
        
        global $ft;
        
        $query  = sprintf("
            SELECT 
                b.*, a.id, a.title 
            FROM 
                %1\$s b 
            LEFT JOIN 
                %2\$s a 
            ON 
                b.comments_id = a.id 
            GROUP BY 
                date 
            DESC 
            LIMIT 
                %3\$d", 

            TABLE_COMMENTS,
            TABLE_MAIN,
            10
        );

        $this->db->query($query);
        
        $ft->define('xml_feed', 'xml_feed.tpl');
        $ft->define_dynamic('xml_row', 'xml_feed');

        $ft->assign(array(
            'MAINSITE_LINK' =>'http://' . $this->http_root,
            'NEWS_FEED'     =>false
        ));
        
        if($this->db->num_rows() > 0) {
            while($this->db->next_record()) {
	
                $date           = $this->db->f("date");
                $title          = $this->db->f("title");
                $text           = $this->db->f("text");
                $author         = $this->db->f("author");
                $id             = $this->db->f("id");
                $image          = $this->db->f("image");
                $comments_allow = $this->db->f("comments_allow");
                $comments       = $this->db->f("comments");
                
                $date           = $this->date_convert($date);
                
                $text = str_replace($this->pattern, $this->replacement, $text);

                $permanent_link = (bool)$this->rewrite ? $this->http_root . '1,' . $id . ',1,item.html' : $this->http_root . 'index.php?p=1&amp;id=' . $id . '';
                
                $ft->assign(array(
                    'DATE'          =>$date, 
                    'TITLE'         =>$title, 
                    'AUTHOR'        =>$author, 
                    'PERMALINK'     =>$permanent_link, 
                    'TEXT'          =>$text, 
                    'DISPLAY_XML'   =>true
                ));
    
                $ft->parse('XML_ROW', ".xml_row");
            }
        } else {
    
            $ft->assign('DISPLAY_XML', false);
            $ft->parse('XML_ROW', ".xml_row");
        }

        $this->display();
    }
    
    
    /**
     * displays parsed template
     * @return template
     */
    function display() {
        
        global $ft;
        
        $ft->parse('CONTENT', "xml_feed");
        $ft->FastPrint('CONTENT');
    }
    
}

?>
