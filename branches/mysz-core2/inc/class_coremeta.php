<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Abstract class for meta properties
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
 * @link       $HeadURL: svn://svn.berlios.de/core/branches/mysz-core2/inc/class_corebase.php $
 */

/**
 * Abstract class for meta properties
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_corebase.php 1275 2006-02-28 15:58:36Z mysz $
 * @link       $HeadURL: svn://svn.berlios.de/core/branches/mysz-core2/inc/class_corebase.php $
 */
abstract class CoreMeta extends CoreBase
{
    /**
     * Type of metadata
     *
     * @var string
     * @access protected
     */
    protected $type;

    /**
     * Set of properties of this object
     *
     * @var array
     * @access protected
     */
    protected $properties = array();

    /**
     * Constructor
     *
     * Load posts meta data from array or from database
     *
     * @param mixed $data
     * @param string $type type of meta data stored in db
     *
     * @access public
     */
    public function __construct(&$data=null, $type)
    {
        parent::__construct();

        $this->type = ucfirst(strtolower($type));

        if (is_int($data)) {
            $this->setFromDB($data);
        } elseif (is_array($data)) {
            $this->setFromArray($data);
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
        $className = $this->type . 'Meta';
        $meta = new $className($this->id_entry);
        $it = $this->getIterator();

        foreach ($it as $key=>$value) {
            if ('id_entry' != $key) {
                if (false === $value || is_null($value)) {
                    $value=0;
                } elseif (true === $value) {
                    $value=1;
                }

                if (isset($meta->$key)) {
                    $query = sprintf("
                        UPDATE
                            %s
                        SET
                            `value` = %s
                        WHERE
                            `type` = %s
                        AND
                            id_entry = %d
                        AND
                            `key` = %s",
                        
                        TBL_META,
                        $this->db->quote($value),
                        $this->db->quote($this->type),
                        $this->id_entry,
                        $this->db->quote($key)
                    );
                } else {
                    $query = sprintf("
                        INSERT INTO
                            %s
                        SET
                            id_entry = %d,
                            `type` = %s,
                            `key` = %s,
                            `value` = %s",
                            
                        TBL_META,
                        $this->id_entry,
                        $this->db->quote($this->type),
                        $this->db->quote($key),
                        $this->db->quote($value)
                    );
                }

                try {
                    $this->db->exec($query);
                } catch (PDOException $e) {
                    throw new CEDBError($e->getMessage(), 500);
                }
            }
        }

        return true;
    }

    /**
     * Read posts meta data from database
     *
     * @param integer $id post id
     *
     * @return boolean
     * @throws CEDBError ({@link CEDBError description})
     *
     * @access public
     */
    public function setFromDB(&$id)
    {
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
            $id,
            $this->db->quote($this->type)
        );

        $result = array('id_entry' => $id);
        try {
            $stmt = $this->db->query($query);
            foreach ($stmt as $data) {
                $type = $this->properties[$data['key']][1];
                settype($data['value'], $type);
                $result[$data['key']] = $data['value'];
            }
        } catch (PDOException $e) {
            throw new CEDBError($e->getMessage(), 500);
        }
        return $this->setFromArray($result);
    }

}

?>
