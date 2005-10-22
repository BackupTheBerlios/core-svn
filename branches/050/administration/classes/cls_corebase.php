<?php

class CoreBase
{
    var $error = array();
    var $debug = false;

    function CoreBase()                                            //KONSTRUKTOR
    {
        global $i18n;

        $view =& view::instance();
        $this->db =& $view->db;

        $this->i18n =& $i18n;
    }

    function error_set($msg)
    {
        if (is_array($msg))
        {
            $this->error = array_merge($this->error, $msg);
        }
        else
        {
            $this->error[] = (string)$msg;
        }

        return true;
    }
    function error_get($last = false)
    {
        if ((bool)count($this->error))
        {
            if ($last)
            {
                return end($this->error);
            }
            return $this->error;
        }
        return array();
    }
    function error_clear()
    {
        $this->error = array();
        return true;
    }
    function is_error()
    {
        return (bool)count($this->error);
    }
}

?>
