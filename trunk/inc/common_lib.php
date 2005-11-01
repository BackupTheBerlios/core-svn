<?php
// $Id$

// automatyczne sprawdzanie stanu magic_quotes
// i w zaleznosci od tego wstawianie addslashes, badz nie.
if(!get_magic_quotes_gpc()) {
    if(is_array($_GET)) {
        while(list($k, $v) = each($_GET)) {
            if(is_array($_GET[$k])) {
                while(list($k2, $v2) = each($_GET[$k])) {
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
        while(list($k, $v) = each($_POST)) {
            if(is_array($_POST[$k])) {
                while(list($k2, $v2) = each($_POST[$k])) {
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
        while(list($k, $v) = each($_COOKIE)) {
            if(is_array($_COOKIE[$k])) {
                while(list($k2, $v2) = each($_COOKIE[$k])) {
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


function replace_amp($s) {
    return str_replace('&', '&amp;', $s);
}


function check_mail($email) {
    return eregi("^([a-z0-9_]|\\-|\\.)+@(((([a-z0-9_]|\\-)+\\.)+[a-z]{2,4})|localhost)$", $email);
}




function str_getext($file, $with_dot = true) {
	
	$p = pathinfo($file);
    if (!isset($p['extension']))
    {
        return '';
    }
    return $with_dot ? '.' . $p['extension'] : $p['extension'];
}



function str_entit($s) {

    $p = array('<', '>', '"', "'");
    $r = array('&lt;', '&gt;', '&quot;', '&#39;');

    return str_replace($p, $r, $s);
}


function get_root() {
	
	return ROOT;
}
function get_httproot($with_slash = true) {

  $d = dirname($_SERVER['REQUEST_URI']);
  $path = sprintf('%s%s', $_SERVER['HTTP_HOST'], $d == '/' ? '' : $d);
  if ($with_slash) {

    $path .= '/';
  }

  return $path;
}


function v_array($array, $exit = 0) { 
	
	printf('<pre>%s</pre>', print_r($array, 1));
	
	if($exit){
		exit;
	}
}


function str_br2nl($s, $nl = "\r\n") {
    return str_replace(array('<br />', '<br>', '<br/>'), $nl, $s);
}


function str_nl2br($s) {
	return str_replace(array("\r\n", "\r", "\n"), '<br />', $s);
}


// stronnicowanie 
function pagination($url, $mainposts_per_page, $num_items) {

    global $ft, $start;

    $ret = array();
    $total_pages = '';
	// Obliczanie liczby stron
	if($mainposts_per_page > 0) {
	    
	    if($num_items > $mainposts_per_page) {
	        $total_pages = ceil($num_items/$mainposts_per_page);
	    }
	    // Obliczanie strony, na której obecnie jestesmy
		$on_page = floor($start / $mainposts_per_page) + 1;
	} else {
		$total_pages = 0;
		$on_page = 0;
	}
	
	$page_string = '';
	
	if($total_pages > 6) {
	    $init_page_max = ($total_pages > 3) ? 3 : $total_pages;
	    for($i = 1; $i < $init_page_max + 1; $i++) {
	        $page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . $url . (($i - 1) * $mainposts_per_page) . '">' . $i . '</a>';
	        if($i <  $init_page_max) {
	            $page_string .= ", ";
	        }
	    }
	    
	    if($total_pages > 3) {
	        if($on_page > 1  && $on_page < $total_pages) {
	            $page_string .= ($on_page > 5) ? ' ... ' : ', ';
	            
	            $init_page_min = ($on_page > 4) ? $on_page : 5;
	            $init_page_max = ($on_page < $total_pages - 4) ? $on_page : $total_pages - 4;
	            
	            for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++) {
	                $page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . $url . (($i - 1) * $mainposts_per_page) . '">' . $i . '</a>';
	                if($i < $init_page_max + 1) {
	                    $page_string .= ', ';
	                }
	            }
	            
	            $page_string .= ($on_page < $total_pages - 4) ? ' ... ' : ', ';
	        } else {
	            $page_string .= ' ... ';
	        }
	        
	        for($i = $total_pages - 2; $i < $total_pages + 1; $i++) {
	            $page_string .= ($i == $on_page) ? '<b>' . $i . '</b>'  : '<a href="' . $url . (($i - 1) * $mainposts_per_page) . '">' . $i . '</a>';
	            if($i < $total_pages) {
	                $page_string .= ", ";
	            }
	        }
	    }
	} else {
	    
	    for($i = 1; $i < $total_pages + 1; $i++) {
	        $page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . $url . (($i - 1) * $mainposts_per_page) . '">' . $i . '</a>';
	        if($i <  $total_pages) {
	            $page_string .= ', ';
	        }
	    }
	}
	
	if($on_page > 1) {
	    $ft->assign(array(
            'MOVE_BACK'      =>true, 
            'MOVE_BACK_LINK' =>$url.(($on_page - 2)*$mainposts_per_page)
        ));
	} else {
	    $ft->assign('MOVE_BACK', false);
	}
	
	if($on_page < $total_pages) {
	    $ft->assign(array(
            'MOVE_FORWARD'      =>true, 
            'MOVE_FORWARD_LINK' =>$url.($on_page*$mainposts_per_page)
        ));
	} else {
	    $ft->assign('MOVE_FORWARD', false);
	}
	
	$ret['page_string'] = $page_string;
	
	return $ret;
}


function highlighter($text, $geshi_lang) {

    include_once('geshi/geshi.php');

    $geshi_path = 'geshi/geshi';
    $geshi = new geshi;

    $langs = array(
        'actionscript', 'ada', 'apache', 'asm', 'asp', 
        'bash', 
        'c', 'c_mac', 'caddcl', 'cadlisp', 'cpp', 'csharp', 'css', 
        'd', 'delphi', 'diff', 
        'html4strict', 
        'java', 'javascript', 
        'lisp', 'lua', 
        'matlab', 'mpasm', 
        'nsis', 
        'objc', 'oobas', 'oracle8', 
        'pascal', 'perl', 'php', 'php-brief', 'python', 
        'qbasic', 
        'smarty', 'sql', 
        'vb', 'vbnet', 'vhdl', 'visualfoxpro', 
        'xml'
    );

    $geshi_lang = in_array($geshi_lang, $langs) ? $geshi_lang : 'php';

    $source_lines = explode("\n", $text);
    foreach($source_lines as $line) {

        $line = stripslashes($line);
        $line = str_replace(array('&lt;', '&gt;', '&amp;', '<br />'), array('<', '>', '&', "\n"), trim($line));
        $line = geshi_highlight($line, $geshi_lang, $geshi_path, TRUE);
        $text = $line;
    }

    return $text;
}


function get_mysql_server_version() {

    $dbs = explode('.', @mysql_get_server_info());
    define('RDBMS', $dbs[0] == '4' && $dbs[1] == '1' ? '4.1' : '4.0');
}


// template check
function prepare_template($lang, $i18n) {

    if(isset($_COOKIE['devlog_design']) && is_dir('./templates/' . $lang. '/' . $_COOKIE['devlog_design'] . '/tpl/')){
        $theme = $_COOKIE['devlog_design'];
    } elseif (is_dir('./templates/' . $lang . '/main/tpl')) {
        $theme = 'main';
    } else {
        printf('<div style="font-family: Arial, sans-serif; font-size: 16px; background-color: #ccc; border: 1px solid red; padding: 15px; text-align: center;">%s</div>', $i18n['design'][0]);
        exit;
    }

    return $theme;
}


function random() {

    list($usec, $sec) = explode(' ', microtime());
    $seed = (float) $sec + ((float) $usec * 100000);
    mt_srand($seed);

    return md5(mt_rand());
}

function get_config($name) {

    $db = new DB_SQL;

    if (!defined('RDBMS'))
    {
        define('RDBMS', '4.0');
    }

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
            $db->query($query);

            $query = sprintf("SET @config_name = '%1\$s'", $name);
            $db->query($query);

            $query = "EXECUTE get_config USING @config_name";

            /*
             * TODO:
             * czy tutaj ta stala nie powinna nieco inaczej wygladac ?
             * zakladam, ze powyzszy sposob, czyli korzystae z prepared
             * statements bedziemy wykorzystywac szerzej. wiec nie moze byc
             * jedna stala o nieznaczacej zbyt wiele nazwie, i zeby sie ja
             * dalo wykorzystac szerzej, do wiekszej ilosci statementsow
             *
             * poza tym, nie mam teraz jak, ale warto by sprawdzic, jak dlugo
             * prepared statements 'utrzymuja' sie w bazie. bo zakladam ze dluzej 
             * niz okres zycia tej stalej ? jesli nie, to korzystanie z tego jest
             * malo wygodne, i sensowne w tym wypadku.
             *
             * jesli prepared statements zyja wystarzajaca dlugo, np tyle samo
             * co otwarte polaczenie z sql, to moze warto wrzucac w jakas tablice
             * do sesji wartosci bool, czy dana 'przygotowana stala' zostala juz
             * zdefiniowana. 
             * 
             */
            // definicja warunku::true
            define('STATEMENT_SET', true);
        } else {
            $query = sprintf("SET @config_name = '%1\$s'", $name);
            $db->query($query);

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

    $db->query($query);
    $db->next_record();

    return $db->f('config_value');
}
function set_config($name, $value) {

    $db = new DB_SQL;

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

    $db -> query($query);

    return true;
}


function pathstripslashes(&$s)
{
    $s = preg_replace('#^([/|\\\\]+)?(.*?)([/|\\\\]+)?$#', '\\2', $s);
    return $s;
}
function pathjoin()
{
    $arg_quant  = func_num_args();
    if ($arg_quant == 0)
    {
        return '';
    }

    $args       = func_get_args();
    $new_args   = array();
    foreach ($args as $arg)
    {
        if (is_array($arg))
        {
            $new_args   = array_merge($new_args, $arg);
        }
        else
        {
            $new_args[] = $arg;
        }
    }

    array_walk($new_args, 'pathstripslashes');

    return implode(DIRECTORY_SEPARATOR, $new_args);
}

function _pathsplit($s, $slash)
{
    switch ($slash)
    {
        case '/':       $splits = array('/');       break;
        case '\\':      $splits = array('\\');      break;
        case 'all':     $splits = array('/', '\\'); break;
        default:        return false;
    }

    $len = strlen($s);
    $ret = array();
    $word = '';

    for ($i=0; $i<$len; $i++)
    {
        $char = $s[$i];

        if (in_array($char, $splits))
        {
            if (!empty($word))
            {
                $ret[] = $word;
            }
            $word = '';
        }
        else
        {
            $word .= $char;
        }
    }

    if (!empty($word))
    {
        $ret[] = $word;
    }

    return $ret;
}
function pathsplit($s, $slash = DIRECTORY_SEPARATOR)
{
    $ret = array();
    if (is_array($s))
    {
        foreach ($s as $path)
        {
            $ret = array_merge($ret, _pathsplit($path, $slash));
        }
    }
    else
    {
        $ret = array(_pathsplit($s, $slash));
    }

    return $ret;
}

?>
