<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Group of functions to operations on arrays
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
 * Group of functions to operations on arrays
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
abstract class Arrays {

    /**
     * Will flat an multilevel array
     *
     * Keys are not preserved, and dupliacted values are stored.
     *
     * @param array $array
     *
     * @return array flatten array
     * @access public
     * @static
     */
    static public function flat($array)
    {
        $arr = array();
        foreach ($array as $a) {
            if (is_array($a)) {
                $arr = array_merge($arr, array_values(Arrays::flat($a)));
            } else {
                $arr[] = $a;
            }
        }
        return $arr;
    }

    /**
     * Prints array content
     *
     * @param array   $array
     * @param boolean $exit if true, call exit after display array content
     * @param string  $funcName function used to display array content
     *
     * @return void
     * @static
     */
    public static function debug($array, $exit=false, $funcName='print_r')
    {
        echo '<pre>';
        if (!function_exists($funcName)) {
            $funcName = 'print_r';
        }
        $funcName($array);
        echo '</pre>';
        if ($exit) {
            exit;
        }
    }
}

?>
