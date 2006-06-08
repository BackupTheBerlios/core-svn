<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// SVN: $Id$
// $HeadURL$

class PostAggregate implements ArrayAccess, Countable, Iterator
{
    protected static $db = null;
    protected $type;
    protected $container = array();

    public function __construct($type=null, $start=null, $offset=false, $where=1)
    {
        self::$db = CoreDB::init();

        if (is_null($type)) {
            throw new CESyntaxError('Select type of aggregate.');
        }
        if (!class_exists($type)) {
            throw new CETypeError('Cannot find specified type of aggregate.');
        }

        $this->type = $type;

        $this->load($start, $offset, $where);
    }

    public function load($start=0, $offset=false, $where = '1')
    {
        $id_group = 1;
        $this->container = array();

        $query = sprintf("
            SELECT
                *
            FROM
                %s
            WHERE
                id_group = %d
            AND
                (
                    %s
                )
            ORDER BY
                date_add
            DESC",
            
            TBL_POSTS,
            $id_group,
            $where
        );
        if (false !== $offset) {
            $query .= sprintf("
                LIMIT %d, %d",

                $start,
                $offset
            );
        }

        $stmt = self::$db->query($query);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        foreach ($stmt as $data) {
            settype($data['id_post'], 'int');
            settype($data['id_parent'], 'int');
            settype($data['id_cat'], 'int');
            settype($data['id_group'], 'int');
            settype($data['id_menu'], 'int');

            $this->container[ $data['id_post'] ] = new $this->type($data);
        }

        return true;
    }

    //ArrayAccess methods
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->container);
    }
    public function offsetGet($offset)
    {
        if (!array_key_exists($offset, $this->container)) {
            throw new CENotFound(sprintf('Index "%s" doesn\'t exists.', $offset));
        }
        return $this->container[$offset];
    }
    public function offsetSet($offset, $data)
    {
        return $this->container[$offset] = $data;
    }
    public function offsetUnset($offset)
    {
        try {
            $this->$offset;
            unset($this->container[$offset]);
        } catch (CENotFound $e) { //do nothing
        }
    }

    //Countable method
    public function count()
    {
        return count($this->container);
    }

    //Iterator methods
    public function current()
    {
        return current($this->container);
    }
    public function key()
    {
        return key($this->container);
    }
    public function next()
    {
        return next($this->container);
    }
    public function rewind()
    {
        return reset($this->container);
    }
    public function valid()
    {
        return current($this->container);
    }
}

?>
