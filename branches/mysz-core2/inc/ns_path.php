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
     * Same as DIRECTORY_SEPARATOR
     */
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Same as PATH_SEPARATOR
     */
    const PS = PATH_SEPARATOR;

    /**
     * Sort order - asc.
     */
    const SORT_ASC  = 'asc';

    /**
     * Sort order - desc.
     */
    const SORT_DESC = 'desc';

    /**
     * Get join aguments into path.
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

        return implode(self::DS, $path);
    }

    /**
     * Return array with path elements.
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
        if ('\\' == self::DS &&  //windows
                preg_match('#^([a-z]:\\\\)#i', $path, $match)) {
            $ret[] = substr($match[1], 0, 2);
            $path = str_replace($match[1], '', $path);
        }

        return array_merge($ret, explode(self::DS, $path));
    }

    /**
     * Replace [back]slashes on correct separator, and normalize case.
     *
     * Case normalizing in only on Windows&trade;.
     *
     * @param string $path
     * @access public
     * @static
     */
    public static function normalize($path)
    {
        static $p = array('#\\\\#', '#/#');
        static $r = array('/', self::DS);
        $path = preg_replace($p, $r, $path);

        if ('\\' == self::DS) { //windows
            $path = strtolower($path);
        }
        return $path;
    }

    /**
     * Normalizing path.
     *
     * Will remove any single and double dots ('.' and '..') with proper
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

    /**
     * Return true if path exists.
     *
     * @param string $path
     *
     * @return boolean
     * @access public
     * @static
     */
    public static function exists($path)
    {
        return ('' != realpath($path));
    }

    /**
     * Returns content of directory.
     *
     * If $split == true, Path::listdir returns flat array with mixed
     * files and directories.
     * Otherwise, return 2-element associative array, with keys: 'files'
     * and 'dirs'.
     *
     * Return doesn't include 'dots' ('.' and '..').
     *
     * @param string  $dir   directory to scan
     * @param string  $order Path::SORT_ASC or Path::SORT_DESC
     * @param boolean $split 
     *
     * @return array
     * @throws CENotFound ({@link CENotFound description})
     *
     * @access public
     */
    public static function listdir($dir, $order=self::SORT_ASC, $split=false)
    {
        $dir = Path::real($dir);
        if (!Path::exists($dir)) {
            throw new CENotFound(sprintf('Directory "%s" not found.', $dir));
        }

        $files = array();
        $dirs  = array();

        $d = dir($dir);
        while (false !== ($obj = $d->read())) {
            if (in_array($obj, array('.', '..'))) {
                continue;
            }

            $path = Path::join($dir, $obj);
            if (is_link($path)) {
                $path = readlink($path);
                $obj  = basename($path);
            }
            if (is_file($path)) {
                $files[] = $obj;
            } else if (is_dir($path)) {
                $dirs[] = $obj;
            }
        }
        
        if (!$split) {
            $ret = array_merge($files, $dirs);
            switch ($order) {
                case self::SORT_ASC:
                    sort($ret, SORT_STRING);
                break;
                case self::SORT_DESC:
                    rsort($ret, SORT_STRING);
                break;
            }
        } else {
            switch ($order) {
                case self::SORT_ASC:
                    sort($files);
                    sort($dirs);
                break;
                case self::SORT_DESC:
                    rsort($files);
                    rsort($dirs);
                break;
            }

            $ret = array(
                'files' => $files,
                'dirs'  => $dirs,
            );
        }

        return $ret;
    }

    /**
     * Recursive walk into directory and return it's content.
     *
     * @param string  $dir directory to scan
     * @param boolean $assoc return associative or normal array
     * @param integer $maxLevel how deep scan $dir
     *
     * @return array
     * @throws CENotFound ({@link CENotFound description})
     *
     * @access public
     */
    public static function walk($dir, $assoc=false, $maxLevel=false, $curLevel=1)
    {
        $dir = Path::real($dir);

        $ret = array();
        $data = Path::listdir($dir, self::SORT_ASC, true);
        if ($assoc) {
            $ret[$dir]  = array(
                'dirs'  => $data['dirs'],
                'files' => $data['files']
            );
        } else {
            $ret[] = array(
                $dir,
                $data['dirs'],
                $data['files']
            );
        }

        if (false === $maxLevel || $maxLevel > $curLevel) {
            foreach ($data['dirs'] as $d) {
                $path = Path::join($dir, $d);
                $ret = array_merge($ret, Path::walk($path, $assoc, $maxLevel, $curLevel+1));
            }
        }
        
        return $ret;
    }

}

?>
