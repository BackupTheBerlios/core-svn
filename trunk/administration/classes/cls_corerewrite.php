<?php
// $Id: cls_calendar.php 1128 2005-08-03 22:16:55Z mysz $

class CoreRewrite {
    
    var $rewrite; // rewrite status

    /*
     * constructor
     */
    function CoreRewrite() {

        $view       =& view::instance();
        $this->db   =& $view->db;
        $rewrite    = $this->rewrite;
    }
    

    /**
     * @param $id - news id
     * @param $rewrite - rewrite status
     * @return permanent news link
     */
    function permanent_news($id, $rewrite) {

        $permanent_news = (bool)$rewrite ? sprintf('%s', $id) : 'index.php?p=1&amp;id=' . $id;
        
        return $permanent_news;
    }
    
    
    /**
     * @param $id - page id
     * @param $rewrite - rewrite status
     * @return permanent page link
     */
    function permanent_page($id, $rewrite) {

        $permanent_page = (bool)$rewrite ? sprintf('node/%s', $id) : 'index.php?p=5&amp;id=' . $id;
        
        return $permanent_page;
    }
    
    
    /**
     * @param $rewrite - rewrite status
     * @return main pagination link
     */
    function pagination($rewrite) {

        $pagination = (bool)$rewrite ? 'offset/' : 'index.php?p=all&amp;start=';

        return $pagination;
    }
    
    
    /**
     * @param $id - category id
     * @param $rewrite - rewrite status
     * @return category news link
     */
    function category_news($id, $rewrite) {

        $category_news = (bool)$rewrite ? sprintf('category/%s', $id) : 'index.php?p=4&amp;id=' . $id;
        
        return $category_news;
    }
    
    
    /**
     * @param $id - category id
     * @param $rewrite - rewrite status
     * @return category pagination link
     */
    function category_pagination($id, $rewrite) {

        $category_pagination = (bool)$rewrite ? sprintf('category/%s/', $id) : 'index.php?p=4&amp;id=' . $id . '&amp;start=';
        
        return $category_pagination;
    }
    
    
    /**
     * @param $rewrite - rewrite status
     * @return category all link
     */
    function category_all($rewrite) {

        $category_all = (bool)$rewrite ? 'all' : 'index.php?p=all';
        
        return $category_all;
    }
    
    
    /**
     * @param $rewrite - rewrite status
     * @param $mont - current month
     * @param $day - current day
     * @return current date link
     */
    function current_date($rewrite, $month, $day) {

        $current_date = (bool)$rewrite ? sprintf('date/%s-%s', $month, $day) : sprintf('index.php?p=9&amp;date=%s-%s', $month, $day);
        
        return $current_date;
    }
    
    
    /**
     * @param $issue - current template
     * @param $rewrite - rewrite status
     * @return template switch link
     */
    function template_switch($issue, $rewrite) {

        $template_switch = (bool)$rewrite ? sprintf('switch/%s', $issue) : 'design.php?issue=' . $issue;
        
        return $template_switch;
    }
    
    
    /**
     * @param $rewrite - rewrite status
     * @return search link
     */
    function search($rewrite) {

        $search = (bool)$rewrite ? 'search' : 'index.php?p=8';
        
        return $search;
    }
    
    
    /**
     * @param $word - searched string
     * @param $rewrite - rewrite status
     * @return search link
     */
    function search_pagination($word, $rewrite) {

        $search_pagination = (bool)$rewrite ? sprintf('search/%s/', $word) : sprintf('index.php?p=8&search_word=%s&amp;start=', $word);
        
        return $search_pagination;
    }
    
    
    /**
     * @param $id - news id
     * @param $rewrite - rewrite status
     * @return addcomments link
     */
    function addcomments($id, $rewrite) {

        $addcomments = (bool)$rewrite ? sprintf('addcomments/%s', $id) : 'index.php?p=3&amp;id=' . $id;
        
        return $addcomments;
    }
    
    
    /**
     * @param $rewrite - rewrite status
     * @return addcomments link
     */
    function addcomments_form($rewrite) {

        $addcomments_form = (bool)$rewrite ? 'add/3' : 'index.php?p=3&amp;action=add';
        
        return $addcomments_form;
    }
    

    /**
     * @param $id - news id
     * @param $rewrite - rewrite status
     * @return showcomments link
     */
    function showcomments($id, $rewrite) {

        $showcomments = (bool)$rewrite ? sprintf('comments/%s', $id) : 'index.php?p=2&amp;id=' . $id;
        
        return $showcomments;
    }
    
    
    /**
     * @param $id - current comment id
     * @param $comments_id - news id
     * @param $rewrite - rewrite status
     * @return comments_quote link
     */
    function comments_quote($id, $comments_id, $rewrite) {

        $comments_quote = (bool)$rewrite ? sprintf('addcomments/%s/quote/%s/', $comments_id, $id) : sprintf('index.php?p=3&amp;id=%s&amp;c=%s', $comments_id, $id);
        
        return $comments_quote;
    }
}

?>
