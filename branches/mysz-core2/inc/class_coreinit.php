<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Provide initialization class for Core CMS.
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
 * @version    SVN: $Id$
 * @link       http://core-cms.com/
 */

/**
 * Initialization class for Core CMS.
 *
 * Sets compressing (via zlib extension) output of scripts, converts encoding
 * of output from character set in files to defined encoding (ex. files like
 * code or templates can be in ISO-8859-2, but output will be in UTF-8).
 *
 * Cannot be superclass for any class (final).
 *
 * Will be inherited in all classes, which aren't to internal use. For
 * example, exceptions classes will not be inherited from this class,
 * but these of classes which be used to playing with an posts etc,
 * will be inherited from CoreBase.
 *
 * Have to be instantiated like:
 * try {
 *     new CoreInit('iso-8859-2', 'utf-8', 5);
 * } catch (Exception $e) {
 *     echo $e->getMessage();
 *     exit;
 * }
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       http://core-cms.com/
 */
final class CoreInit
{
    /**
     * Holds status of output buffering.
     *
     * @var boolean
     * @access private
     */
    private $_initialized;

    /**
     * Enable/disable debug mode
     *
     * @var boolean
     * @access private
     */
    private $_debug = false;


    /**
     * Set compressing level.
     *
     * @param integer $comp_level
     *
     * @return string    name of compressing function name (for ob_start())
     * @throws Exception if zlib not loaded
     *
     * @access private
     */
    private function _init_compress($comp_level)
    {
        if (!extension_loaded('zlib')) {
            throw new Exception('Compressing failed: "zlib" isn\'t loaded.');
        }

        ini_set('zlib.compresss_level', $comp_level);
        return 'ob_gzhandler';
    }

    /**
     * Set encoding method.
     *
     * Checks for existing of proper extension: iconv or mbstring. If any
     * of them is loaded, proper handler for ob_start() is returned, either
     * false.
     *
     * @param string $enc_from character set of files (present)
     * @param string $enc_to   output character set
     *
     * @return string  name of handler function to converts character set
     * @throws Exception if none of valid extensions loaded
     *
     * @access private
     */
    private function _init_encoding($enc_from, $enc_to)
    {
        if (extension_loaded('iconv')) {
            iconv_set_encoding('internal_encoding', $enc_from);
            iconv_set_encoding('output_encoding', $enc_to);
            return 'ob_iconv_handler';
        } elseif (extension_loaded('mbstring')) {
            mb_internal_encoding($enc_from);
            mb_http_output($enc_to);
            return 'mb_output_handler';
        } else {
            throw new Exception(sprintf('Encoding to "%s" failed: neither' .
               ' "iconv" and "mbstring" isn\'t loaded.', $enc_to));
        }
    }

    /**
     * Set default options for php settings
     *
     * Set also error handler.
     */
    private function _init_phpsettings() {
        ini_set('arg_separator.output',        '&amp;');
        ini_set('arg_separator.input',         ';&');
        ini_set('include_path',      implode(PATH_SEPARATOR, $this->_inc_path)); //NFY. how to put here these variable ?
        ini_set('magic_quotes_gpc',            0);
        ini_set('magic_quotes_runtime',        0);
        ini_set('magic_quotes_sybase',         0);
        ini_set('register_globals',            0);
        ini_set('zend.ze1_compatibility_mode', 0);
        ini_set('variables_order',             'EGPCS');

        ini_set('sendmail_from',               'core@core-cms.com'); //from config. NFY
        ini_set('session.hash_function',       1);

        if ($this->_debug) {
          ini_set('error_reporting',           E_STRICT | E_ALL);
          ini_set('display_errors',            1);
          ini_set('mysql.trace_mode',          1);
          ini_set('error_log',                 null);
          ini_set('log_errors',                0);

          ini_set('html_errors',               1);
          ini_set('error_prepend_string',      '<span style="color: #ff0000">');
          ini_set('error_append_string',       '</span>');
        } else {
          ini_set('error_reporting',           E_ERROR | E_STRICT |
                                               E_USER_ERROR | E_USER_WARNING |
                                               E_USER_NOTICE);
          ini_set('display_errors',            0);
          ini_set('mysql.trace_mode',          0);
          ini_set('error_log',                 'C:/www/htdocs/testy/php.log'); //from config. NFY
          ini_set('log_errors',                1);

          set_error_handler(    array($this, 'error_handler'));
          set_exception_handler(array($this, 'error_handler'));
        }

        ini_set('memory_limit',                '2M'); //do przedyskutowania. from config, NFY
        ini_set('session.auto_start',          1); //do przedyskutowania
    }


