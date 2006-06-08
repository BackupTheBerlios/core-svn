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
 * @link       $HeadURL$
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
 * @link       $HeadURL$
 */
class XmlFeed extends DOMDocument {

    /**
     * settings - basic settings array for rss 2.0 feed
     *
     * @var array
     * @access protected
     */
    protected $rss_settings = array(
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
     * settings - basic settings array for atom feed
     *
     * @var array
     * @access protected
     */
    protected $atom_settings = array(
        'title'         => 'Core CMS - RSS Feed',
        'subtitle'      => 'not blank',
        'link'          => 'http://core-cms.com/',
        'atom_link'     => 'http://core-cms.com/feed/atom',
        'rights'        => '2006 Core Dev Team',
        'author'        => array(
            'name'  => 'Core Dev Team',
            'email' => 'core@core-cms.com',
        ),
        'updated'       => '2003-12-13T18:30:02Z', // example
        'id'            => 'http://core-cms.com/'
    );

    
    /**
     * Constructor
     *
     * Initialize new DomDocument. Set the basic data, like xml version,  
     * encoding and kind of feed(rss 0.91, 1.0, 2.0, atom)
     *
     * @access public
     */
    public function __construct($version, $encoding)
    {   
        parent::__construct($version, $encoding);
    }

    
    /**
     * Set the basic data, like rss version, feed title, copyrights.
     *
     * @access public
     */
    public function prepare_xml($feed, $content)
    {
        // feed switcher: rss | atom
        switch($feed) {
            
            case 'rss2.0':
            
                // create the <rss /> element
                $this->root = $this->createElement('rss');
                $this->root = $this->appendChild($this->root);

                // set the <rss /> attribute & value
                $this->root->setAttribute('version', '2.0');

                // create the <channel /> element
                $channel = $this->createElement('channel');
                $channel = $this->root->appendChild($channel);

                while(list($name, $value) = each($this->rss_settings)) {
                    
                    $name = $this->createElement($name);
                    $name = $channel->appendChild($name);

                    $name->appendChild($this->createTextNode($value));
                }
            break;
            
            case 'atom':
            
                // create the <feed /> element
                $this->root = $this->createElement('feed');
                $this->root = $this->appendChild($this->root);

                // set the <feed /> attribute & value
                $this->root->setAttribute(
                    'xmlns', 
                    'http://www.w3.org/2005/Atom'
                );

                while(list($name, $value) = each($this->atom_settings)) {
                    
                    switch($name) {
                        
                        case($name == 'link'):
                        
                            // create the <link /> element
                            $name = $this->createElement($name);
                            $name = $this->root->appendChild($name);
                        
                            // set the <link /> attribute & value
                            $name->setAttribute('rel', 'alternate');
                            $name->setAttribute('href', $value);
                        break;
                        
                        case($name == 'atom_link'):
                        
                            // create the <link /> element
                            $name = $this->createElement('link');
                            $name = $this->root->appendChild($name);
                        
                            // set the <link /> attribute & value
                            $name->setAttribute('rel', 'self');
                            $name->setAttribute('type', 'application/atom+xml');
                            $name->setAttribute('href', $value);
                        break;
                        
                        case($name == 'author'):
                        
                            // create the <author /> element
                            $name = $this->createElement($name);
                            $name = $this->root->appendChild($name);
                        
                            while(list($subname, $subvalue) = each($value)) {
                        
                                $subname = $this->createElement($subname);
                                $subname = $name->appendChild($subname);

                                $subname->appendChild(
                                    $this->createTextNode($subvalue)
                                );
                            }
                        break;
                        
                        default:
                        
                            $name = $this->createElement($name);
                            $name = $this->root->appendChild($name);

                            $name->appendChild($this->createTextNode($value));
                        break;
                    }
                }
            break;
        }

        // cleans array
        unset($this->settings);
        
        if (!is_array($content)) {
            throw new CESyntaxError(sprintf(
                'Incorrect argument type: expected "array", received "%s".',
                gettype($content)
            ));
        }
        
        switch($feed) {
            
            case 'rss2.0':
        
                while(list(, $element) = each($content)) {
            
                    // create the <item /> element
                    $item = $this->createElement('item');
                    $item = $channel->appendChild($item);
            
                    while(list($name, $value) = each($element)) {
                
                        $name = $this->createElement($name);
                        $name = $item->appendChild($name);
                        $name->appendChild($this->createTextNode($value));
                    }
                }
            break;
                
            case 'atom':
            
                while(list(, $element) = each($content)) {
            
                    // create the <item /> element
                    $item = $this->createElement('entry');
                    $item = $this->root->appendChild($item);
            
                    while(list($name, $value) = each($element)) {
                        
                        switch($name) {
                            
                            case($name == 'title'):
                            
                                // create the <title /> element
                                $name = $this->createElement($name);
                                $name = $item->appendChild($name);
                            
                                // set the <title /> type attribute & value
                                $name->setAttribute('type', 'text');
                                $name->appendChild($this->createTextNode($value));
                            break;
                            
                            case($name == 'content'):
                            
                                // create the <title /> element
                                $name = $this->createElement($name);
                                $name = $item->appendChild($name);
                            
                                // set the <title /> type attribute & value
                                $name->setAttribute('type', 'html');
                                $name->appendChild($this->createTextNode($value));
                            break;
                        
                            case($name == 'link'):
                            
                                // create the <link /> element
                                $name = $this->createElement($name);
                                $name = $item->appendChild($name);
                            
                                // set the <link /> href attribute & value
                                $name->setAttribute('href', $value);
                            break;
                            
                            case($name == 'author'):
                            
                                // create the <author /> element
                                $name = $this->createElement($name);
                                $name = $item->appendChild($name);
                        
                                while(list($subname, $subvalue) = each($value)) {
                        
                                    $subname = $this->createElement($subname);
                                    $subname = $name->appendChild($subname);

                                    $subname->appendChild(
                                        $this->createTextNode($subvalue)
                                    );
                                }

                            break;
                            
                            case($name == 'category'):
                            
                                // create the <category /> element
                                $name = $this->createElement($name);
                                $name = $item->appendChild($name);
                            
                                // set the <category /> term attribute & value
                                $name->setAttribute('term', $value);
                                $name->setAttribute('label', $value);
                            break;
                            
                            default:    
                                $name = $this->createElement($name);
                                $name = $item->appendChild($name);
                                $name->appendChild($this->createTextNode($value));
                            break;
                        }
                    }
                }
            break;
        }
        
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

// returns rss feed | example array
$rss_content[0]["title"] = "Some title";
$rss_content[0]["description"] = "Current news content";
$rss_content[0]["link"] = "http://core-cms.com/";
$rss_content[0]["author"] = "core@core-cms.com"; // must be an e-mail
$rss_content[0]["pubDate"] = "Sun, 26 Feb 2006 13:07:07 EST"; // example
$rss_content[0]["category"] = "main";
$rss_content[0]["guid"] = "http://core-cms.com/"; // perma link

// returns atom feed | example array
$atom_content[0]["title"] = "Some title";
$atom_content[0]["content"] = "Current news contentdsa";
$atom_content[0]["link"] = "http://core-cms.com/";
$atom_content[0]["author"]['name'] = "Core Team";
$atom_content[0]["author"]['email'] = "core@core-cms.com"; // must be an e-mail
$atom_content[0]["updated"] = "2003-12-13T18:30:02Z"; // example
$atom_content[0]["category"] = "blabla";
$atom_content[0]["id"] = "http://core-cms.com/";

// how to use
$a = new XmlFeed('1.0', 'utf-8');
$a->prepare_xml('atom', $atom_content);

?>
