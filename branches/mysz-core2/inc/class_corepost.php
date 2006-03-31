<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Class for playing with posts
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
 * @version    SVN: $Id: class_entry.php 1272 2006-02-26 18:36:12Z mysz $
 * @link       $HeadURL$
 */

/**
 * Provides API for playing with entries.
 *
 * Will be inherited by all entry classes, like posts, comments etc.
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_entry.php 1272 2006-02-26 18:36:12Z mysz $
 * @link       $HeadURL$
 */
abstract class CorePost extends CoreBase {

    /**
     * Constant
     *
     * Default link (in author_www property) prefix.
     */
    const DEFAULT_LINK = 'http://';

    /**
     * Base set of properties of all kinds of entries
     *
     * @see CoreBase::base_properties
     *
     * @var array
     * @access protected
     */
    protected static $base_properties   =  array(
        /* for core_posts table */
        'id_post'           => array(null,    'integer'),
        'id_parent'         => array(null,    'integer'),
        'id_cat'            => array(null,    'integer'),
        'id_type'           => array(null,    'integer'),
        'id_section'        => array(null,    'integer'),
        'title'             => array('',      'string' ),
        'permalink'         => array('',      'string' ),
        'caption'           => array(null,    'string' ),
        'body'              => array(null,    'string' ),
        'tpl_name'          => array('',      'string' ),
        'author_name'       => array(null,    'string' ),
        'author_mail'       => array(null,    'string' ),
        'author_www'        => array(null,    'string' ),
        'date_add'          => array('',      'string' ),
        'date_add_ts'       => array(0,       'integer'),
        'date_mod'          => array('',      'string' ),
        'date_mod_ts'       => array(0,       'integer'),
        'status'            => array('draft', 'string' )
    );

    /**
     * Base set of properties, which must have external setter
     *
     * These properties has additional correctness checking.
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $base_setExternal = array(
        'title', 'caption', 'body', 'author_www', 'author_mail',
        'date_add', 'date_add_ts', 'date_mod', 'date_mod_ts',
        'status'
    );

    /**
     * Base set of properties, which must have external getter
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $base_getExternal = array();

    /**
     * All post properties together
     *
     * @var array
     * @access protected
     */
    protected $properties;

    /**
     * All post external getters together
     *
     * @var array
     * @access protected
     */
    protected $getExternal;

    /**
     * All post external setters together
     *
     * @var array
     * @access protected
     */
    protected $setExternal;

    /**
     * Constructor
     *
     * Merge $base_properties, $base_getExternal and $base_setExternal with
     * parents.
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        self::$base_properties  = array_merge(
            parent::$base_properties, 
            self::$base_properties
        );
        self::$base_setExternal = array_merge(
            parent::$base_setExternal,
            self::$base_setExternal
        );
        self::$base_getExternal = array_merge(
            parent::$base_getExternal,
            self::$base_getExternal
        );
    }

    /**
     * Checking for correctness of entry title
     *
     * Sets an error message if title is empty.
     *
     * @param string $data entry title
     *
     * @return boolean
     * @throws CESyntaxError if incorrect type (@see $this->isType())
     *
     * @access protected
     */
    protected function set_title($data)
    {
        $this->isType('title', $data);

        $data = trim($data);
        if ('' == $data) {
            $this->errorSet('Title cannot be empty.');
            return false;
        }
        $this->properties['title'][0] = $data;
        return true;
    }

    /**
     * Converts new line chars to html's new line tag in caption
     *
     * @param string $data entry caption
     *
     * @return boolean
     * @throws CESyntaxError if incorrect type (@see $this->isType())
     *
     * @access protected
     */
    protected function set_caption($data)
    {
        if (is_null($data)) {
            unset($this->caption);
            return true;
        }

        $this->isType('caption', $data);

        $this->properties['caption'][0] = Strings::parse($data);
        return true;
    }

    /**
     * Converts new line chars to html's new line tag in body
     *
     * @param string $data entry body
     *
     * @return boolean
     * @throws CESyntaxError if incorrect type (@see $this->istype())
     *
     * @access protected
     */
    protected function set_body($data)
    {
        if (is_null($data)) {
            unset($this->body);
            return true;
        }

        $this->isType('body', $data);

        $this->properties['body'][0] = Strings::parse($data);
        return true;
    }

    /**
     * Checks for starting 'http://|https://' and add it if neccessary
     *
     * @param string $data author's www address
     *
     * @return boolean
     * @throws CESyntaxError if incorrect type (@see $this->isType())
     *
     * @access protected
     */
    protected function set_author_www($data)
    {
        if (is_null($data)) {
            unset($this->author_www);
            return true;
        }

        $this->isType('author_www', $data);
        $data = trim($data);

        if ('' != $data && !preg_match('#^(http|https)://#i', $data)) {
            $data = self::DEFAULT_LINK . $data;
        }
        $this->properties['author_www'][0] = $data;
        return true;
    }

