<?php
// $Id: cls_tree.php 1128 2005-08-03 22:16:55Z mysz $

class tree {
    
    var $view;
    
    /**
     * Constructor
     */
    function tree() {
        $this->view =& view::instance();
    }
    
    /**
     * Pages category
     * @param $page_id - page ID
     * @param $level - indent level
     * @return parsed pages tree
     */
    function get_cat($page_id, $level) {
        
        global $ft, $rewrite;
        
        $query = sprintf("
            SELECT 
                id, 
                parent_id, 
                title 
            FROM 
                %1\$s 
            WHERE 
                parent_id = '%2\$d' 
            AND 
                published = 'Y' 
            ORDER BY 
                id 
            ASC", 
	
            TABLE_PAGES, 
            $page_id
        );
        
        $this->view->db->query($query);
        
        while($this->view->db->next_record()) {
            
            $page_id 	= $this->view->db->f("id");
            $parent_id 	= $this->view->db->f("parent_id");
            $page_name 	= $this->view->db->f("title");
            $page_link  = (bool)$rewrite ? '1,' . $page_id . ',5,item.html' : 'index.php?p=5&amp;id=' . $page_id . '';
            
            $ft->assign(array(
                'PAGE_NAME' =>$page_name,
                'PAGE_ID'   =>$page_id,
                'CLASS'     =>"child",
                'PARENT'    =>str_repeat('&nbsp; ', $level), 
                'PAGE_LINK' =>$page_link
            ));
            
            $ft->parse('PAGES_ROW', ".pages_row");
            $this->get_cat($page_id, $level+2);
        }
    }
    
    
    /**
     * Subpages category
     * @param $subpage_id - page ID
     * @param $level - indent level
     * @return parsed subpages tree
     */
    function get_subpage_cat($subpage_id, $level) {
        
        global $ft, $rewrite;
        
        $query = sprintf("
            SELECT 
                id, 
                parent_id, 
                title 
            FROM 
                %1\$s 
            WHERE 
                parent_id = '%2\$d' 
            AND 
                published = 'Y' 
            ORDER BY 
                id 
            ASC", 
	
            TABLE_PAGES, 
            $subpage_id
        );
        
        $this->view->db->query($query);
        
        while($this->view->db->next_record()) {
            
            $subpage_id     = $this->view->db->f("id");
            $subparent_id   = $this->view->db->f("parent_id");
            $subpage_name   = $this->view->db->f("title");
            $subpage_link   = (bool)$rewrite ? '1,' . $subpage_id . ',5,item.html' : 'index.php?p=5&amp;id=' . $subpage_id . '';
            
            $ft->assign(array(
                'SUBPAGE_NAME'  =>$subpage_name,
                'SUBPAGE_ID'    =>$subpage_id,
                'CLASS'         =>"child",
                'PARENT'        =>str_repeat('&nbsp; ', $level), 
                'SUBPAGE_LINK'  =>$subpage_link
            ));
            
            $ft->parse('SUBPAGES_ROW', ".subpages_row");
            $this->get_subpage_cat($subpage_id, $level+2);
        }
    }
    
    
    /**
     * News category
     * @param $cat_id - category ID
     * @param $level - indent level
     * @return parsed news category tree
     */
    function get_category_cat($cat_id, $level) {
        
        global $ft, $rewrite;
        
        $query = sprintf("
            SELECT 
                category_id, 
                category_parent_id, 
                category_name 
            FROM 
                %1\$s 
            WHERE 
                category_parent_id = '%2\$d' 
            ORDER BY 
                category_id 
            ASC", 
	
            TABLE_CATEGORY, 
            $cat_id
        );
        
        $this->view->db->query($query);
        
        while($this->view->db->next_record()) {
            
            $cat_id           = $this->view->db->f("category_id");
            $cat_parent_id    = $this->view->db->f("category_parent_id");
            $cat_name         = $this->view->db->f("category_name");
            $cat_link         = (bool)$rewrite ? '1,' . $cat_id . ',4,item.html' : 'index.php?p=4&amp;id=' . $cat_id;
            
            $ft->assign(array(
                'CAT_NAME'  =>$cat_name,
                'NEWS_CAT'  =>$cat_id,
                'CLASS'     =>"cat_child",
                'PARENT'    =>str_repeat('&nbsp; ', $level), 
                'CAT_LINK'  =>$cat_link
            ));
            
            $ft->parse('CATEGORY_ROW', ".category_row");
            $this->get_category_cat($cat_id, $level+2);
        }
    }
    
    
    /**
     * Breadcrumb pages navigation
     * @param $page_id - current page ID
     * @param $level - indent level
     * @return parsed breadcrumb naviagtion template
     */
    function get_breadcrumb($page_id, $level) {
        
        global $ft, $rewrite, $pages_sort, $pages_id;
        
        $query = sprintf("
            SELECT 
                id, 
                parent_id, 
                title 
            FROM 
                %1\$s 
            WHERE 
                id = '%2\$d' 
            AND 
                published = 'Y' 
            ORDER BY 
                id 
            ASC", 
	
            TABLE_PAGES, 
            $page_id
        );
        
        $this->view->db->query($query);
        
        while($this->view->db->next_record()) {
	
            $page_id    = $this->view->db->f("id");
            $parent_id 	= $this->view->db->f("parent_id");
            $page_name 	= $this->view->db->f("title");
            $page_link  = (bool)$rewrite ? '1,' . $page_id . ',5,item.html' : 'index.php?p=5&amp;id=' . $page_id . '';
            
            $ft->assign(array(
                'PAGE_TITLE'    =>$page_name,
                'PAGE_ID'       =>$page_id,
                'CLASS'         =>"child",
                'PARENT'        =>str_repeat('&nbsp; ', $level), 
                'PAGE_LINK'     =>$page_link
            ));
        
            $pages_sort[]   = $page_name;
            $pages_id[]     = $page_id;
            
            $this->get_breadcrumb($parent_id, $level+2);
        }
    }
    
}

?>
