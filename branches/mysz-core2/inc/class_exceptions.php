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
 * @version    SVN: $Id$
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
 * @version    SVN: $Id$
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
    public function __construct($message, $code=null)
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
     * @access public
     */
    public function __toString() {
        return sprintf(
            "%s\nFile: %s\nLine: %s\nCode: %s\n%s",
            get_class($this),
            $this->getFile(),
            $this->getLine(),
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
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */
class CENotFound extends CEBase {}

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
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */
class CESyntaxError extends CEBase {}

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
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */
class CEDBError extends CEBase {}

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
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */
class CETypeError extends CEBase {}

/**
 * Filesystem error
 *
 * @category   Classes
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */
class CEFileSystemError extends CEBase {}

/**
 * Upload error
 *
 * @category   Classes
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */
class CEUploadError extends CEBase {}

/**
 * Read only error
 *
 * @category   Classes
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */
class CEReadOnly extends CEBase {}

/**
 * Incorrect data
 *
 * @category   Classes
 * @package    Exceptions
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */
class CEIncorrectData extends CEBase {}

?>
