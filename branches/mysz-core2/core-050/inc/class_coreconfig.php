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
 * @version    SVN: $Id: class_coreconfig.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL: https://lark@svn.berlios.de/svnroot/repos/core/branches/mysz-core2/inc/class_coreconfig.php $
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
 * @version    SVN: $Id: class_coreconfig.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL: https://lark@svn.berlios.de/svnroot/repos/core/branches/mysz-core2/inc/class_coreconfig.php $
 */
final class CoreConfig extends CoreBase
{

    /**
     * Prepared query for getting properties.
     *
     * @var object
     * @access protected
     */
    protected $stmtGet     = null;

    /**
     * Prepared query for setting properties.
     *
     * @var object
     * @access protected
     */
    protected $stmtSet     = null;


    /**
     * Prepared query for inserting properties.
     *
     * @var object
     * @access protected
     */
    protected $stmtInsert  = null;

    /**
     * Constructor
     *
     * Creates prepared statements with acquire bindings.
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $query = sprintf("
            SELECT
                `value`
            FROM
                %s
            WHERE
                `key` = :k",

            TBL_CONFIG
        );
        $this->stmtGet = $this->db->prepare($query);

        $query = sprintf("
            UPDATE
                %s
            SET
                `value` = :v
            WHERE
                `key` = :k",

            TBL_CONFIG
        );
        $this->stmtSet = $this->db->prepare($query);

        $query = sprintf("
            INSERT INTO
                %s
            SET
                `key` = :k,
                `value = :v",

            TBL_CONFIG
        );
        $this->stmtInsert = $this->db->prepare($query);
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
        $silent = ('_' == substr($key, 0, 1));
        if ($silent) {
            $key = substr($key, 1);
        }

        try {
            $this->stmtGet->execute(array(':k'=>$key));
        } catch (PDOException $e) {
            throw new CEDBError($e->getMessage());
        }

        $row = $this->stmtGet->fetch();
        if (!$row) {
            if ($silent) {
                return false;
            } else {
                throw new CENotFound(sprintf('Config property "%s" not found.', $key));
            }
        }

        return unserialize($row['value']);
    }

    /**
     * Overloaded setter
     *
     * Magic: if key name begins with underscore, it will add _new_ property
     * to database if it doesn't exists.
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
        try {
            if ('_' != $key[0]) { //update of existing value
                $this->stmtSet->execute(array(':k'=>$key, ':v'=>serialize($value)));
            } else { //insert new value
                $binds = array(':k'=>substr($key, 1), ':v'=>serialize($value));
                $this->stmtInsert->execute($binds);
            }
        } catch (PDOException $e) {
            throw new CEDBError($e->getMessage());
        }
    }
}

?>
