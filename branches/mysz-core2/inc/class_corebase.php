<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Provide abstract base class for other classes.
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
 * @version    SVN: $Id: class_corebase.php 1275 2006-02-28 15:58:36Z mysz $
 * @link       $HeadURL$
 */

/**
 * Abstract base class.
 *
 * Will be inherited in all classes, which aren't to internal use. For
 * example, exceptions classes will not be inherited from this class,
 * but these of classes which be used to playing with an posts etc,
 * will be inherited from CoreBase.
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_corebase.php 1275 2006-02-28 15:58:36Z mysz $
 * @link       $HeadURL$
 */
abstract class CoreBase
{

    /**
     * All error messages
     *
     * An aray which store all error messages
     *
     * @var array
     * @access protected
     */
    protected $errors = array();

    /**
     * Database connection handler
     *
     * @var object
     * @access protected
     */
    protected $db;

    /**
     * Base set of properties of this object
     *
     * Storing all class properties as an array. All properties must set be here
     * in all of subclasses, as array of arrays:
     * <samp>$properties = array(
     *   'var1' => array(3,            'int'   ),
     *   'var2' => array(array(1,2,3), 'array' ),
     *   'var3' => array('asd',        'string')
     * );</samp>
     * It's for type checking.
     * First item in array is value of var1 property, second - type of value.
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $base_properties = array();

    /**
     * Base set of properties who must have an external getter method
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $base_getExternal = array();

    /**
     * Base set of properties who must have an external setter method
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $base_setExternal = array();

    /**
     * Set of properties of this object
     *
     * @var array
     * @access protected
     * @static
     */
    protected $properties   = array();

    /**
     * Set of properties who must have an external setter method
     *
     * @var array
     * @access protected
     * @static
     */
    protected $getExternal  = array();

    /**
     * Set of properties who must have an external getter method
     *
     * @var array
     * @access protected
     * @static
     */
    protected $setExternal  = array();

    /**
     * Constructor
     *
     * Initialize database connection.
     */
    public function __construct()
    {
        $this->db = CoreDB::connect();
    }

    /**
     * Check that any error occurrence
     *
     * @return boolean
     *
     * @access public
     */
    public function isError()
    {
        return (bool)count($this->errors);
    }

    /**
     * Add an error message
     *
     * Add an error message to internal array
     *
     * @param string $message contains message
     * @param int    $code    contains error code
     *
     * @return boolean
     *
     * @access protected
     */
    protected function errorSet($msg, $code = 0)
    {
        $this->errors[] = array($code, $msg);
        return true;
    }

    /**
     * Gets error message or messages
     *
     * @param bool $last determine that has to return all or just last message
     *
     * @return string last message or array of all messages
     *
     * @access public
     */
    public function errorGet($last = true)
    {
        if($last) {
            return end($this->errors);
        }
        return $this->errors;
    }

    /**
     * Clears messages array
     *
     * @return boolean
     *
     * @access public
     */
    public function errorClear()
    {
        $this->errors = array();
        return true;
    }

    /**
     * Overloaded getter
     *
     * If property doesn't have external getter (if isn't in
     * $this->getExternal array) returns that property (from
     * $this->properties array). In other case, it execute private method
     * $this->get_$property_name().
     *
     * Returned value, if it's type is 'string', is stripslashed() before.
     *
     * @param string $key seeked class property
     *
     * @return mixed value of property
     *
     * @access public
     */
    public function __get($key)
    {
        if (!array_key_exists($key, $this->properties)) {
            throw new CENotFound(sprintf('"%s" property doesn\'t exists.', $key));
        }
        if (in_array($key, $this->getExternal)) {
            $m = sprintf('get_%s', $key);
            return $this->$m();
        }
        if ('string' == $this->properties[$key][1]) {
            return stripslashes($this->properties[$key][0]);
        } else {
            return $this->properties[$key][0];
        }
    }

    /**
     * Overloaded setter
     *
     * If property doesn't have external setter (if isn't in
     * $this->setExternal array) set value of this property (to
     * $this->properties array). In other case, it execute private method
     * $this->set_$property_name().
     *
     * If property type is an string, it runs the addslashes method on it.
     *
     * @param string $key   name of class property
     * @param mixed  $value value of property
     *
     * @return mixed value of property
     * @throws CESyntaxError if type of $value is wrong
     *
     * @access public
     */
    public function __set($key, $value)
    {
        if (!array_key_exists($key, $this->properties)) {
            throw new CENotFound(sprintf('"%s" property doesn\'t exists.', $key));
        }
        if (in_array($key, $this->setExternal)) {
            $m = sprintf('set_%s', $key);
            return $this->$m($value);
        }
        if (is_null($value)) {
            $this->properties[$key][0] = null;
        } else {
            if (!$this->isType($key, $value)) {
                throw new CESyntaxError(sprintf('"%s" property must be an "%s" type, is "%s".',
                    $key,
                    $this->properties[$key][1],
                    gettype($value)
                ));
            }

            if ('string' == $this->properties[$key][1]) {
                $this->properties[$key][0] = addslashes($value);
            } else {
                $this->properties[$key][0] = $value;
            }
        }
        return true;
    }

    /**
     * Overloaded checking by isset() function.
     *
     * Checks for null value in $this->properties, and return true/false.
     * Better way to checking that property is set:
     * isset($entry->title)
     *
     * returns true if title is not null.
     *
     * @param string $key name of property
     *
     * @return boolean true for not null value
     *
     * @access public
     */
    public function __isset($key)
    {
        if (!array_key_exists($this->properties)) {
            return false;
        }
        return is_null($this->properties[$key][0]);
    }

    /**
     * Overloaded unsetting by unset() function.
     *
     * Set null value for unsetting property.
     * Now can be simple way to unset (set null) values of properties, ex.:
     * unset($entry->title)
     *
     * @param string $key name of property
     *
     * @throws CENotFound if property isn't in $this->properties
     *
     * @access public
     */
    public function __unset($key)
    {
        if (array_key_exists($this->properties)) {
            $this->properties[$key][0] = null;
        } else {
            throw new CENotFound(sprintf('"%s" property doesn\'t exists.', $key));
        }
    }

    /**
     * Check for types compatibility of property
     *
     * @param string $key   name of class property
     * @param string $value value of property (reference)
     * @param bool   $throw has throw an exception?
     *
     * @return boolean if $throw == false
     * @throws CESyntaxError instead of returning bool ($throw decides)
     *
     * @access protected
     */
    protected function isType($key, &$value, $throw=true)
    {
        if (gettype($value) == $this->properties[$key][1]) {
            return true;
        }
        if ($throw) {
            throw new CESyntaxError(sprintf('"%s" property must be an "%s" type, is "%s".',
                $key,
                $this->properties[$key][1],
                gettype($value)
            ));
        }
        return false;
    }

    public function getIter()
    {
        return new PropIterator($this->properties);
    }
}

?>
