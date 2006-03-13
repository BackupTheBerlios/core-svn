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
}

?>
