<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

class PostMeta extends CoreBase
{
    $properties = array(
        'sticky' => array(false, 'boolean'),
    );

    public function __construct(&$data=null)
    {
        parent::__construct();

        if (is_int($data)) {
            $this->setFromDB($data);
        } elseif (is_array($data)) {
            $this->setFromArray($data);
        }
    }

    public function save()
    {
        $exists = new PostMeta($this->id_meta);
        $query = sprintf("
            
        ");
    }
    public function setFromDB(&$id)
    {
        $query = sprintf("
            SELECT
                id_meta,
                key,
                value
            FROM
                %s
            WHERE
                id_post = %d",
            
            TBL_POSTMETA,
            $id
        );

        $result = array();
        try {
            $stmt = $this->db->query($query);
            foreach ($stmt as $data) {
                $result[$data['key']] = $data['value'];
            }
        } catch (PDOException $e) {
            throw new CEDBError($e->getMessage(), 500);
        }
        return $this->setFromArray($result);
    }
}

?>
