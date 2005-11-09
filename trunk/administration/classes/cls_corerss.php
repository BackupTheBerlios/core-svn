<?php
// $Id: cls_corenews.php 1213 2005-11-05 13:03:06Z mysz $

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

class CoreRss extends CoreBase {
    
    var $rss = array();

    /*
     * constructor
     */
    function CoreRss() {
        
        CoreBase::CoreBase();

        global $permarr;
        $this->permarr      = $permarr;
        $this->db_config    = $db_config =& new db_config;
    }
    
    
    /**
     * @param $published
     * @param $comments_allow
     * @param $only_in_category
     * @param $order
     */
    function rss_list($published = true, $comments_allow = null, $order = 'asc', $limit) {
        
        if(!in_array($order, array('asc', 'desc'))) {
            $this->error_set('CoreRss::RssList:: incorrect value of $order - none of "asc" or "desc".');
        }
        
        if(!is_null($published)) {
            $published = (bool)$published;
        }
        
        if(!is_null($comments_allow)) {
            $ca = (bool)$ca;
        }

        if($this->is_error()) {
            return false;
        }

        // building query
        $query = sprintf("
            SELECT 
                a.*, b.*, c.id_news, count(DISTINCT c.id) 
            AS 
                comments 
            FROM 
                %1\$s a, 
                %2\$s b 
            LEFT JOIN 
                %3\$s c 
            ON 
                a.id = c.id_news 
            LEFT JOIN 
                %4\$s d 
            ON 
                a.id = d.news_id",

            TABLE_MAIN,
            TABLE_CATEGORY, 
            TABLE_COMMENTS, 
            TABLE_ASSIGN2CAT
        );
        
        $and = 'WHERE';

        // building query continued: conditions
        // skracamy sprawdzanie po kolei warunkow if/elseif i wrzucamy
        // to w petle
        $keys = array('published', 'comments_allow');
        foreach($keys as $key) {
            
            $var = $$key;
            if($var === true) {
                $val = 1;
            } elseif($var === false) {
                $val = -1;
            }

            if(!is_null($var)) {
                $query .= sprintf("
                    %s
                        %s = %d",

                    $and,
                    $key,
                    $val
                );

                $and = 'AND';
            }
        }

        // budowanie query cd: LIMIT
        if($limit > -1) {
            $query .= sprintf("
                LIMIT %d",

                $start
            );
        }

        $this->db->query($query);

        // poniewaz id kategorii do jakich zostal przydzielony wpis trzeba
        // pobrac w osobnym zapytaniu, rozbijamy to na 2 etapy: najpierw
        // pobranie do tablicy $entries zawartosci wpisow, pozniej
        // dopiero dodajemy, w nastepnej petli iteracyjnej, liste kategorii
        // dopiero na koniec tworzymy obiekt klasy News
        $entries = array();
        
        while($this->db->next_record()) {
            $id = $this->db->f('id');
            $entries[$id] = $this->db->get_record();
        }

        while(list($k,) = each($entries)) {
            
            $id_cat = array();
            $query = sprintf("
                SELECT
                    category_id
                FROM
                    %s
                WHERE
                    news_id = %d",
                
                TABLE_ASSIGN2CAT,
                $k
            );
            
            $this->db->query($query);
            
            while($this->db->next_record()) {
                $id_cat[] = $this->db->f('category_id');
            }
            
            $entries[$k]['id_cat'] = $id_cat;

            $entries[$k]['published']           = ($entries[$k]['published']        == 1);
            $entries[$k]['comments_allow']      = ($entries[$k]['comments_allow']   == 1);

            $this->rss[$k] =& new Rss();
            $this->rss[$k]->set_from_array($entries[$k]);
        }

        krsort($this->rss);
        reset($this->rss);

        return true;
    }
    
    
    /**
     * @param $id_news
     */
    function rss_get($id_news) {
        $this->rss[$id_news] =& new Rss($id_news);
        
        krsort($this->news, SORT_NUMERIC);
        reset($this->news);
        
        return true;
    }
    
} // end CoreRss class

?>