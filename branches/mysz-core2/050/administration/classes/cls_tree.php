<?php
// $Id: cls_tree.php 1213 2005-11-05 13:03:06Z mysz $

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
        
        global 
            $ft, 
            $rewrite, 
            $CoreRewrite;
        
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
            
            $ft->assign(array(
                'PAGE_NAME' =>$page_name,
                'PAGE_ID'   =>$page_id,
                'CLASS'     =>"child",
                'PARENT'    =>str_repeat('&nbsp; ', $level), 
                'PAGE_LINK' =>$CoreRewrite->permanent_page($page_id, $rewrite)
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
        
        global 
            $ft, 
            $rewrite, 
            $CoreRewrite;
        
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
            
            $ft->assign(array(
                'SUBPAGE_NAME'  =>$subpage_name,
                'SUBPAGE_ID'    =>$subpage_id,
                'CLASS'         =>"child",
                'PARENT'        =>str_repeat('&nbsp; ', $level), 
                'SUBPAGE_LINK'  =>$CoreRewrite->permanent_page($subpage_id, $rewrite)
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
        
        global 
            $ft, 
            $rewrite, 
            $CoreRewrite;
        
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
            
            $ft->assign(array(
                'CAT_NAME'  =>$cat_name,
                'NEWS_CAT'  =>$cat_id,
                'CLASS'     =>"cat_child",
                'PARENT'    =>str_repeat('&nbsp; ', $level), 
                'CAT_LINK'  =>$CoreRewrite->category_news($cat_id, $rewrite)
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
        
        global 
            $ft, 
            $rewrite, 
            $pages_sort, 
            $pages_id, 
            $CoreRewrite;
        
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
            
            $ft->assign(array(
                'PAGE_TITLE'    =>$page_name,
                'PAGE_ID'       =>$page_id,
                'CLASS'         =>"child",
                'PARENT'        =>str_repeat('&nbsp; ', $level), 
                'PAGE_LINK'     =>$CoreRewrite->permanent_page($page_id, $rewrite)
            ));
        
            $pages_sort[]   = $page_name;
            $pages_id[]     = $page_id;
            
            $this->get_breadcrumb($parent_id, $level+2);
        }
    }
    
}

?>