    /**
     * Constructor
     *
     * Checks for valid parameters, and initializes proper options.
     *
     * @param string  $enc_from   character set of files (present)
     * @param string  $enc_to     output character set
     * @param integer $comp_level
     *
     * @access public
     */
    public function __construct($enc_from='iso-8859-2', $enc_to='utf-8', $comp_level=5)
    {
        if (defined('DEBUG') && DEBUG) {
            $this->_debug = true;
        }

        $ob_start_opts = array();

        if ($comp_level > 0) {
            $comp = $this->_init_compress($comp_level);
            if ($comp !== false) {
                $ob_start_opts[] = $comp;
            }
        }
        if ($enc_from != $enc_to) {
            $enc = $this->_init_encoding($enc_from, $enc_to);
            if ($enc !== false) {
                $ob_start_opts[] = $enc;
            }
        }

        if (count($ob_start_opts) > 0) {
            ob_start($ob_start_opts);
            $this->_initialized = true;
        } else {
            $this->_initialized = false;
        }

        $this->_init_phpsettings();
    }

    /**
     * Destructor
     *
     * If ob_start() was called, it call ob_flush().
     *
     * @access public
     */
    public function __destruct()
    {
        if ($this->_initialized) {
            ob_flush();
        }
    }

    /**
     * Error handler
     *
     * These method is set as error/exception handler for Core CMS.
     * If something is wrong, and debug mode is off, error messages
     * are logged into a file, and no error info is on main page.
     * Additionally, if error type is E_STRICT, E_ERROR or E_USER_ERROR,
     * an email is sending to admin (if his address is set).
     *
     * WARNING: Fatal errors aren't handled by this function - PHP
     * doesn't allow this.
     *
     * @param mixed $errno   int if we are handling errors, object if exceptions
     * @param mixed $errmsg  int for errors, otherwise null
     * @param mixed $errfile string for errors, otherwise null
     * @param mixed $errline int for errors, otherwise null
     * @param mixed $vars    all available variables in error context
     *
     * @access public
     */
    public function error_handler($errno, $errmsg=null, $errfile=null,
                                  $errline=null, $vars=null)
    {
        if (is_object($errno)) {    // if we handle an exception
            $errmsg  = sprintf('Uncaught exception (%s): %s',
                get_class($errno),
                $errno->getMessage()
            );
            $errfile = $errno->getFile();
            $errline = $errno->getLine();
            $errcode = $errno->getCode();
        } else {                    // if we handle error
            $errtype = array (
                E_ERROR           => 'Error',
                E_WARNING         => 'Warning',
                E_PARSE           => 'Parsing Error',
                E_NOTICE          => 'Notice',
                E_CORE_ERROR      => 'Core Error',
                E_CORE_WARNING    => 'Core Warning',
                E_COMPILE_ERROR   => 'Compile Error',
                E_COMPILE_WARNING => 'Compile Warning',
                E_USER_ERROR      => 'User Error',
                E_USER_WARNING    => 'User Warning',
                E_USER_NOTICE     => 'User Notice',
                E_STRICT          => 'Runtime Notice'
            );
            $errcode = $errtype[$errno];
        }


        // Error messages have links to reference manual. We won't them.
        $errmsg = preg_replace('# \[<a href=.*?</a>\]#', '', $errmsg);

        $msg1 = array(
            'Core CMS Error:',
            'Type: ' . $errcode,
            'File: ' . $errfile,
            'Line: ' . $errline,
            'Error message: ' . $errmsg
        );
        $msg1 = implode("\n\t", $msg1); //version for logs
        $msg2 = implode("\n\t", array(  //version for email
            $msg1, '', '',
            'All variables:', '',
            print_r($vars, 1))
        );

        // in_array() can be quicker, but switch() is more extendable - in
        // future we may want to handle additional actions for any type of
        // error
        switch ($errno) {
            case E_STRICT:
            case E_ERROR:
            case E_USER_ERROR:
                if (defined('ADMIN_MAIL')) {
                    @mail(ADMIN_MAIL, 'Core CMS Error: ' . $errcode, $msg2);
                }
                break;
        }

        error_log($msg1, 0); // we want to log into specified file
                             // (see: CoreInit::_init_phpsettings(())
    }
}

?>

