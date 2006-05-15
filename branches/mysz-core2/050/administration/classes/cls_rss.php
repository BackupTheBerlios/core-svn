<?php
// $Id: cls_tree.php 1138 2005-08-06 18:29:53Z lark $

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

class Rss extends CoreBase {
    
    
    // deklaracje wlasciowsci
    var $id = null;
    var $id_cat = array(); // lista id kategorii do ktorych przynalezy news
    var $timestamp = 0;
    var $time = null;
    var $date = null;
    var $title = '';
    var $author = '';
    var $text = '';
    var $comments_allow = null;
    var $published = null;

    
    /**
     * @param $id - news id
     */
    function _id_check($id = null) {
        
        if(is_null($id)) {
            $id = $this->get_id();
        }

        if(!is_numeric($id) || $id < 0) {
            $this->error_set('Rss::IdCheck:: incorrect news ID.');
            return false;
        }
        
        return true;
    }

    
    /**
     * @param $id_news - news id
     */
    function Rss($id_news = null) {
        
        // konstruktor klasy bazowej
        CoreBase::CoreBase();

        if(!is_null($id_news) && $this->_id_check($id_news)) {
            
            $this->set_id($id_news);
            $data = $this->retrieve();
            
            $data['published']          = ($data['published']           == 1);
            $data['comments_allow']     = ($data['comments_allow']      == 1);
            
            $this->set_from_array($data);
        }

        return true;
    }

    
    /**
     * @param $id
     */
    function set_id($id) {
        
        if(!$this->_id_check($id)) {
            return false;
        }

        $this->id = (int)$id;
        return true;
    }
    
    
    /**
     * @param $id_cat
     */
    function set_id_cat($id_cat) {
        
        if(!is_array($id_cat)) {
            $id_cat = (array)$id_cat;
        }

        $this->id_cat = $id_cat;
        return true;
    }
    
    
    /**
     * @param $timestamp
     * @param $format - date format
     */
    function set_timestamp($timestamp, $format = 'H:i:s Y-m-d') {
        
        $this->timestamp = $timestamp;

        list($this->time, $this->date) = explode(' ', date($format, $timestamp));
        return true;
    }
    
    
    /**
     * @param $title - news title
     */
    function set_title($title) {
        
        $this->title = trim($title);
        return true;
    }
    
    
    /**
     * @param $author - news author
     */
    function set_author($author) {
        $this->author = trim($author);
        return true;
    }
    
    
    /**
     * @param $text - news text
     */
    function set_text($text) {
        $this->text = trim($text);
        return true;
    }
    
    
    /**
     * @param $data - define news comments status
     * @return boolean
     */
    function set_comments_allow($data) {
        $this->comments_allow = (bool)$data;
        return true;
    }
    
    
    /**
     * @param $data - define news published status
     * @return boolean
     */
    function set_published($data) {
        $this->published = (bool)$data;
        return true;
    }
    

    /**
     * @param $array
     */
    function set_from_array($array) {
        
        if (!is_array($array)) {
            $this->error_set('Rss::SetFromArray:: incorrect input data (not an array).');
            return false;
        }

        $bad_prop = array();
        foreach($array as $prop => $value) {
            
            $test = $this->set_prop($prop, $value);
            
            if(!$test) {
                $bad_prop[] = $prop;
            }
        }

        if((bool)count($bad_prop)) {
            return $bad_prop;
        }

        return true;
    }
    
    
    /**
     * @param $prop
     * @param $val
     */
    function set_prop($prop, $val) {
        
        if(!method_exists($this, sprintf('set_%s', $prop))) {
            return false;
        }

        $method = 'set_' . $prop;
        $this->$method($val);
        
        return true;
    }

    
    /**
     * @return $id
     */
    function get_id() {
        return $this->id;
    }
    
    
    /**
     * @return $id_cat
     */
    function get_id_cat() {
        return $this->id_cat;
    }
    
    
    /**
     * @return $timestamp
     */
    function get_timestamp() {
        return $this->timestamp;
    }
    
    
    /**
     * @return $time
     */
    function get_time() {
        return $this->time;
    }
    
    
    /**
     * @return $date
     */
    function get_date() {
        return $this->date;
    }
    
    
    /**
     * @return $title
     */
    function get_title() {
        return $this->title;
    }
    
    
    /**
     * @return $author
     */
    function get_author() {
        return $this->author;
    }
    
    
    /**
     * @return $text
     */
    function get_text() {
        return $this->text;
    }
    
    
    /**
     * @return $comments_allow
     */
    function get_comments_allow() {
        return $this->comments_allow;
    }
    
    
    /**
     * @return $published
     */
    function get_published() {
        return $this->published;
    }
    
}

?>