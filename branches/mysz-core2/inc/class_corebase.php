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
abstract class CoreBase implements Iterator
{
    /**
     * Constant
     *
     * Literal 'DESC' for use in SQL queries
     */
    const DESC = 'DESC';

    /**
     * Constant
     *
     * Literal 'ASC' for use in SQL queries
     */
    const ASC  = 'ASC';

    /**
     * All error messages
     *
     * An aray which store all error messages
     *
     * @var array
     * @access protected
     */
    protected $errors       = array();

    /**
     * Database connection handler
     *
     * @var object
     * @access protected
     */
    protected static $db    = null;

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
    protected static $getExternal  = array();

    /**
     * Set of properties who must have an external getter method
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $setExternal  = array();

    /**
     * Store flag did we have to save changed post data.
     *
     * @var boolean
     * @access protected
     */
    protected $modified     = false;

    /**
     * Object of CoreMeta child
     *
     * @var object
     * @access protected
     */
    protected $meta         = null;

    /**
     * Constructor
     *
     * Initialize database connection.
     */
    public function __construct()
    {
        self::$db = CoreDB::init();
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
     * self::$getExternal array) returns that property (from
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
        if (array_key_exists($key, $this->properties)) {
            if (in_array($key, self::$getExternal)) {
                $m = sprintf('get_%s', $key);
                return $this->$m();
            }
            if ('string' == $this->properties[$key][1]) {
                return stripslashes($this->properties[$key][0]);
            } else {
                return $this->properties[$key][0];
            }
        }

        $ret = $this->getMeta($key);
        if (false !== $ret) {
            return $ret;
        }

        throw new CENotFound(sprintf('"%s" property doesn\'t exists.', $key));
    }

    /**
     * Overloaded setter
     *
     * If property doesn't have external setter (if isn't in
     * self::$setExternal array) set value of this property (to
     * $this->properties array). In other case, it execute private method
     * $this->set_$property_name().
     *
     * If property type is an string, it runs the addslashes method on it.
     *
     * @param string $key   name of class property
     * @param mixed  $value value of property
     *
     * @return mixed value of property
     *
     * @access public
     */
    public function __set($key, $value)
    {
        if (array_key_exists($key, $this->properties)) {
            if (in_array($key, self::$setExternal)) {
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
        } else {
            $this->setMeta($key, $value);
        }

        $this->modified = true;

        return true;
    }

    /**
     * Overloaded checking by isset() function.
     *
     * Checks for null value in $this->properties, and return true/false.
     * Better way to checking that property is set:
     * isset($entry->title)
     *
     * Returns true if title is not null.
     *
     * @param string $key name of property
     *
     * @return boolean true for not null value
     *
     * @access public
     */
    public function __isset($key)
    {
        if (array_key_exists($key, $this->properties)) {
            return !is_null($this->properties[$key][0]);
        }

        return isset($this->meta->$key);
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
        if (array_key_exists($key, $this->properties)) {
            $this->properties[$key][0] = null;
        } else if (isset($this->meta->$key)) {
            unset($this->meta->$key);
        } else {
            throw new CENotFound(sprintf('"%s" property doesn\'t exists.', $key));
        }
    }

    /**
     * Check for types compatibility of property
     *
     * @param string $key    name of class property
     * @param string $value  value of property (reference)
     * @param bool   $silent has throw an exception?
     *
     * @return boolean if $silent == true
     * @throws CESyntaxError instead of returning bool ($throw decides)
     *
     * @access protected
     */
    protected function isType($key, &$value, $silent=false)
    {
        if (gettype($value) == $this->properties[$key][1]) {
            return true;
        }
        if (!$silent) {
            throw new CESyntaxError(sprintf('"%s" property must be an "%s" type, is "%s".',
                $key,
                $this->properties[$key][1],
                gettype($value)
            ));
        }
        return false;
    }

    /**
     * Quoting for DB with change empty strings to NULL statement
     *
     * @param string $s
     *
     * @return string
     * @access public
     */
    public function quote($s, $null=false)
    {
        if ($null && '' == $s) {
            return 'NULL';
        } else {
            return self::$db->quote($s);
        }
    }

    /**
     * Set properties from an array.
     *
     * @param $data array array of properties
     *
     * @return boolean       false if some of property doesn't exists, otherwise true
     * @throws CESyntaxError if gettype($array) != array
     *
     * @access protected
     */
    protected function setFromArray(array &$data)
    {
        $meta = array();
        $ret = true;
        while (list($property, $value) = each($data)) {
            if (array_key_exists($property, $this->properties)) {
                try {
                    $this->$property = $value;
                } catch (CEReadOnly $e) {
                    if (!$this->isType($property, $value, true)) {
                        $this->errorSet($e->getMessage());
                        $ret = false;
                    } else {
                        $this->properties[$property][0] = $value;
                    }
                }
            } else {
                $meta[$property] = $value;
            }
        }

        if (!is_null($this->meta)) {
            while (list($key, $value) = each($meta)) {
                $this->setMeta($key, $value);
            }
        }

        return $ret;
    }

    /**
     * For internal use
     *
     * If meta for object is provided, then getMeta() return meta value
     * if property is not specified.
     *
     * @param string $key
     * @access protected
     */
    protected abstract function getMeta($key);

    /**
     * For internal use
     *
     * If meta for object is provided, then setMeta() sets meta data to value
     * if property is not specified.
     *
     * @param string $key
     * @param string $value
     * @access protected
     */
    protected abstract function setMeta($key, $value);

    /**
     * Get data from database
     *
     * @param integer $id
     * @access protected
     */
    public abstract function setFromDb($id);

    /**
     * Return the current element of properties
     *
     * Implements Iterator::current()
     *
     * @return mixed
     * @access public
     */
    public function current()
    {
        $c = current($this->properties);
        if (false !== $c) {
            return $c[0];
        } else {
            return false;
        }
    }

    /**
     * Return the key of the current element
     *
     * Implements Iterator::current()
     *
     * @return string
     * @access public
     */
    public function key()
    {
        return key($this->properties);
    }

    /**
     * Move forward to next element. 
     * 
     * @return mixed
     * @access public
     */
    public function next()
    {
        $c = next($this->properties);
        return $c[0];
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return mixed
     * @access public
     */
    public function rewind()
    {
        $c = reset($this->properties);
        return $c[0];
    }
    
    /**
     * Check if there is a current element after calls to rewind() or next()
     *
     * @return boolean
     * @access public
     */
    public function valid()
    {
        return (bool)current($this->properties);
    }



    /**
     * @internal just for debug purposes
     * List all properties and their values
     */
    public function show() {
        echo '<h1>PROPERTIES:</h2>';
        echo '<pre>';
        foreach ($this as $k=>$prop) {
            if ($prop === false) {
                $p = 'false';
            } elseif ($prop === true) {
                $p = 'true';
            } elseif (is_null($prop)) {
                $p = '<i>NULL</i>';
            } else {
                $p = $prop;
            }
            printf('<b>%s</b>: %s %s'."\n",
                Strings::left($k, 20, ' ', false),
                Strings::left(gettype($prop), 10),
                $p
            );
        }
        echo '</pre>';

        if (!is_null($this->meta)) {
            echo '<h1>META:</h2>';
            $this->meta->show();
        }
    }
}

?>
