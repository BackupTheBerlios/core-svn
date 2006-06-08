<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Group of functions to operations on strings
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
 * @package    Namespaces
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */

/**
 * Group of functions to operations on strings
 *
 * @category   Classes
 * @package    Namespaces
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */
abstract class Strings {
    /**
     * Parse string using class {@link Parser}
     *
     * @param  string  $s      string to parse
     * @param  string  $rettpe specify type of return value
     * @param  boolean $wellformed does passed string is weelformed XML?
     * @access public
     * @static
     */
    public static function parse($s, $rettype='text', $wellformed=false)
    {
        $parser = new Parser;
        return $parser->parse($s, $rettype, $wellformed);
    }
            
    /**
     * Converts html < br /> tag to new line char
     *
     * @param  string $s
     * @param  string $nl new line characters
     * @access public
     * @static
     *
     * @staticvar array $br html < br /> tags used when seeking for them
     */
    public static function br2nl($s, $nl="\n")
    {
        static $br = array('<br />', '<br>', '<br/>');
        return str_replace($br, $nl, $s);
    }

    /**
     * Converts special chars to html entities
     *
     * @param  string $s
     * @access public
     * @static
     *
     * @staticvar array $p special chars to replace
     * @staticvar array $r html entities
     */
    public static function entities($s, $striprn=false)
    {
        static $p = array('<',    '>',    '"',       "'");
        static $r = array('&lt;', '&gt;', '&quot;', '&#39;');
        $ret = str_replace($p, $r, $s);

        if ($striprn) {
            $ret = str_replace(array("\r", "\n"), ' ', $ret);
        }

        return $ret;
    }

    /**
     * Insert links into html <a /> tag
     *
     * @param  string 4s
     *
     * @access public
     * @static
     *
     * @staticvar array $p pattern used in {@link http://php.net/preg_replace preg_replace()}
     * @staticvar array $r replacement used in {@link http://php.net/preg_replace preg_replace()}
     */
    public static function htmlizelinks($s)
    {
        static $p = array(
          '#(^|[\n ])([\w]+?://[^ "\n\r\t<]*)#i',
          '#(^|[\n ])((www|ftp)\.[^ "\t\n\r<]*)#i',
          '#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i'
        );
        static $r = array(
          '\\1<a href="\\2">\\2</a>',
          '\\1<a href="http://\\2">\\2</a>',
          '\\1<a href="mailto:\\2@\\3">\\2@\\3</a>'
        );
	      return preg_replace($p, $r, $s);
    }

    /**
     * Checks that $string starts with $prefix
     *
     * Case sensitive.
     *
     * @param string $string
     * @param string $prefix
     * @access public
     * @static
     */
    public static function startswith($string, $prefix)
    {
        return (substr($string, 0, strlen($prefix)) == $prefix);
    }

    /**
     * Checks that $string ends with $prefix
     *
     * Case sensitive.
     *
     * @param string $string
     * @param string $postfix
     *
     * @access public
     * @static
     */
    public static function endswith($string, $postfix)
    {
        return (substr($string, -strlen($postfix)) == $postfix);
    }

    /**
     * Checks that $string starts with $prefix
     *
     * Case insensitive.
     *
     * @param string $string
     * @param string $prefix
     *
     * @access public
     * @static
     */
    public static function istartswith($string, $prefix)
    {
        return (strtolower(substr($string, 0, strlen($prefix))) == strtolower($prefix));
    }

    /**
     * Checks that $string ends with $prefix
     *
     * Case insensitive.
     *
     * @param string $string
     * @param string $postfix
     *
     * @access public
     * @static
     */
    public static function iendswith($string, $postfix)
    {
        return (strtolower(substr($string, -strlen($postfix))) == strtolower($postfix));
    }

    /**
     * Print string in column at $width chars wide, and alig text to left.
     *
     * @param string  $string
     * @param integer $width width of columnt
     * @param string  $type  string used to use when padding string
     * @param boolean $entit if true, use $type as html entity and treat it as single char
     * @access public
     * @static
     */
    public static function left($string, $width, $type=' ', $entit=true)
    {
        if ($entit) {
            $len = strlen($string);
            if ($width > $len) {
                $width = ($width - $len)*strlen($type) + $len;
            }
        }
        return str_pad($string, $width, $type, STR_PAD_RIGHT);
    }

    /**
     * Print string in column at $width chars wide, and alig text to right.
     *
     * @param string  $string
     * @param integer $width width of columnt
     * @param string  $type  string used to use when padding string
     * @param boolean $entit if true, use $type as html entity and treat it as single char
     * @access public
     * @static
     */
    public static function right($string, $width, $type=' ', $entit=true)
    {
        if ($entit) {
            $len = strlen($string);
            if ($width > $len) {
                $width = ($width - $len)*strlen($type) + $len;
            }
        }
        return str_pad($string, $width, $type, STR_PAD_LEFT);
    }

    /**
     * Print string in column at $width chars wide, and alig text to center.
     *
     * @param string  $string
     * @param integer $width width of columnt
     * @param string  $type  string used to use when padding string
     * @param boolean $entit if true, use $type as html entity and treat it as single char
     * @access public
     * @static
     */
    public static function center($string, $width, $type=' ', $entit=true)
    {
        if (!$entit) {
            return str_pad($string, $width, $type, STR_PAD_BOTH);
        }

        $len   = strlen($string);

        $left  = ceil(($width-$len)/2);
        $right = $width - $len - $left;

        $left  = implode('', array_pad(array(), $left,  $type));
        $right = implode('', array_pad(array(), $right, $type));

        return $left . $string . $right;
    }
}

?>
