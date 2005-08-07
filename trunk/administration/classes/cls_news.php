<?php
// $Id: cls_tree.php 1138 2005-08-06 18:29:53Z lark $

class news {
    
    var $view;
    
    /**
     * Constructor
     */
    function news() {
        $this->view =& view::instance();
    }
    
    /**
     * Split news text
     * @param $text - news text
     * @return splited text
     */
    function show_me_more($text) {
    
        global 
            $rewrite, $id, $i18n;
            
        if($find = strpos($text, '[podziel]') OR $find = strpos($text, '[more]')) {
            
            $text = sprintf(
                '%s<br /><a href="' . SITE_ROOT . '/%s">%s</a>',
            
                substr($text, 0, $find),
                perma_link($rewrite, $id),
                $i18n['main_view'][2]
            );
	   }
	
	   return $text;
    }
    
    
    /**
     * Prepare comments link
     * @param $comments_allow - integer
     * @param $comments - number of comments
     * @param $id - news ID
     * @return comments link
     */
    function get_comments_link($comments_allow, $comments, $id) {
    
        global 
            $ft, 
            $rewrite;
    
        if(($comments_allow) == 0 ) {
            $ft->assign(array(
                'COMMENTS_ALLOW'    =>false, 
                'COMMENTS'          =>''
            ));
        } else {
            if($comments == 0) {
                $ft->assign(array(
                    'COMMENTS_LINK' =>addcomments_link($rewrite, $id), 
                    'COMMENTS_ALLOW'=>true, 
                    'COMMENTS'      =>''
                ));
            } else {
                $ft->assign(array(
                    'COMMENTS_LINK' =>showcomments_link($rewrite, $id), 
                    'COMMENTS_ALLOW'=>true, 
                    'COMMENTS'      =>$comments
                ));
            }
        }
    }
    
    
    /**
     * Prepare image
     * @param $image - image name
     * @param $id - news ID
     * @return image (if exist)
     */
    function get_image_status($image, $id) {
    
        global 
            $ft, 
            $max_photo_width, 
            $rewrite;
    
        if(empty($image)) {
            
            // IFDEF: IMAGE_EXIST return empty value, move to ELSE condition
            $ft->assign(array(
                'IMAGE'         =>'', 
                'IMAGE_EXIST'   =>false, 
                'IMAGE_NAME'    =>false
            ));
        } else {
        
            $img_path = get_root() . '/photos/' . $image;
        
            if(is_file($img_path)) {
            
                list($width, $height) = getimagesize($img_path);
            
                // width, height of picture
                $ft->assign(array(
                    'WIDTH'         =>$width,
                    'HEIGHT'        =>$height,
                    'PHOTO_LINK'    =>photo_link($rewrite, $id)
                ));
            
                if($width > $max_photo_width) {
                
                    $ft->assign(array(
                        'UID'           =>$id,
                        'IMAGE_NAME'    =>''
                    ));
                } else {
                    $ft->assign('IMAGE_NAME', $image);
                }
            
                $ft->assign('IMAGE_EXIST', true);
            } else {
            
                $ft->assign(array(
                    'IMAGE_EXIST'   =>false, 
                    'IMAGE_NAME'    =>false
                ));
            }
        }
    }
    
    
    /**
     * Prepare list of assigned categories
     * @param $id - news ID
     * @return categories list
     */
    function list_assigned_categories($id) {
    
        global 
            $ft, 
            $rewrite;
    
        $query = sprintf("
            SELECT 
                a.*, b.* 
            FROM 
                %1\$s a 
            LEFT JOIN 
                %2\$s b 
            ON 
                a.category_id = b.category_id 
            WHERE 
                a.news_id = '%3\$d'", 
	    
            TABLE_ASSIGN2CAT, 
            TABLE_CATEGORY, 
            $id
        );
	    
        $this->view->db->query($query);
    
        $count_cats = $this->view->db->nf();
        $idx = 1;
    
        while($this->view->db->next_record()) {
        
            $cname = replace_amp($this->view->db->f('category_name'));
            $cid   = $this->view->db->f('category_id');
        
            $ft->assign(array(
                'CATEGORY_NAME' =>$cname, 
                'CATEGORY_LINK' =>category_link($rewrite, $cid), 
                'COMMA'         =>$count_cats == $idx ? '' : ', '
            ));
        
            $ft->parse('CAT_ROW', ".cat_row");
        
            $idx++;
        }
    
        // CAT_ROW must be cleared
        $ft->clear_parse('CAT_ROW');
    }
    
}

?>