    /**
     * Checks for correctness of email address
     *
     * @param string $data author's email address
     *
     * @return boolean
     * @throws CESyntaxError if incorrect type (@see $this->isType())
     *
     * @access protected
     */
    protected function set_author_mail($data)
    {
        if (is_null($data)) {
            unset($this->author_mail);
            return true;
        }

        $this->isType('author_mail', $data);
        $data = trim($data);

        if ('' != $data && !Strings::email($data)) {
            $this->errorSet('Incorrect email address.');
            return false;
        } else {
            $this->properties['author_mail'][0] = $data;
            return true;
        }
    }

    /**
     * Timestamp must be set by 'date_add' property
     *
     * @throws CESyntaxError
     *
     * @access protected
     */
    protected function set_date_add_ts()
    {
        throw new CEReadOnly('Read only property.');
    }

    /**
     * Timestamp must be set by 'date_mod' property
     *
     * @throws CESyntaxError
     *
     * @access protected
     */
    protected function set_date_mod_ts()
    {
        throw new CEReadOnly('Read only property.');
    }

    /**
     * Checks for correctness of date_add
     *
     * Additional set auxilliary property date_add_ts (as unix timestamp)
     *
     * @param string  $data date
     *
     * @return boolean
     *
     * @access protected
     */
    protected function set_date_add($data)
    {
        if (is_null($data)) {
            unset($this->date_add);
            return true;
        }

        $date = $this->checkDate($data);
        if (!$date) {
            $this->errorSet(sprintf('Incorrect date format "%s".', $data));
        }

        $this->properties['date_add'][0]    = $date['date'];
        $this->properties['date_add_ts'][0] = $date['timestamp'];
    }

    /**
     * Checks for correctness of date_mod
     *
     * Additional set auxilliary property date_mod_ts (as unix timestamp)
     *
     * @param string  $data date
     *
     * @return boolean
     *
     * @access protected
     */
    protected function set_date_mod($data)
    {
        if (is_null($data)) {
            unset($this->date_mod);
            return true;
        }

        $date = $this->checkDate($data);
        if (!$date) {
            $this->errorSet(sprintf('Incorrect date "%s".', $data));
            return false;
        }

        $this->properties['date_mod'][0]    = $date['date'];
        $this->properties['date_mod_ts'][0] = $date['timestamp'];
    }

    /**
     * Checks for correctness of date
     *
     * As parametr can be an string in correct format ('yy-mm-dd hh:mm:ss'),
     * unix timestamp of time (integer) or null - if time has to be 'now'.
     *
     * Returns false if time format is incorrect, or array:
     * $ret['timestamp'] - unix timestamp
     * $ret['date']      - date in format: yymmddhhmmss
     *
     * @param string      $data date
     * @param integer     $data unix timestamp
     * @param null        $data
     *
     * @return array|bool date and timestamp array, or false if incorrect format
     *
     * @access protected
     */
    protected function checkDate($date)
    {
        if(is_null($date)) { //it means: now
            $ts = time(); //for sure date == timestamp
            $ret = array(
                'timestamp' => $ts,
                'date'      => date('YmdHis', $ts)
            );
        } elseif (is_int($date)) { //unix timestamp
            $date_arr = getdate($date);
            if (!checkdate($date_arr['mon'], $date_arr['mday'], $date_arr['year'])) {
                return false;
            }

            $ret = array(
                'timestamp' => $date,
                'date'      => date('YmdHis', $date)
            );
        } elseif (is_string($date)) {
            static $regexp = '/
                ^
                ([0-9][0-9][0-9][0-9]) #year (index:1)
                -
                ([0-9][0-9]) #month (index:2)
                -
                ([0-9][0-9]) #day (index:3)
                [ ]
                ([0-9][0-9]) #hour (index:4)
                :
                ([0-9][0-9]) #minute (index:5)
                :
                ([0-9][0-9]) #second (index:6)
                $
                /ix';

            // wlasciwy format czasu ?
            if(!preg_match($regexp, $date, $d_match)) {
                return false;
            } else {
                $ret = array(
                    'timestamp' => mktime(
                            (int)$d_match[4],
                            (int)$d_match[5],
                            (int)$d_match[6],
                            (int)$d_match[2],
                            (int)$d_match[3],
                            (int)$d_match[1],
                            -1
                    ),
                    'date' =>   $d_match[1] . $d_match[2] . $d_match[3] .
                                $d_match[4] . $d_match[5] . $d_match[6]
                );
            }
        } else {
            return false;
        }

        return $ret;
    }

}

?>

