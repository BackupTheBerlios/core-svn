<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// $Id$
// $HeadURL$

abstract class Strings {
    public static function email($s)
    {
        return eregi('^([a-z0-9_]|\\-|\\.)+@(((([a-z0-9_]|\\-)+\\.)+[a-z]{2,4})|localhost)$', $s);
    }

    public static function login($s)
    {
        return preg_match('#^[a-z0-9-.,]{4,64}#i', $s);
    }

    public static function parse($s, $rettype='text', $wellformed=false)
    {
        $parser = new Parser;
        return $parser->parse($s, $rettype, $wellformed);
    }
            
    public static function br2nl($s, $nl="\r\n")
    {
        static $br = array('<br />', '<br>', '<br/>');
        return str_replace($br, $nl, $s);
    }

    public static function entities($s)
    {
        static $p = array('<',    '>',    '"',       "'");
        static $r = array('&lt;', '&gt;', '&quot;', '&#39;');
        return str_replace($p, $r, $s);
    }

    public static function htmlizelinks($s)
    {
        static $p = array(
          '#(^|[\n ])([\w]+?://[^ "\n\r\t<]*)#i',
          '#(^|[\n ])((www|ftp)\.[^ "\t\n\r<]*)#i',
          '#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i'
        );
        static $r = array(
          '\\1<a href="\\2">\\2</a>',
          '\\1<a href="http://\\2">\\2</a>',
          '\\1<a href="mailto:\\2@\\3">\\2@\\3</a>'
        );
	      return preg_replace($p, $r, $s);
    }

    public static function startswith($string, $prefix)
    {
        return (substr($string, 0, strlen($prefix)) == $prefix);
    }

    public static function endswith($string, $prefix)
    {
        return (substr($string, -strlen($prefix)) == $prefix);
    }

    public static function istartswith($string, $prefix)
    {
        return (strtolower(substr($string, 0, strlen($prefix))) == strtolower($prefix));
    }

    public static function iendswith($string, $prefix)
    {
        return (strtolower(substr($string, -strlen($prefix))) == strtolower($prefix));
    }

    public static function left($string, $width, $type=' ', $entit=true)
    {
        if ($entit) {
            $len = strlen($string);
            if ($width > $len) {
                $width = ($width - $len)*strlen($type) + $len;
            }
        }
        return str_pad($string, $width, $type, STR_PAD_RIGHT);
    }
    public static function right($string, $width, $type=' ', $entit=true)
    {
        if ($entit) {
            $len = strlen($string);
            if ($width > $len) {
                $width = ($width - $len)*strlen($type) + $len;
            }
        }
        return str_pad($string, $width, $type, STR_PAD_LEFT);
    }
    public static function center($string, $width, $type=' ', $entit=true)
    {
        if (!$entit) {
            return str_pad($string, $width, $type, STR_PAD_BOTH);
        }

        $len   = strlen($string);

        $left  = ceil(($width-$len)/2);
        $right = $width - $len - $left;

        $left  = implode('', array_pad(array(), $left,  $type));
        $right = implode('', array_pad(array(), $right, $type));

        return $left . $string . $right;
    }
}

?>
