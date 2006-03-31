<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Provide class for database connection as a singletone
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
 * @version    SVN: $Id: class_coredb.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL$
 */

/**
 * Singleton's pattern of database connection class
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_coredb.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL$
 */
final class CoreDB
{

    /**
     * Instance of PDO connection
     *
     * @var object
     * @access private
     * @static
     */
    private static $_instance = null;

    /**
     * Constructor
     *
     * Private, without parameters and body.
     *
     * @access private
     */
    private function __construct() {}

    /**
     * Connecting with database.
     *
     * Function create an singleton object, and creates database connection.
     * Connection handler is stored in self::$_instance->db variable.
     *
     * @param string $type database type. Only 'mysql' like for now.
     * @return object PDO
     * @throws CESyntaxError if incorrect database type
     * @throws CEDBError     if problem with connect with database
     *
     * @access public
     * @static
     */
    public static function connect($type='mysql')
    {
        if(!isset(self::$_instance)) {
            try {
                switch ($type) {
                    case 'mysql':
                        $dsn = sprintf('mysql:host=%s;dbname=%s',
                            DB_HOST,
                            DB_NAME
                        );
                        self::$_instance = new PDO($dsn, DB_USER, DB_PASS);
                    break;
                    default:
                        throw new CESyntaxError('Invalid database type.');
                }

                self::$_instance->setAttribute(PDO::ATTR_AUTOCOMMIT,
                    true);
                self::$_instance->setAttribute(PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION);
                self::$_instance->setAttribute(PDO::ATTR_CASE,
                    PDO::CASE_NATURAL);
                self::$_instance->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,
                    true);
                self::$_instance->setAttribute(PDO::ATTR_STRINGIFY_FETCHES,
                    false);
            } catch(PDOException $e) {
                throw new CEDBError(sprintf('Connection failed: %s.',
                
                    $e->getMessage())
                );
            }
        }

        return self::$_instance;
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
