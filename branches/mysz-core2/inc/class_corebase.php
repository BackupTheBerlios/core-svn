<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Provide abstract base class for other classes.
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
 * Abstract base class.
 *
 * Will be inherited in all classes, which aren't to internal use. For
 * example, exceptions classes will not be inherited from this class,
 * but these of classes which be used to playing with an posts etc,
 * will be inherited from CoreBase.
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

abstract class CoreBase {
    
    /**
     * All error messages
     *
     * An aray which store all error messages
     *
     * @var array
     * @access protected
     */
    protected $errors = array();

    /**
     * Check that any error occurrence
     *
     * @return bool
     * 
     * @access public
     */
    public function is_error()
    {
        return (bool)count($this->errors);
    }
    
    /**
     * Add an error message
     *
     * Add an error message to internal array
     *
     * @param string $message contains message
     * @param int    $code    contains error code
     * 
     * @return bool
     *
     * @access protected
     */
    protected function error_set($msg, $code = 0)
    {
        $errors[] = array($code, $msg);
        return true;
    }

    /**
     * Gets error message or messages
     *
     * @param bool $last determine that has to return all or just last message
     *
     * @return string last message or array of all messages
     *
     * @access public
     */
    public function error_get($last = true)
    {
        if($last) {
            return end($this->errors);
        }
        return $this->errors;
    }
    
    /**
     * Clears messages array
     *
     * @return bool
     *
     * @access public
     */
    public function error_clear()
    {
        $this->errors = array();
        return true;
    }
}

?>

