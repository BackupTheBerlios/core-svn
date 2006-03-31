<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Class for operations on posts
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
 * @version    SVN: $Id: class_image.php 1299 2006-03-08 19:59:18Z mysz $
 * @link       $HeadURL: svn://svn.berlios.de/core/branches/mysz-core2/inc/class_image.php $
 */

/**
 * Class for operations on posts
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
 * @version    SVN: $Id: class_image.php 1299 2006-03-08 19:59:18Z mysz $
 * @link       $HeadURL: svn://svn.berlios.de/core/branches/mysz-core2/inc/class_image.php $
 */
final class Post extends CorePost
{
    /**
     * Constant
     *
     * Literal 'published' for use as status
     */
    const PUBLISHED = 'published';

    /**
     * Constant
     *
     * Literal 'draft' for use as status
     */
    const DRAFT     = 'draft';

    /**
     * Constant
     *
     * Literal 'disabled' for use as status
     */
    const DISABLED  = 'disabled';

    /**
     * Available values for entry status
     *
     * @var array
     *
     * @access protected
     * @static
     */
    protected static $status_array = array(self::PUBLISHED, self::DRAFT, self::DISABLED);

    /**
     * Post properties
     * 
     * More info: {@link CoreBase::$properties} and {@link CoreBase::$base_properties}.
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $base_properties = array(
        'sticky'    => array(null, 'boolean'),
        'cat_name'  => array(null, 'string'),
        'grp_name'  => array(null, 'string'),
        'id_group'  => array(null, 'integer'),
    );

    /**
     * List of external setters
     * 
     * More info: {@link CoreBase::$setExternal} and {@link CoreBase::$base_setExternal}.
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $base_setExternal = array('cat_name', 'grp_name');

    /**
     * List of external getters
     * 
     * More info: {@link CoreBase::$getExternal} and {@link CoreBase::$base_getExternal}.
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $base_getExternal = array();

    /**
     * All post properties together
     *
     * @var array
     * @access protected
     */
    protected $properties;

    /**
     * All post external getters together
     *
     * @var array
     * @access protected
     */
    protected $getExternal;

    /**
     * All post external setters together
     *
     * @var array
     * @access protected
     */
    protected $setExternal;

    /**
     * Constructor
     *
     * Merge some internal arrays (look for {@link CorePost::__construct} docs)
     * and set Post properties from arrays or database.
     *
     * If $data is integer, it will execute {@link Post::setFromDB()} method.
     * Else, if $data is an array, it will be passed to {@link Post::setFromArray()}.
     *
     * @param mixed $data
     *
     * @access public
     */
    public function __construct(&$data=null)
    {
        parent::__construct();

        $this->properties = array_merge(
            parent::$base_properties,
            self::$base_properties
        );
        $this->getExternal = array_merge(
            parent::$base_getExternal,
            self::$base_getExternal
        );
        $this->setExternal = array_merge(
            parent::$base_setExternal,
            self::$base_setExternal
        );

        if (is_array($data)) {
            $this->setFromArray($data);
        } elseif (is_int($data)) {
            $this->setFromDB($data);
        } else {
            $this->date_add = null;
            $this->date_mod = null;
        }
    }

    /**
     * External setter for category name - prevents from set category name here
     *
     * @param string $name
     *
     * @throws CEReadOnly
     *
     * @access protected
     */
    protected function set_cat_name($name)
    {
        throw new CEReadOnly('Read only property.');
    }
    
    /**
     * External setter for group name - prevents from set category name here
     *
     * @param string $name
     *
     * @throws CEReadOnly
     *
     * @access protected
     */
    protected function set_grp_name($name)
    {
        throw new CEReadOnly('Read only property.');
    }

    /**
     * Checks for correct value of status
     *
     * Proper values:
     *   - {@link Post::PUBLISHED}
     *   - {@link Post::DRAFT}
     *   - {@link Post::DISABLED}
     *
     * @param string $data status
     *
     * @return boolean
     * @throws CESyntaxError if incorrect type ({@link CoreBase::isType()})
     *
     * @access protected
     */
    protected function set_status(&$data)
    {
        $this->isType('status', $data);

        if (!in_array($data, self::$status_array)) {
            $this->errorSet(sprintf('Incorrect status "%s".', $data));
            return false;
        }
        $this->properties['status'][0] = $data;
    }

