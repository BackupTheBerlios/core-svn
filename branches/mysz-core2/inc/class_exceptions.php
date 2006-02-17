<?php
// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Provide set of exception classes
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
 * Abstract base class for all exception classes
 *
 * @category   Classes
 * @package    Classess
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       http://core-cms.com/
 */
abstract class CEBase extends Exception {
    
    /**
     * Contsructor
     *
     * Execute it's parent constructor only
     *
     * @param string $message error message
     * @param int    $code    error code
     *
     * @access public
     */
    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }
    
    /**
     * Creates string describing the class.
     *
     * @param string $class classname
     *
     * @return string 
     *
     * @access protected
     */
    protected function toString($class) {
        return sprintf(
            '%s::%d:: %s', 
            $class, 
            $this->code, 
            $this->message
        );
    }
}


/**
 * Not found exception
 *
 * Exception class used to report, that something wasn't found.
 *
 * @category   Classes
 * @package    Classess
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       http://core-cms.com/
 */
class CENotFound extends CEBase {

    /**
     * Constructor
     *
     * @param string $message error message
     * @param int    $code    error code
     *
     * @access public
     */
    public function __construct($message, $code = null) {
        parent::__construct($message, $code);
    }
    
    /**
     * Overloaded function to create string describing the class.
     *
     * @return string 
     *
     * @access public
     */
    public function __toString() {
        return $this->toString(__CLASS__);
    }
}

/**
 * Syntax error exception
 *
 * Exception class used to report, that somewhere is a coding problem with a
 * correct syntax of file.
 *
 * @category   Classes
 * @package    Classess
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       http://core-cms.com/
 */
class CESyntaxError extends CEBase {
    
    /**
     * Constructor
     *
     * @param string $message error message
     * @param int    $code    error code
     *
     * @access public
     */
    public function __construct($message, $code = null)
    {
        parent::__construct($message, $code);
    }
    
    /**
     * Overloaded function to create string describing the class.
     *
     * @return string 
     *
     * @access public
     */
    public function __toString()
    {
        return $this->toString(__CLASS__);
    }
}


/**
 * Database error exception
 *
 * Exception class used to report, that is some sort of database access error.
 *
 * @category   Classes
 * @package    Classess
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       http://core-cms.com/
 */
class CEDBError extends CEBase {
    
    /**
     * Constructor
     *
     * @param string $message error message
     * @param int    $code    error code
     *
     * @access public
     */
    public function __construct($message, $code = null)
    {
        parent::__construct($message, $code);
    }
    
    /**
     * Overloaded function to create string describing the class.
     *
     * @return string 
     *
     * @access public
     */
    public function __toString()
    {
        return $this->toString(__CLASS__);
    }
}

?>

