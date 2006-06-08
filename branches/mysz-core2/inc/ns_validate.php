<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Group of functions to validating data
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
 * Group of functions to validating data
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
abstract class Validate {
    /**
     * Validate an email address
     *
     * @param  string $s
     * @access public
     * @static
     */
    public static function email($s)
    {
        return eregi('^([a-z0-9_]|\\-|\\.)+@(((([a-z0-9_]|\\-)+\\.)+[a-z]{2,4})|localhost)$', $s);
    }

    /**
     * Validate login
     *
     * @param  string $s
     * @access public
     * @static
     */
    public static function login($s)
    {
        return preg_match('#^[a-z0-9-.,]{4,64}#i', $s);
    }

    public static function password($s)
    {
        return preg_match('#[a-z0-9_.-]{6,}#i', $s);
    }

    public static function url($s)
    {
        return preg_match('#^(https?|ftps?)://[a-z0-9-]+(\.[a-z0-9-]+)*\.[a-z]{2,4}(/[^ ]*)*[a-z0-9/]?$#i', $s);
    }
}

?>
