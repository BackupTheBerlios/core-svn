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
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_exceptions.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL$
 */

/**
 * Abstract base class for all exception classes
 *
 * @category   Classes
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_exceptions.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL$
 */
abstract class CEBase extends Exception {

    /**
     * Constructor
     *
     * Execute it's parent constructor only
     *
     * @param string $message error message
     * @param int    $code    error code
     *
     * @access public
     */
    public function __construct($message, $code)
    {
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
            "%s::%d:: %s\n%s",
            $class,
            $this->getCode(),
            $this->getMessage(),
            $this->getTraceAsString()
        );
    }
}


/**
 * Not found exception
 *
 * Exception class used to report, that something wasn't found.
 *
 * @category   Classes
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_exceptions.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL$
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
 * Syntax error exception
 *
 * Exception class used to report, that somewhere is a coding problem with a
 * correct syntax of file.
 *
 * @category   Classes
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_exceptions.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL$
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
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_exceptions.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL$
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

/**
 * Incorrect type error
 *
 * Exception raised when function/method received any param of other type
 * then expected.
 *
 * @category   Classes
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_exceptions.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL$
 */
class CETypeError extends CEBase {

    /**
     * Constructor
     *
     * @param string $message error message
     * @param int    $code    error code
     *
     * @return void
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
 * Filesystem error
 *
 * @category   Classes
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_exceptions.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL$
 */
class CEFileSystemError extends CEBase {

    /**
     * Constructor
     *
     * @param string $message error message
     * @param int    $code    error code
     *
     * @return void
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
 * Upload error
 *
 * @category   Classes
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_exceptions.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL$
 */
class CEUploadError extends CEBase {

    /**
     * Constructor
     *
     * @param string $message error message
     * @param int    $code    error code
     *
     * @return void
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
 * Read only error
 *
 * @category   Classes
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_exceptions.php 1270 2006-02-26 11:13:34Z lark $
 * @link       $HeadURL$
 */
class CEReadOnly extends CEBase {

    /**
     * Constructor
     *
     * @param string $message error message
     * @param int    $code    error code
     *
     * @return void
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
