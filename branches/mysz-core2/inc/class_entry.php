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
 * @version    SVN: $Id$
 * @link       http://core-cms.com/
 */


/**
 * Just workarounds
 *
 * Will be deleted when class be finished.
 */
function str_nl2br($s) {return nl2br($s);}
function check_email($email) {
    return eregi("^([a-z0-9_]|\\-|\\.)+@(((([a-z0-9_]|\\-)+\\.)+[a-z]{2,4})|localhost)$", $email);
}
include 'config.php';

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
 * @version    SVN: $Id$
 * @link       http://core-cms.com/
 */
class Entry extends CoreBase {

    /**
     * Basic properties of all kinds of entries
     *
     * @see CoreBase::properties
     *
     * @var array
     * @access protected
     */
    protected $properties   =  array(
        /* for core_posts table */
        'id_post'           => array(null, 'integer'),
        'id_parent'         => array(null, 'integer'),
        'id_type'           => array(null, 'integer'),
        'id_section'        => array(null, 'integer'),
        'title'             => array(null, 'string'),
        'caption'           => array(null, 'string'),
        'body'              => array(null, 'string'),
        'tpl_name'          => array(null, 'string'),
        'author_name'       => array(null, 'string'),
        'author_mail'       => array(null, 'string'),
        'author_www'        => array(null, 'string'),
        'date_add'          => array(null, 'string'),
        'date_add_ts'       => array(null, 'integer'),
        'date_mod'          => array(null, 'string'),
        'date_mod_ts'       => array(null, 'integer'),
        'status'            => array(null, 'string')
    );

    /**
     * Set of properties, which must have external setter
     *
     * These properties has additional correctness checking.
     *
     * @var array
     *
     * @access protected
     */
    protected $set_external = array(
        'title', 'caption', 'body', 'author_www', 'author_mail',
        'date_add', 'date_add_ts', 'date_mod', 'date_mod_ts',
        'status'
    );

    /**
     * Available values for entry status
     *
     * @var array
     *
     * @access protected
     */
    protected $status_array = array('published', 'draft', 'disabled');

    /**
     * Checking for correctness of entry title 
     *
     * Sets an error message if title is empty.
     *
     * @param string $data entry title
     * 
     * @return bool
     * @throws CESyntaxError if incorrect type (@see $this->is_type())
     *
     * @access protected
     */
    protected function set_title($data) {
        $this->is_type('title', $data);

        $data = trim($data);
        if ($data == '') {
            $this->error_set('Title cannot be empty.');
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
     * @return bool
     * @throws CESyntaxError if incorrect type (@see $this->is_type())
     *
     * @access protected
     */
    protected function set_caption($data) {
        $this->is_type('caption', $data);

        $this->properties['caption'][0] = str_nl2br($data);
        return true;
    }

    /**
     * Converts new line chars to html's new line tag in body
     *
     * @param string $data entry body
     * 
     * @return bool
     * @throws CESyntaxError if incorrect type (@see $this->is_type())
     *
     * @access protected
     */
    protected function set_body($data) {
        $this->is_type('body', $data);

        $this->properties['body'][0] = str_nl2br($data);
        return true;
    }

    /**
     * Checks for starting 'http://|https://' and add it if neccessary
     *
     * @param string $data author's www address
     * 
     * @return bool
     * @throws CESyntaxError if incorrect type (@see $this->is_type())
     *
     * @access protected
     */
    protected function set_author_www($data) {
        $this->is_type('author_www', $data);
        $data = trim($data);

        if ($data != '' && !preg_match('#^(http|https)://#i', $data)) {
            $data = 'http://' . $data;
        }
        $this->properties['author_www'][0] = $data;
        return true;
    }

    /**
     * Checks for correctness of email address
     *
     * @param string $data author's email address
     * 
     * @return bool
     * @throws CESyntaxError if incorrect type (@see $this->is_type())
     *
     * @access protected
     */
    protected function set_author_mail($data) {
        $this->is_type('author_mail', $data);
        $data = trim($data);

        if ($data != '' && !check_email($data)) {
            $this->error_set('Incorrect email address.');
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
    protected function set_date_add_ts() {
        throw new CESyntaxError('Incorrect property "date_add_ts".');
    }

    /**
     * Timestamp must be set by 'date_mod' property
     *
     * @throws CESyntaxError
     *
     * @access protected
     */
    protected function set_date_mod_ts() {
        throw new CESyntaxError('Incorrect property "date_mod_ts".');
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
     * @param string  $data date
     * @param integer $data unix timestamp
     * @param null    $data
     * 
     * @return bool   if format is incorrect
     * @return array  date
     *
     * @access protected
     */
    protected function check_date($date) {
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
            $regexp = '/
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
                    'date' =>   $d_match[1] .  $d_match[2] .  $d_match[3] .
                                $d_match[4] .  $d_match[5] .  $d_match[6]
                );
            }
        } else {
            return false;
        }

        return $ret;
    }

    /**
     * Checks for correctness of date_add
     *
     * Additional set auxilliary property date_add_ts (as unix timestamp)
     *
     * @param string  $data date
     * 
     * @return bool
     *
     * @access protected
     */
    protected function set_date_add($data) {
        $date = $this->check_date($data);
        if (!$date) {
            $this->error_set(sprintf('Incorrect date format "%s".', $data));
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
     * @return bool
     *
     * @access protected
     */
    protected function set_date_mod($data) {
        $date = $this->check_date($data);
        if (!$date) {
            $this->error_set(sprintf('Incorrect date "%s".', $data));
        }

        $this->properties['date_mod'][0]    = $date['date'];
        $this->properties['date_mod_ts'][0] = $date['timestamp'];
    }

    /**
     * Checks for correct value of status
     *
     * For proper values @see Entry::status_array
     *
     * @param string  $data status
     * 
     * @return bool
     * @throws CESyntaxError if incorrect type (@see $this->is_type())
     *
     * @access protected
     */
    protected function set_status($data) {
        $this->is_type('status', $data);

        if (!in_array($data, $this->status_array)) {
            $this->error_set(sprintf('Incorrect status "%s".', $data));
            return false;
        }
        $this->properties['status'][0]      = $data;
    }
}

?>

