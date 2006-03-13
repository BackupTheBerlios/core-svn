<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// $Id: index.php 1303 2006-03-08 20:31:39Z mysz $
// $HeadURL: svn://svn.berlios.de/core/branches/mysz-core2/index.php $

final class Post extends CorePost
{
    const ORDER_DESC = 'DESC';
    const ORDER_ASC  = 'ASC';

    protected static $base_properties = array(
        'sticky'    => array(null, 'boolean'),
        'cat_name'  => array('', 'string'),
        'grp_name'  => array('', 'string')
    );

    protected static $base_setExternal = array('cat_name', 'grp_name');

    protected static $base_getExternal = array();

    protected $properties; //  = array();
    protected $getExternal = array();
    protected $setExternal = array();
    

    public function __construct(&$data)
    {
        parent::__construct($data);

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

    private function set_cat_name($name)
    {
        throw new CESyntaxError('Category name cannot be change from Post object.');
    }
    
    private function set_grp_name($name)
    {
        throw new CESyntaxError('Category name cannot be change from Post object.');
    }

    public function save()
    {
    }
    public function setFromDB($id, $order=self::ORDER_DESC, $where=null)
    {
        if (!in_array($order, array(self::ORDER_DESC, self::ORDER_ASC))) {
            $order = self::ORDER_DESC;
        }

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
                posts.author_www        AS auhtor_www,
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
    }

    public function show() {
        $it = $this->getIter();
        foreach ($it as $k=>$prop) {
            printf('<b>%s</b>: %s<br />', $k, $prop);
        }
    }
}

?>
