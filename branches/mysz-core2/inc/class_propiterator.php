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
class PropIterator implements Iterator {
    private $properties = array();

    public function __construct($properties = null)
    {
        if (!is_array($properties)) {
            throw new CESyntaxError(sprintf('"$properties" must be an "array", is "%s".',
                gettype($properties)
            ));
        }
        $this->properties = $properties;
    }

    public function current()
    {
        $v = current($this->properties);
        if ($v !== false) {
            return $v[0];
        } else {
            return false;
        }
    }

    public function key()
    {
        $v = key($this->properties);
        return $v[0];
    }

    public function next()
    {
        $v = next($this->properties);
        return $v[0];
    }

    public function rewind()
    {
        $v = reset($this->properties);
        return $v[0];
    }

    public function valid()
    {
        return ($this->current() !== false);
    }
}

class CoreBase {}
class T extends CoreBase{
    private $o = array(
        'a' => array(1, 'int'),
        'b' => array(2, 'int'),
        'c' => array('asd', 'string'),
        'd' => array(true, 'boolean')
    );
    public function getIter()
    {
        return new PropIterator($this->o);
    }
}

$a = new T;
foreach ($a->getIter() as $k=>$v) {
    printf('%s: %s<br />', $k, $v);
}

?>
