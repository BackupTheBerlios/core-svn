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
 * @version    SVN: $Id$
 * @link       http://core-cms.com/
 */


/**
 * Class for prepare & parse xml feed, based on DOM
 *
 * Sets basic settings of xml file, like headers. Prepare data from array to
 * parse feed.
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       http://core-cms.com/
 */
class XmlFeed extends DOMDocument {

    /**
     * settings - basic settings array
     *
     * @var array
     * @access protected
     */
    protected $settings = array(
        'title'         => 'Core CMS - RSS Feed',
        'link'          => 'http://core-cms.com',
        'description'   => '',
        'language'      => 'pl',
        'copyright'     => '2006 Core Dev Team',
        'docs'          => 'http://blogs.law.harvard.edu/tech/rss',
        'webMaster'     => 'core@core-cms.com',
        'lastBuildDate' => 'Sun, 26 Feb 2006 13:07:07 EST' // example
    );

    
    /**
     * Constructor
     *
     * Initialize new DomDocument. Set the basic data, like xml version and 
     * encoding
     *
     * @access public
     */
    public function __construct($version, $encoding)
    {   
        parent::__construct($version, $encoding);
        $this->set_xml();
    }

    
    /**
     * Set the basic data, like rss version, feed title, copyrights.
     *
     * @access public
     */
    public function set_xml()
    {
        // set rss element
        $this->root = $this->createElement('rss');
        $this->root = $this->appendChild($this->root);

        $this->root->setAttribute('version', '2.0');

        // create the channel element
        $channel = $this->createElement('channel');
        $channel = $this->root->appendChild($channel);

        foreach($this->settings as $name => $value) {
            $name = $this->createElement($name);
            $name = $channel->appendChild($name);

            $name->appendChild($this->createTextNode($value));
        }

        // cleans array
        unset($this->settings);
        
    }
    
    
    /**
     * Set properties of feed from an array.
     *
     * @param $content array of properties
     *
     * @throws CESyntaxError if gettype($array) != array
     *
     * @access public
     */
    public function prepare_xml($content)
    {
        if (!is_array($content)) {
            throw new CESyntaxError(sprintf(
                'Incorrect argument type: expected "array", received "%s".',
                gettype($content)
            ));
        }
        
        foreach($content as $element) {
            
            // create the item element
            $item = $this->createElement("item");
            $item = $this->root->appendChild($item);
            
            foreach($element as $name=>$value) {
                
                $name = $this->createElement($name);
                $name = $item->appendChild($name);
                $name->appendChild($this->createTextNode($value));
            }
            unset($element);
        }
        unset($content);
        
        $this->parse_xml();
    }

    
    /**
     * Send headers and output feed to the browser
     *
     * @access public
     */
    public function parse_xml()
    {
        header('Content-Type: text/xml');
        echo $this->saveXML();
    }
}

// returns feed
$content[0]["title"] = "Some title";
$content[0]["description"] = "Current news content";
$content[0]["link"] = "http://core-cms.com/";
$content[0]["author"] = "Core Dev Team";
$content[0]["pubDate"] = "Sun, 26 Feb 2006 13:07:07 EST"; // example
$content[0]["category"] = "main";

$a = new XmlFeed('1.0', 'utf-8');
$a->prepare_xml($content);

?>
