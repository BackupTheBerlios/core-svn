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
     * Constructor
     *
     * Checks for valid parameters, and initializes proper options.
     *
     * @param string $enc_from character set of files (present)
     * @param string $enc_to   output character set
     * @param integer $comp_level
     *
     * @access public
     */
    public function __construct($enc_from='iso-8859-2', $enc_to='utf-8', $comp_level=5)
    {
        $start_opts = array();

        if ($comp_level > 0) {
            $comp = $this->_init_compress($comp_level);
            if ($comp !== false) {
                $start_opts[] = $comp;
            }
        }
        if ($enc_from != $enc_to) {
            $enc = $this->_init_encoding($enc_from, $enc_to);
            if ($enc !== false) {
                $start_opts[] = $enc;
            }
        }

        if (count($start_opts) > 0) {
            ob_start($start_opts);
            $this->_initialized = true;
        } else {
            $this->_initialized = false;
        }
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
}

?>