    /**
     * Get post data from database
     * 
     * @param integer $id post id
     *
     * @return boolean
     * @throws CEDBError
     *
     * @access public
     */
    public function setFromDB($id)
    {
        $query = sprintf("
            SELECT
                posts.id_post           AS id_post,
                posts.id_parent         AS id_parent,
                posts.id_cat            AS id_cat,
                posts.id_group          AS id_group,
                posts.id_section        AS id_section,
                posts.title             AS title,
                posts.permalink         AS permalink,
                posts.caption           AS caption,
                posts.body              AS body,
                posts.tpl_name          AS tpl_name,
                posts.author_name       AS author_name,
                posts.author_mail       AS author_mail,
                posts.author_www        AS author_www,
                posts.date_add          AS date_add,
                posts.date_mod          AS date_mod,
                posts.status            AS status,

                cats.name               AS cat_name,

                groups.grp_name         AS grp_name
            FROM
                %s posts
            LEFT JOIN
                    %s cats
                ON
                    cats.id_cat = posts.id_cat
            LEFT JOIN
                    %s groups
                ON
                    groups.id_group = posts.id_group
            WHERE
                posts.id_post = %d",
    
            TBL_POSTS,
            TBL_POSTCATS,
            TBL_POSTGROUPS,
            $id
        );

        try {
            $stmt = $this->db->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new CEDBError($e->getMessage(), 500);
        }
        
        $result['id_post']    = (int)$result['id_post'];
        $result['id_parent']  = (int)$result['id_parent'];
        $result['id_cat']     = (int)$result['id_cat'];
        $result['id_group']   = (int)$result['id_group'];
        $result['id_section'] = (int)$result['id_section'];

        return $this->setFromArray($result);
    }


    /**
     * Save post data in database
     *
     * On success, if we set insert new post, it will return ID of this
     * entry. Otherwise, return true.
     *
     * @return mixed
     * @throws CEDBError ({@link CEDBError description})
     *
     * @access public
     */
    public function save()
    {
        if (isset($this->id_post)) {
            $query = sprintf("
                UPDATE
                    %s
                SET
                    id_parent    = %d,
                    id_cat       = %d,
                    id_group     = %d,
                    id_section   = %d,
                    title        = %s,
                    permalink    = %s,
                    caption      = %s,
                    body         = %s,
                    tpl_name     = %s,
                    author_name  = %s,
                    author_mail  = %s,
                    author_www   = %s,
                    date_mod     = NOW(),
                    status       = %s
                WHERE   
                    id_post      = %d",
                
                TBL_POSTS,
                $this->id_parent,
                $this->id_cat,
                $this->id_group,
                $this->id_section,
                $this->quotenull($this->title),
                $this->quotenull($this->permalink),
                $this->quotenull($this->caption),
                $this->quotenull($this->body),
                $this->quotenull($this->tpl_name),
                $this->quotenull($this->author_name),
                $this->quotenull($this->author_mail),
                $this->quotenull($this->author_www),
                $this->quotenull($this->status),
                $this->id_post
            );
        } else {
            $query = sprintf("
                INSERT INTO
                    %s
                SET
                    id_parent    = %d,
                    id_cat       = %d,
                    id_group     = %d,
                    id_section   = %d,
                    title        = %s,
                    permalink    = %s,
                    caption      = %s,
                    body         = %s,
                    tpl_name     = %s,
                    author_name  = %s,
                    author_mail  = %s,
                    author_www   = %s,
                    date_add     = NOW(),
                    date_mod     = NOW(),
                    status       = %s",
                
                TBL_POSTS,
                $this->id_parent,
                $this->id_cat,
                $this->id_group,
                $this->id_section,
                $this->quotenull($this->title),
                $this->quotenull($this->permalink),
                $this->quotenull($this->caption),
                $this->quotenull($this->body),
                $this->quotenull($this->tpl_name),
                $this->quotenull($this->author_name),
                $this->quotenull($this->author_mail),
                $this->quotenull($this->author_www),
                $this->quotenull($this->status)
            );
        }

        try {
            $this->db->exec($query);
        } catch (PDOException $e) {
            throw new CEDBError($e->getMessage(), 500);
        }

        if (isset($this->id_post)) {
            return true;
        } else {
            $this->id_post = (int)$this->db->lastInsertId();
            return $this->id_post;
        }
    }
}

?>
