<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// $Id$
// $HeadURL$

abstract class Arrays {
    static public function flat($array)
    {
        $arr = array();
        foreach ($array as $a) {
            if (is_array($a)) {
                $arr = array_merge($arr, array_values(Arrays::flat($a)));
            } else {
                $arr[] = $a;
            }
        }
        return $arr;
    }

    public static function debug($array, $exit=false, $funcName='print_r')
    {
        echo '<pre>';
        if (!function_exists($funcName)) {
            $funcName = 'print_r';
        }
        $funcName($array);
        echo '</pre>';
        if ($exit) {
            exit;
        }
    }
}

?>
