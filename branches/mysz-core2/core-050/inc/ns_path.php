<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// $Id: ns_path.php 1327 2006-03-27 21:44:59Z mysz $
// $HeadURL: https://lark@svn.berlios.de/svnroot/repos/core/branches/mysz-core2/inc/ns_path.php $

abstract class Path
{
    const SEPARATOR = DIRECTORY_SEPARATOR;

    public static function join()
    {
        $argv = Arrays::flat(func_get_args());

        $path = array();
        while (list(, $c) = each($argv)) {
            $c = Path::split($c);
            if (preg_match('#^[a-z]:$#', $c[0])) {
                $path = array();
            }
            $path = array_merge($path, $c);
        }

        return implode(self::SEPARATOR, $path);
    }

    public static function split($path)
    {
        $path = Path::normalize($path);

        $ret = array();
        // we cut drive letter if os == windows and put it as first element
        if ('\\' == self::SEPARATOR &&  //windows
                preg_match('#^([a-z]:\\\\)#i', $path, $match)) {
            $ret[] = substr($match[1], 0, 2);
            $path = str_replace($match[1], '', $path);
        }

        return array_merge($ret, explode(self::SEPARATOR, $path));
    }

    public static function normalize($path)
    {
        static $p = array('#\\\\#', '#/#');
        static $r = array('/', self::SEPARATOR);
        $path = preg_replace($p, $r, $path);

        if ('\\' == self::SEPARATOR) { //windows
            $path = strtolower($path);
        }
        return $path;
    }

    public static function real($path)
    {
        $path = Path::split($path);
        $newpath = array();
        while (list(, $v) = each($path)) {
            switch ($v) {
                case '.' :
                    continue;
                case '..':
                    array_pop($newpath);
                    continue;
                default:
                    $newpath[] = $v;
            }
        }
        return Path::join($newpath);
    }
}

?>
