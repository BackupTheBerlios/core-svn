<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Provide access to database config table
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
 * @link       $HeadURL$
 */

/**
 * Class who provides interface to core_config databse table.
 *
 * Class use PDO prepared statements.
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */
final class CoreConfig
{
    /**
     * constant - SELECT prepared statement
     */
    const ST_SEL = 1;

    /**
     * constant - REPLACE prepared statement
     */
    const ST_DB  = 2;

    /**
     * constant - DELETE prepared statement
     */
    const ST_DEL = 3;

    /**
     * Database connection handler
     *
     * @var object
     * @access private
     */
    private $_db            = null;

    /**
     * CoreConfig object
     *
     * @var object
     * @access private
     */
    private static $_object = null;

    /**
     * Storage for prepared statements
     *
     * @var object
     * @access private
     */
    private $_stmt = array();

    /**
     * Cache
     *
     * @var array
     * @access private
     */
    private $_cache        = array();

    /**
     * Create CoreConfig object and/or return it. Singleton init method.
     *
     * @return object CoreConfig
     *
     * @access public
     */
    public static function init()
    {
        if (is_null(self::$_object)) {
            self::$_object = new CoreConfig;
        }
        return self::$_object;
    }

    /**
     * Constructor
     *
     * Creates prepared statements with acquire bindings.
     *
     * @access private
     */
    private function __construct()
    {
        $this->_db = CoreDB::init();

        $this->_stmt[self::ST_SEL] = $this->_db->prepare(sprintf("
            SELECT
                `value`
            FROM
                %s
            WHERE
                `key` = :key",

            TBL_CONFIG
        ));
        $this->_stmt[self::ST_DB] = $this->_db->prepare(sprintf("
            REPLACE
                %s
            SET
                `key` = :key,
                `value` = :value",

            TBL_CONFIG
        ));
        $this->_stmt[self::ST_DEL] = $this->_db->prepare(sprintf("
            DELETE FROM
                %s
            WHERE
                `key` = :key",

            TBL_CONFIG
        ));
    }

    /**
     * Overloaded getter
     *
     * Magic: if property name begins with underscore, it will not raise an
     * exception if property will not be found in database, but return false.
     *
     * @param string $key name of property
     *
     * @return mixed      value of property or false if not found
     * @throws CEDBError  if any database error
     * @throws CENotFound if property not found
     *
     * @access public
     */
    public function __get($key)
    {
        //needed setting is in _cache already ?
        if (array_key_exists($key, $this->_cache)) {
            return $this->_cache[$key];
        }



        $silent = ('_' == $key[0]);
        if ($silent) {
            $key = substr($key, 1);
        }

        //shortcut
        $stmt = &$this->_stmt[self::ST_SEL];
        try {
            $stmt->execute(array(':key'=>$key));
        } catch (PDOException $e) {
            throw new CEDBError($e->getMessage());
        }

        $row = $stmt->fetch();
        if (!$row) {
            if ($silent) {
                return false;
            } else {
                throw new CENotFound(sprintf('Config property "%s" not found.', $key));
            }
        }
        $stmt->closeCursor();
        $this->_cache[$key] = unserialize($row['value']);

        return $this->_cache[$key];
    }

    /**
     * Overloaded setter
     *
     * @param string $key   name of property
     * @param string $value value of property
     *
     * @throws CEDBError if any database error
     *
     * @access public
     */
    public function __set($key, $value)
    {
        $s_value = serialize($value);
        //shortcut
        $stmt = &$this->_stmt[self::ST_DB];

        try {
            $stmt->execute(array(
                ':key' => $key,
                ':value' => $s_value
            ));
            $stmt->closeCursor();
        } catch (PDOException $e) {
            throw new CEDBError($e->getMessage());
        }

        $this->_cache[$key] = $value;
    }

    /**
     * Overloaded __isset() for checking did property is set
     *
     * @param string $k name of property
     *
     * @return boolean
     * 
     * @access public
     */
    public function __isset($key)
    {
        try {
            $this->$k;
            return true;
        } catch (CENotFound $e) {
            return false;
        }
    }

    /**
     * Overloaded __unset() for deleting properties
     *
     * @param string $k name of property
     *
     * @return boolean
     *
     * @access public
     */
    public function __unset($key)
    {
        $this->_stmt[self::ST_DEL]->execute(array(':key' => $key));
    }

    /**
     * Overloaded method to forbid cloning of instance
     *
     * @throws CESyntaxError always when invoked
     * @access public
     */
    public function __clone()
    {
        throw new CESyntaxError('Singleton cloning not allowed.');
    }

}

?>
