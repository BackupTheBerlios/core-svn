<?php
// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Class for post meta properties
 *
 * PHP version 5
 *
 * This file is internal part of Core CMS (http://core-cms.com/) engine.
 *
 * Copyright (C) 2004-2005 Core Dev Team (more info: docs/AUTHORS).
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published
 * by the Free Software Foundation; version 2 only.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_corebase.php 1275 2006-02-28 15:58:36Z mysz $
 * @link       $HeadURL: svn://svn.berlios.de/core/branches/mysz-core2/inc/class_corebase.php $
 */

/**
 * Class for easy getting data from user via GET, POST or COOKIE method.
 *
 * Error codes:
 *  10 - CEReadOnly         Read only variable "%s".
 * 100 - CESyntaxError      Variable name needed.
 * 101 - CESyntaxError      Unknown method "%s".
 * 102 - CESyntaxError      Deleting properties not allowed.
 * 400 - CENotFound         Unknown or read only property "%s".
 * 401 - CENotFound         Unknown object or method.
 * 402 - CENotFound         Unknown function "%s".
 * 403 - CENotFound         Invalid source.
 * 404 - CENotFound         Key "%s" not found.
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_corebase.php 1275 2006-02-28 15:58:36Z mysz $
 * @link       $HeadURL: svn://svn.berlios.de/core/branches/mysz-core2/inc/class_corebase.php $
 */
final class getGPC
{
    /**
     * source: GET array
     */
    const GET                       = 1;
    /**
     * source: POST array
     */
    const POST                      = 2;
    /**
     * source: COOKIE array
     */
    const COOKIE                    = 4;
    /**
     * source: auto
     */
    const AUTO                      = 8;

    /**
     * getGPC object
     *
     * @var object
     * @access private
     * @static
     */
    private static $getGPC          = null;

    /**
     * Copy of superglobal $_GET
     *
     * @var array
     * @access private
     */
    private $_GET                   = array();

    /**
     * Copy of superglobal $_POST
     *
     * @var array
     * @access private
     */
    private $_POST                  = array();

    /**
     * Copy of superglobal $_COOKIE
     *
     * @var array
     * @access private
     */
    private $_COOKIE                = array();

    /**
     * Available data types
     *
     * @var array
     * @access private
     */
    private $_retTypes              = array('string', 'int', 'bool', 'double');

    /**
     * Properties array
     *
     * Store default properties for getting data
     *
     * @var array
     * @access private
     */
    private $_properties            = array(
        '__source'              => self::AUTO,
        '__html'                => false,
        '__sql'                 => false,
        '__magicQuotes'         => true,
        '__sqlEscapeFun'        => 'mysql_real_escape_string',
    );

    /**
     * Singleton method for get getGPC instance
     *
     * @param integer $source       one of self::{GET,POST,COOKIE,AUTO}
     * @param boolean $html         replace dangerous chars with htmlentities?
     * @param boolean $sql          escape data for sql queries?
     * @param string  $sqlEscapeFun function used to escape data for sql queries
     *
     * @return object getGPC object
     *
     * @access public
     */
    public static function init($source=null, $html=null, $sql=null,
                                $sqlEscapeFun=null)
    {
        if (is_null(self::$getGPC)) {
            self::$getGPC = new getGPC($source, $html, $sql, $sqlEscapeFun);
        }
        return self::$getGPC;
    }

    /**
     * Constructor
     *
     * Copy superglobals $_{GET,POST,COOKIE} to internal
     * getGPC::$_{GET,POST,COOKIE} and set these superglobals to null.
     * Also set default properties. 
     *
     * @param integer $source       one of self::{GET,POST,COOKIE,AUTO}
     * @param boolean $html         replace dangerous chars with htmlentities?
     * @param boolean $sql          escape data for sql queries?
     * @param string  $sqlEscapeFun function used to escape data for sql queries
     *
     * @access private
     */
    private function __construct($source, $html, $sql, $sqlEscapeFun)
    {
        $this->_GET     = $_GET;
        $this->_POST    = $_POST;
        $this->_COOKIE  = $_COOKIE;

        $this->_properties['__magicQuotes'] = get_magic_quotes_gpc();

        if (!is_null($source)) {
            $this->__source         = $source;
        }
        if (!is_null($html)) {
            $this->__html           = $html;
        }
        if (!is_null($sql)) {
            $this->__sql            = $sql;
        }
        if (!is_null($sqlEscapeFun)) {
            $this->__sqlEscapeFun   = $sqlEscapeFun;
        }

        $_GET       = null;
        $_POST      = null;
        $_COOKIE    = null;
    }

    /**
     * Overloaded setter
     *
     * Allow to set only default properties of getGPC instance.
     *
     * @param string $k
     * @param mixed  $v
     *
     * @return mixed
     * @throws CENotFound if property doesn't exists.
     *
     * @access public
     */
    public function __set($k, $v)
    {
        $fun = '_set_' . substr($k, 2);
        if ('__' == substr($k, 0, 2)) {
            if (method_exists($this, $fun)) {
                return call_user_func_array(array($this, $fun), $v);
            } else {
                throw new CENotFound(sprintf('(%d) Unknown property "%s".', __line__, $k), 400);
            }
        } else {
            throw new CEReadOnly(sprintf('(%d) Read only variable "%s".', __line__, $k), 10);
        }

    }

    /**
     * Overloaded getter
     *
     * As default, use getGPC::getString() method (created via getGPC::__call())
     * with default properties.
     *
     * @param string $k
     *
     * @return boolean
     *
     * @access public
     */
    public function __get($k)
    {
        if ('__' == substr($k, 0, 2) && array_key_exists($k, $this->_properties)) {
            return $this->_properties[$k];
        }

        return $this->getString($k, null, $this->__source,
                                $this->__html, $this->__sql);
    }

    /**
     * Overloaded __call function for dynamic creating methods
     *
     * Used as getGPC::get{getGPC::$_retTypes}() methods.
     *
     * @param string $fun  name of called method
     * @param array  $args method parameters
     *
     * @return mixed
     * @throws CESyntaxError
     *
     * @access public
     */
    public function __call($fun, $args)
    {
        if (0 == count($args)) {
            throw new CESyntaxError(sprintf('(%d) Variable name needed.', __line__), 100);
        }

        $type = strtolower(substr($fun, 3));

        //method name is also type of returning value. Allowad types are in getGPC::$_retTypes.
        if (!in_array($type, $this->_retTypes)) {
            throw new CESyntaxError(sprintf('(%d) Unknown method "%s".', __line__, $fun), 101);
        }

        //default arguments for called method: variable name, source, escape html entities,
        //escape for sql queries, use as sprintf
        $defArgs = array(null, null, null, false, false, false);

        //replace default properties with user data
        foreach ($args as $k=>&$arg) {
            $defArgs[$k] =& $arg;
        }

        //string used as first parameter in sprintf()
        $syntax = array_pop($defArgs);

        
        if (is_array($defArgs[0])) { //if user want to get more than one variable at time.
            $ret = array();
            $vars = $defArgs[0];
            foreach ($vars as $key=>$name) {
                //we can use 0 index of $defArgs as container to store getted variable
                // - we don't use it's index anymore.
                //it allows us to not create additional array
                $defArgs[0] = $this->_getVar($name, $defArgs[1], $defArgs[2]);
                $ret[$key]  = call_user_func_array(array($this, '_prepareData'), $defArgs);
                settype($ret[$key], $type);
            }

        } else {
            //comments like above
            $defArgs[0] = $this->_getVar($defArgs[0], $defArgs[1], $defArgs[2]);
            $ret = call_user_func_array(array($this, '_prepareData'), $defArgs);
            settype($ret, $type);
        }

        if (is_string($syntax)) {
            if (!is_array($ret)) {
                $ret = array($ret);
            }
            array_unshift($ret, $syntax);
            $ret = call_user_func_array('sprintf', $ret);
        }

        return $ret;
    }

    /**
     * Overloaded __isset()
     *
     * Returns boolean did isset needed variable.
     *
     * @param string $k varibale name
     *
     * @return boolean
     *
     * @access public
     */
    public function __isset($k)
    {
        try {
            $this->_getVar($k, null, self::AUTO);
            return true;
        } catch (CESyntaxError $e) {
            return false;
        }
    }

    /**
     * Overloaded __usnet()
     *
     * Disable deleting variables
     *
     * @param string $k
     *
     * @throws CESyntaxError
     *
     * @access public
     */
    public function __unset($k)
    {
        throw new CESyntaxError(sprintf('(%d) Deleting properties not allowed.', __line__), 102);
    }


    /**
     * Set default source data.
     *
     * Used internall to check validity of source and to set source data.
     * Available sources are:
     *   - getGPC::GET
     *   - getGPC::POST
     *   - getGPC::COOKIE
     *   - getGPC::AUTO
     *
     * @param integer @source
     *
     * @return null
     * @throws CESyntaxError
     *
     * @access private
     */
    private function _set_source($source)
    {
        if (!$this->_checkSource($source)) {
            throw new CESyntaxError(sprintf('(%d) Invalid source.', __line__), 403);
        }
        $this->_properties['__source'] = $source;
    }

    /**
     * Set function or method used to escaping data for sql queries
     *
     * Can be neither function or some object method. If function, pass
     * function name as $fun parameter, if method, use an array with objects
     * reference as first array element, and methods name as string in second
     * element if $fun.
     *
     * @param mixed $fun
     *
     * @return null
     * @throws CENotFound
     *
     * @access private
     */
    private function _set_SqlEscapeFun($fun)
    {
        if (is_array($fun)) { //dla np. PDO::quote() trzeba wywolywac metode obiektu
            if (!is_object($fun[0]) || !method_exists($fun[0], $fun[1])) {
                throw new CENotFound(sprintf('(%d) Unknown object or method.', __line__), 401);
            }
        } else if (!function_exists($fun)) {
            throw new CENotFound(sprintf('(%d) Unknown function "%s".', __line__, $fun), 402);
        }

        $this->_properties['__sqlEscapeFun'] = $fun;
    }

    /**
     * Set default property $html
     *
     * @param boolean $data
     *
     * @return null
     *
     * @access private
     */
    private function _set_html($data)
    {
        $this->_properties['__html'] = (bool)$data;
    }

    /**
     * Set default property $sql
     *
     * @param boolean $data
     *
     * @return null
     *
     * @access private
     */
    private function _set_sql($data)
    {
        $this->_properties['__sql'] = (bool)$data;
    }


    /**
     * Validate source type
     *
     * @param integer $src
     *
     * @return boolean
     *
     * @access private
     */
    private function _checkSource($src)
    {
        return in_array($src, array(self::AUTO, self::POST, self::GET, self::COOKIE));
    }


    /**
     * Prepare data and return escape variable
     *
     * @param string  $var variable name
     * @param mixed   $default default value if variable not found
     * @param integer $source 
     * @param boolean $html escape to html entities?
     * @param boolean $sql escape for use in sql queries?
     *
     * @return mixed
     * 
     * @access private
     */
    private function _prepareData($var, $default, $source, $html, $sql)
    {
        //skoro jest równe wartosci domyslnej, to user siê spodziewa tego co otrzyma
        //albo ¿¹da³ nieprzetworzonej zmiennej
        if ($var === $default) {
            return $var;
        }

        if ($this->__magicQuotes) {
            $var = stripslashes($var);
        }
        if ($html || (is_null($html) && $this->__html) ) {
            $var = str_replace(
                array('<', '>', '"', "'"),
                array('&lt;', '&gt;', '&quot;', '&#39;'),
                $var
            );
        }
        if ($sql || (is_null($sql) && $this->__sql) ) {
            $var = call_user_func_array($this->__sqlEscapeFun, $var);
        }

        return $var;
    }

    /**
     * Get variable from proper array (method)
     *
     * If source set to getGPC::AUTO, we seek first in $_POST, next in $_COOKIE
     * and the last (but not least ;) ) in $_GET. First founded variable is
     * returned (without preparing - just return value of variable).
     * If variable not founded, but $default is set != null, return $default,
     * in other case raise CENotFound exception.
     *
     * @param string $name
     * @param mixed $default
     * @param integer $source
     *
     * @return mixed
     * @throws CESyntaxError
     *
     * @access private
     */
    private function _getVar($name, $default=null, $source=null)
    {
        if (!$this->_checkSource($source)) {
            $source = $this->__source;
        }

        $var = null;
        switch ($source) {
            case self::POST:
                if (array_key_exists($name, $this->_POST)) {
                    $var = $this->_POST[$name];
                }
            break;
            case self::GET:
                if (array_key_exists($name, $this->_GET)) {
                    $var = $this->_GET[$name];
                }
            break;
            case self::COOKIE:
                if (array_key_exists($name, $this->_COOKIE)) {
                    $var = $this->_COOKIE[$name];
                }
            break;
            default:
                if (array_key_exists($name, $this->_POST)) {
                    $var = $this->_POST[$name];
                } else if (array_key_exists($name, $this->_COOKIE)) {
                    $var = $this->_COOKIE[$name];
                } else if (array_key_exists($name, $this->_GET)) {
                    $var = $this->_GET[$name];
                }
        }
        if (!is_null($var)) { //variable founed, we return them
            return $var;
        }

        if (is_null($default)) { //if default not provided, we will raise an exception
            throw new CENotFound(sprintf('(%d) Key "%s" not found.', __line__, $name), 404);
        } else {
            return $default;
        }
    }


    /**
     * Return iterator after all properties.
     *
     * Use default properties to preparing data.
     * If source == getGPC::AUTO, we use $_REQUEST array.
     *
     * @param integer $source
     * @param boolean $html
     * @param boolean $sql
     *
     * @return object ArrayIterator
     * @throws CENotFound
     *
     * @access public
     */
    public function getIterator($source=self::AUTO, $html=null, $sql=null)
    {
        if (!$this->_checkSource($source)) {
            throw new CENotFound(sprintf('(%d) Invalid source.', __line__), 403);
        }

        switch ($source) {
            case self::POST:    $data = $this->_POST;       break;
            case self::GET:     $data = $this->_GET;        break;
            case self::COOKIE:  $data = $this->_COOKIE;     break;
            case self::AUTO:    $data = $this->_REQUEST;    break;
        }

        foreach ($data as &$var) {
            $var = $this->_prepareData($var, null, $source, $html, $sql);
        }

        return new ArrayIterator($data);
    }

    /**
     * Overloaded method to forbid cloning of instance
     *
     * @throws CESyntaxError always when invoked
     * @access public
     */
    public function __clone()
    {
        throw new CESyntaxError('Singleton cloning not allowed.');
    }
}

?>
