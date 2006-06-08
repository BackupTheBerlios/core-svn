<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Class for meta properties
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
 * Class for meta properties
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
class Meta extends CoreBase
{
    /**
     * Constant - meta property status: already in database
     */
    const ST_DB     = 1;

    /**
     * Constant - meta property status: will be deleted
     */
    const ST_DEL    = 2;

    /**
     * Type of metadata
     *
     * @var string
     * @access protected
     */
    protected $type;

    /**
     * Entry ID
     *
     * @var integer
     * @access protected
     */
    protected $id_entry;

    /**
     * Set of properties of this object
     *
     * @var array
     * @access protected
     */
    protected $properties = array();

    /**
     * Storage for prepared statements
     *
     * @var object PDOStatement
     * @access protected
     */
    protected static $stmt = array();

    /**
     * Constructor
     *
     * Load posts meta data from array or from database
     *
     * @param string $type type of meta data stored in db
     * @param mixed $data
     *
     * @access public
     */
    public function __construct($type, $data=null)
    {
        parent::__construct();

        $this->type = ucfirst(strtolower($type));

        if (is_int($data)) {
            $this->setFromDB($data);
        } elseif (is_array($data)) {
            $this->setFromArray($data);
        }

        self::$stmt[self::ST_DB] = self::$db->prepare(sprintf("
            REPLACE
                %s
            SET
                `value` = :value,
                `key` = :key,
                `id_entry` = :id_entry,
                `type` = :type",

            TBL_META
        ));

        self::$stmt[self::ST_DEL] = self::$db->prepare(sprintf("
            DELETE FROM
                %s
            WHERE
                `key` = :key
            AND
                id_entry = :id_entry
            AND
                `type` = :type",

            TBL_META
        ));
    }

    /**
     * Destructor
     *
     * @access public
     */
    public function __destruct()
    {
        if ($this->modified) {
            $this->save();
        }
    }

    /**
     * Save data
     *
     * @return boolean
     * @throws CEDBError ({@link CEDBError description})
     *
     * @access public
     */
    public function save()
    {
        foreach ($this->properties as $key=>$value) {
            try {
                self::$stmt[$value[1]]->execute(array(
                    ':id_entry' => $this->id_entry,
                    ':key'      => $key,
                    ':value'    => $value[0],
                    ':type'     => $this->type
                ));

                if (self::ST_DEL == $value[1]) {
                    unset($this->properties[$key]);
                }
            } catch (PDOException $e) {
                throw new CEDBError($e->getMessage(), 500);
            }
        }

        return true;
    }

    /**
     * Read meta data from database
     *
     * @param integer $id_entry
     *
     * @return boolean
     * @throws CEDBError ({@link CEDBError description})
     *
     * @access public
     */
    public function setFromDB($id_entry)
    {
        $this->id_entry = (int)$id_entry;

        $query = sprintf("
            SELECT
                `key`,
                `value`
            FROM
                %s
            WHERE
                id_entry = %d
            AND
                type = %s",
            
            TBL_META,
            $id_entry,
            self::$db->quote($this->type)
        );

        $result = array();
        try {
            $stmt = self::$db->query($query);
            foreach ($stmt as $data) {
                $result[$data['key']] = $data['value'];
            }
            $stmt->closeCursor();
        } catch (PDOException $e) {
            throw new CEDBError($e->getMessage(), 500);
        }
        $this->setFromArray($result);

        //reset setting of modified flags
        foreach ($this->properties as &$value) {
            $value[1] = self::ST_DB;
        }
        $this->modified = false;

        return true;
    }

    /**
     * Get data array and set meta data properties from it
     *
     * @param array $data
     * @access protected
     */
    protected function setFromArray(array &$data)
    {
        while (list($key, $value) = each($data)) {
            $this->$key = $value;
        }
    }

    /**
     * Overloaded __set() method - set meta property
     *
     * @param string $key
     * @param mixed $key
     * @return null
     * @access public
     */
    public function __set($key, $value)
    {
        if (isset($this->$key)) {
            $this->properties[$key][0] = Strings::entities($value, true);

            if (self::ST_DEL == $this->properties[$key][1]) {
                $this->properties[$key][1] = self::ST_DB;
            }
        } else {
            $this->properties[$key] = array(
                Strings::entities($value, true),
                self::ST_DB
            );
        }

        $this->modified = true;
    }

    /**
     * Overloaded __get() method - return property or null
     *
     * @param string $key property name
     * @return null
     * @access public
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->properties)) {
            return $this->properties[$key][0];
        }
        return null;
    }

    /**
     * Overloaded isset()
     *
     * @param string $key
     * @return mixed
     * @access public
     */
    public function __isset($key)
    {
        return (
            array_key_exists($key, $this->properties) &&
            $this->properties[$key][1] != self::ST_DEL
        );
    }

    /**
     * Overloaded unset()
     *
     * @param string $key
     * @return null
     * @access public
     */
    public function __unset($key)
    {
        if (isset($this->$key)) {
            $this->properties[$key][0] = null;
            $this->properties[$key][1] = self::ST_DEL;
        }
    }

    /**
     * Set correct meta property
     *
     * Used via CoreBase parent.
     *
     * @param string $key
     * @param mixed $value
     * @return null
     * @access public
     */
    public function setMeta($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * Return meta property
     *
     * Used via CoreBase parent.
     *
     * @param string $key
     * @return null
     * @access public
     */
    public function getMeta($key)
    {
        return $this->$key;
    }


    /**
     * @internal just for debug purposes
     * List all properties and their values
     */
    public function show()
    {
        $values = array(
            self::ST_DB  => 'DB',
            self::ST_DEL => 'DEL'
        );
        foreach ($this->properties as $key=>$value) {
            if (isset($key)) {
                printf("<strong>%s</strong> (%s): %s<br />", $key, $values[$value[1]], $value[0]);
            }
        }
        echo '<br />';
    }
}

?>
