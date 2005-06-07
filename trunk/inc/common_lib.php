<?php

// automatyczne sprawdzanie stanu magic_quotes
// i w zaleznosci od tego wstawianie addslashes, badz nie.
if(!get_magic_quotes_gpc()) {
    if (function_exists('array_walk_recursive')) {

        function core_addslashes($k, $v) {
            return addslashes($v);
        }
        array_walk_recursive($_GET, 'core_addslashes');
        array_walk_recursive($_POST, 'core_addslashes');
        array_walk_recursive($_COOKIE, 'core_addslashes');
        @reset($_GET);
        @reset($_POST);
        @reset($_COOKIE);
    } else {

        if(is_array($_GET)) {
            foreach($_GET as $k => $v) {
                if(is_array($_GET[$k])) {
                    foreach ($_GET[$k] as $k2 => $v2) {
                        $_GET[$k][$k2] = addslashes($v2);
                    }
                    @reset($_GET[$k]);
                } else {
                    $_GET[$k] = addslashes($v);
                }
            }
            @reset($_GET);
        }
        
        if(is_array($_POST)) {
            foreach ($_POST as $k => $v) {
                if(is_array($_POST[$k])) {
                    foreach ($_POST[$k] as $k2 => $v2) {
                        $_POST[$k][$k2] = addslashes($v2);
                    }
                    @reset($_POST[$k]);
                } else {
                    $_POST[$k] = addslashes($v);
                }
            }
            @reset($_POST);
        }
        
        if(is_array($_COOKIE)) {
            foreach ($_COOKIE as $k => $v) {
                if(is_array($_COOKIE[$k])) {
                    foreach( $_COOKIE[$k] as $k2 => $v2) {
                        $_COOKIE[$k][$k2] = addslashes($v2);
                    }
                    @reset($_COOKIE[$k]);
                } else {
                    $_COOKIE[$k] = addslashes($v);
                }
            }
            @reset($_COOKIE);
        }
    }
}

?>