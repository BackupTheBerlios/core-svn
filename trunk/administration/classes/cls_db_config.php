<?php
// $Id$

class db_config {
    
    var $view;
    
    /**
     * Constructor
     */
    function db_config() {
        $this->view =& view::instance();
    }
    
    /**
     * Get config value
     * @param $name - config name
     * @return config value
     */
    function get_config($name) {
        
        if(RDBMS == '4.1') {
            if(!defined('STATEMENT_SET')) {
                $query = sprintf("
                    PREPARE 
                        get_config 
                    FROM 'SELECT 
                        config_value 
                    FROM 
                        %1\$s 
                    WHERE 
                        config_name = ?'", 
        
                    TABLE_CONFIG
                );
                $this->view->db->query($query);
            
                $query = sprintf("SET @config_name = '%1\$s'", $name);
                $this->view->db->query($query);
            
                $query = "EXECUTE get_config USING @config_name";
            
                // define statement - true
                define('STATEMENT_SET', true);
            } else {
                $query = sprintf("SET @config_name = '%1\$s'", $name);
                $this->view->db->query($query);
            
                $query = "EXECUTE get_config USING @config_name";
            }
        } else {
            $query = sprintf("
                SELECT
                    config_value
                FROM
                    %1\$s
                WHERE
                    config_name = '%2\$s'",
          
                TABLE_CONFIG,
                $name
            );
        }

        $this->view->db->query($query);
        $this->view->db->next_record();

        return $this->view->db->f('config_value');
    }
    
    
    /**
     * Set config value
     * @param $name - config name
     * @param $value - config value
     * @return set config value
     */
    function set_config($name, $value) {

        $query = sprintf("
            UPDATE
                %1\$s
            SET
                config_value = '%2\$s'
            WHERE
                config_name = '%3\$s'",
          
            TABLE_CONFIG,
            $value,
            $name
        );

        $this->view->db->query($query);

        return true;
    }
    
}

?>
