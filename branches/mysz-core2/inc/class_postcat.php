<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Class for operations on post categories
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
 * Class for operations on post categories
 *
 * Error codes:
 *  10 - CEReadOnly
 * 100 - CESyntaxError      Category|Group name cannot be changed from Post object.
 * 500 - CEDBError
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
final class PostCat extends CoreBase
{
    /**
     * All category properties
     *
     * @var array
     * @access protected
     */
    protected $properties = array(
        'id_cat'        => array(null, 'integer'),
        'id_parent'     => array(0,    'integer'),
        'name'          => array(null, 'string' ),
        'permalink'     => array(null, 'string' ),
        'description'   => array(null, 'string' ),
        'tpl_name'      => array(null, 'string' ),
        'enabled'       => array(true, 'boolean')
    );

    /**
     * List of external setters
     *
     * @var array
     * @access protected
     */
    protected $setExternal = array('permalink', 'name');

    /**
     * Constructor
     *
     * Get data from database (if $data is integer) or from array.
     *
     * @param mixed $data
     *
     * @access public
     */
    public function __construct(&$data=null)
    {
        parent::__construct();

        if (is_array($data)) {
            $this->setFromArray($data);
        } elseif (is_int($data)) {
            $this->setFromDB($data);
        }
    }

    /**
     * External setter for permalink property
     *
     * @param string $data value of permalink property
     *
     * @return boolean
     *
     * @access protected
     */
    protected function set_permalink(&$data)
    {
        $this->isType('permalink', $data);
        $this->properties['permalink'][0] = $data;
        return true;
    }

    /**
     * External setter for category name
     *
     * @param string $data
     *
     * @return boolean
     *
     * @access protected
     */
    protected function set_name(&$data)
    {
        $this->isType('name', $data);
        $data = trim($data);
        if ('' == $data) {
            $this->errorSet('Category name cannot be empty.');
            return false;
        }
        $this->properties['name'][0] = $data;
        return true;
    }

    /**
     * Save category (new ot update) in database
     * If new, it will return ID of category. Othwerwise, it returns boolean.
     *
     * @return mixed
     * @access public
     */
    public function save()
    {
        if (isset($this->id_cat)) {
            $query = sprintf("
                UPDATE
                    %s

                    po
                SET
                    id_parent   = %d,
                    name        = %s,
                    permalink   = %s,
                    description = %s,
                    tpl_name    = %s,
                    enabled     = %d
                WHERE
                    id_cat      = %d",
                
                TBL_POSTCATS,
                $this->id_parent,
                $this->quotenull($this->name),
                $this->quotenull($this->permalink),
                $this->quotenull($this->description),
                $this->quotenull($this->tpl_name),
                $this->enabled ? 1 : -1,
                $this->id_cat
            );
        } else {
            $query = sprintf("
                INSERT INTO
                    %s
                SET
                    id_parent   = %d,
                    name        = %s,
                    permalink   = %s,
                    description = %s,
                    tpl_name    = %s,
                    enabled     = %d",
                
                TBL_POSTCATS,
                $this->id_parent,
                $this->quotenull($this->name),
                $this->quotenull($this->permalink),
                $this->quotenull($this->description),
                $this->quotenull($this->tpl_name),
                $this->enabled ? 1 : -1
            );
        }

        $this->db->exec($query);

        if (isset($this->id_cat)) {
            return true;
        } else {
            $this->id_cat = $this->db->lastInsertId();
            return $this->id_cat;
        }
    }

    /**
     * Read category data from DB
     *
     * @param integer $id
     *
     * @return boolean
     *
     * @access public
     */
    public function setFromDB($id)
    {
        $query = sprintf("
            SELECT
                id_cat,
                id_parent,
                name,
                permalink,
                description,
                tpl_name,
                enabled
            FROM
                %s
            WHERE
                id_cat = %d",
            
            TBL_POSTCATS,
            $id
        );
        try {
            $stmt = $this->db->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new CEDBError($e->getMessage(), 500);
        }

        $result['id_cat']    = (int)$result['id_cat'];
        $result['id_parent'] = (int)$result['id_parent'];
        $result['enabled']   = ($result['enabled'] == 1);

        return $this->setFromArray($result);
    }

}

?>
