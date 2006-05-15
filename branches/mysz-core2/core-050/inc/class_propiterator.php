<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Iterator class
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
 * @version    SVN: $Id: class_propiterator.php 1341 2006-03-31 17:43:51Z mysz $
 * @link       $HeadURL: https://lark@svn.berlios.de/svnroot/repos/core/branches/mysz-core2/inc/class_propiterator.php $
 */

/**
 * Class for iterating via properties in subclasses of CoreBase
 *
 * Implements interface from SPL class Iterator.
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_propiterator.php 1341 2006-03-31 17:43:51Z mysz $
 * @link       $HeadURL: https://lark@svn.berlios.de/svnroot/repos/core/branches/mysz-core2/inc/class_propiterator.php $
 */
class PropIterator implements Iterator
{
    /**
     * Holds properties array
     */
    private $_properties = array();

    public function __construct(&$properties=null)
    {
        if (!is_array($properties)) {
            throw new CESyntaxError(sprintf('"$properties" must be an "array", is "%s".',
                gettype($properties)
            ));
        }
        $this->_properties =& $properties;
    }

    public function current()
    {
        $v = current($this->_properties);
        if (false !== $v) {
            return $v[0];
        } else {
            return false;
        }
    }

    public function key()
    {
        $v = key($this->_properties);
        return $v;
    }

    public function next()
    {
        $v = next($this->_properties);
        return $v[0];
    }

    public function rewind()
    {
        $v = reset($this->_properties);
        return $v[0];
    }

    public function valid()
    {
        return ($this->current() !== false);
    }
}


?>
