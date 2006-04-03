<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Group of functions to operations on paths
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
 * Group of functions to operations on paths
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
abstract class Path
{
    /**
     * Constant
     *
     * Same as DIRECTORY_SEPARATOR
     */
    const SEPARATOR = DIRECTORY_SEPARATOR;

    /**
     * Get join aguments into path
     *
     * Will join all elements, they can be an arrays ({@link Arrays::flat()} is used)
     * or single arguments.
     *
     * @access public
     * @static
     */
    public static function join()
    {
        $argv = Arrays::flat(func_get_args());

        $path = array();
        while (list(, $c) = each($argv)) {
            $c = Path::split($c);
            if (preg_match('#^[a-z]:$#', $c[0])) {
                $path = array();
            }
            $path = array_merge($path, $c);
        }

        return implode(self::SEPARATOR, $path);
    }

    /**
     * Return array with path elements
     *
     * @param string $path
     * @access public
     * @static
     */
    public static function split($path)
    {
        $path = Path::normalize($path);

        $ret = array();
        // we cut drive letter if os == windows and put it as first element
        if ('\\' == self::SEPARATOR &&  //windows
                preg_match('#^([a-z]:\\\\)#i', $path, $match)) {
            $ret[] = substr($match[1], 0, 2);
            $path = str_replace($match[1], '', $path);
        }

        return array_merge($ret, explode(self::SEPARATOR, $path));
    }

    /**
     * Replace [back]slashes on correct separator, and normalize case
     *
     * Case normalizing in only on Windows&trade;
     *
     * @param string $path
     * @access public
     * @static
     */
    public static function normalize($path)
    {
        static $p = array('#\\\\#', '#/#');
        static $r = array('/', self::SEPARATOR);
        $path = preg_replace($p, $r, $path);

        if ('\\' == self::SEPARATOR) { //windows
            $path = strtolower($path);
        }
        return $path;
    }

    /**
     * Normalizing path
     *
     * Will remove any single and doubled dots ('.' and '..') with proper
     * path elements.
     *
     * @param string $path
     * @access public
     * @static
     */
    public static function real($path)
    {
        $path = Path::split($path);
        $newpath = array();
        while (list(, $v) = each($path)) {
            switch ($v) {
                case '.' :
                    continue;
                case '..':
                    array_pop($newpath);
                    continue;
                default:
                    $newpath[] = $v;
            }
        }
        return Path::join($newpath);
    }
}

?>
