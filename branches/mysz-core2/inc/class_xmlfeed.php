<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Class for xml feed
 *
 * PHP version 5
 *
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
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_entry.php 1260 2006-02-17 02:08:01Z lark $
 * @link       http://core-cms.com/
 */

class XmlFeed extends DOMDocument {

    /**
     * elements - data content array 
     *
     * @var array
     * @access protected
     */
    protected $elements = array(
        'title'     =>'some title',
        'link'      =>'http://somesite.com',
        'copyright' =>'Core Dev Team'
    );

    
    /**
     * Constructor
     *
     * Initialize new DomDocument. Set the basic data, like xml version and encoding
     *
     * @access public
     */
    public function __construct() {
        parent::__construct('1.0', 'utf-8');

    }
    
    
    /**
     * Set the basic data, like rss version, feed title, copyrights.
     *
     * @access public
     */
    public function set_xml() {
        
        // set rss element
        $this->root = $this->createElement('rss');
        $this->root = $this->appendChild($this->root);
        
        $this->root->setAttribute('version', '2.0');
        
        // create channel element
        $channel = $this->createElement('channel');
        $channel = $this->root->appendChild($channel);
        
        foreach($this->elements as $name => $value) {
            $name = $this->createElement($name);
            $name = $channel->appendChild($name);
            
            $name->appendChild($this->createTextNode($value));
        }
        
        // cleans array, will be used later for content data
        unset($this->elements);
        
        $this->parse_feed();
    }
    
    
    /**
     * Send headers and output feed to the browser
     *
     * @access public
     */
    public function parse_feed() {
        header('Content-Type: text/xml');
        echo $this->saveXML();
    }
 
}

// returns feed
// $a = new XmlFeed();
// $a->set_xml();

?>