<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// SVN: $Id$
// $HeadURL$


class User extends CoreBase
{
    protected $properties = array(
        'id'            => array(null,    'integer'),
        'login'         => array(null,    'string'),
        'passwd'        => array(null,    'string'),
        'fname'         => array(''  ,    'string'),
        'lname'         => array(''  ,    'string'),
        'perms'         => array(null,    'array'),
        'enabled'       => array(true,    'boolean'),
        'meta'          => array(array(), 'array'),
    );
    protected static $setExternal = array('login', 'passwd', 'perms', 'enabled');
    protected static $getExternal = array();
  
    public function __construct()
    {
        parent::__construct();
        $this->getMetaFromDB();
    }
    protected function set_login($data)
    {
        if (!Validate::login($data)) {
            throw new CEIncorrectData(sprintf('Login "%s" is incorrect.', $data));
        }
        settype($data, self::properties['login'][1]);
        $this->properties['login'][0] = $data;
    }
    protected function set_password($data)
    {
        if (!Validate::password($data)) {
            throw new CEIncorrectData(sprintf('Password "%s" is incorrect.', $data));
        }
        settype($data, sha1(self::properties['passwd'][1]));
        $this->properties['passwd'][0] = $data;
    }
    protected function set_enabled($data)
    {
        $this->properties['enabled'] = (bool)$data;
    }
    protected function set_perms($data)
    {
        throw new CESyntexError(sprintf('%s->set_perms() method isn\'t set.', __class__));
        $this->properties['perms'] = $data;
    }

    protected function getMetaFromDB()
    {
        $query = sprintf("
            SELECT
                id_meta,
                key,
                value
            FROM
                %s
            WHERE
                    id_entry = %d
                AND
                    type = 'user'",
                
            TBL_META,
            $this->id
        );
        try {
            $stmt = $this->db->query($query);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (PDOException $e) {
            throw new CEDBError($e->getMessage());
        }
        foreach ($stmt as $meta) {
            $this->properties['meta'][$meta['key']] = array($meta['value'], $meta['id_meta']);
        }

        return true;
    }
    public function getMeta($key)
    {
        return $this->properties['meta'][$key][0];
    }
    public function setMeta($key, $value)
    {
        $this->properties['meta'][$key] = array($value, false);
        return true;
    }

    public function save()
    {
        if (!isset($this->login)) {
            $this->errorSet('Login cannot be empty.');
        }
        if (!isset($this->password)) {
            $this->errorSet('Password cannot be empty.');
        }
        if (!isset($this->perms)) {
            $this->errorSet('Permissions aren\'t set.');
        }

        if ($this->isError()) {
            return false;
        }
        if (isset($this->id)) { //update
            $query = sprintf("
                UPDATE
                    %s
                SET
                    login   = %s,
                    passwd  = %s,
                    perms   = %s,
                    lname   = %s,
                    fname   = %s,
                    enabled = %d
                WHERE
                    id_user = %d",
                
                TBL_USERS,
                $this->db->quote($this->login),
                $this->db->quote($this->passwd),
                serialize($this->perms),
                $this->db->quote($this->lname),
                $this->db->quote($this->fname),
                $this->enabled ? 1 : -1,
                $this->id
            );
        } else {
            $query = sprintf("
                INSERT INTO
                    %s
                SET
                    login   = %s,
                    passwd  = %s,
                    perms   = %s,
                    lname   = %s,
                    fname   = %s,
                    enabled = %d",
                
                TBL_USERS,
                $this->db->quote($this->login),
                $this->db->quote($this->passwd),
                serialize($this->perms),
                $this->db->quote($this->lname),
                $this->db->quote($this->fname),
                $this->enabled ? 1 : -1
            );
        }
        $this->db->exec($query);
        $this->id = (int)$this->db->lastInsertId();

        $meta &= $this->properties['meta']; //shortcut
        if (!count($meta)) {
            return $this->id;
        }

        //zrobic rozdzielone dwa 'wielokrotne query (insert into ... (f1, f2) values (1, 2), (2,3) ) dla meta z bazy i dla nowych
        $query = sprintf("
            
        ");

        foreach ($meta as $
        $query = sprintf("
            
        ");
    }


}

?>
