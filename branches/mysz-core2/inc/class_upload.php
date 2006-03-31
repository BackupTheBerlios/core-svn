<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Class for uploading files
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
 * @link       $HeadURL$
 */

/**
 * Class for uploading files
 *
 * Class uses sessions to store informations about uploded files (if has to 
 * be stored in temporary directory).
 *
 * Error codes:
 * 100 - CESyntaxError      Property "%s" is read only.
 * 200 - CETypeError        Incorrect file type "%s".
 * 201 - CETypeError        WARNING: file extension "%s" is not supposed to be
 *                          an "%s" mime type!
 * 300 - CEFileSystemError  "%s" is in tmp dir already.
 * 301 - CEFileSystemError  Cannot write to "%s" directory.
 * 302 - CEFileSystemError  File "%s" already exists.
 * 400 - CENotFound         Incorrect field name "%s".
 * 401 - CENotFound         File not found.
 * 402 - CENotFound         Property "%s" doesn't exists.
 * 600 - CEUploadError      Error occured at upload, one of defined in
 *                          Upload::$errors
 * 601 - CEUploadError      File size exceeds allowed (%dk) size.
 * 602 - CEUploadError      Cannot delete not uploaded file.
 * 603 - CEUploadError      Cannot write in temporary directory (%s).
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id$
 * @link       $HeadURL$
 */
class Upload
{
    /**
     * Constant - max size of uploaded file (in kilobytes)
     */
    const MAX_SIZE = 2048;

    /**
     * Name of field in form of type 'file'
     *
     * @var string
     * @access public
     */
    public $fieldName;

    /**
     * Name of session variable where infos about uploaded file are stored
     *
     * @var string
     * @access public
     */
    public $sessionName = 'upload';

    /**
     * Temporary directory (if used)
     *
     * @var string
     * @access public
     */
    public $tmpDir      = '';

    /**
     * Destination directory (where files are stored)
     *
     * @var string
     * @access public
     */
    public $saveDir     = '';

    /**
     * Properties of uploaded file
     *
     * @var array
     * @access protected
     */
    protected $properties = array(
        'type'      => '',
        'size'      => 0,
        'name'      => '',
        'tmpName'   => '',
        'error'     => 0
    );

    /**
     * File types handled by class
     *
     * As a key, is an file extension, and value is corresponding to
     * file extension mime type.
     * If in different places have to be used different sets of filetypes,
     * you must subclass this class and change value of Upload::$types property.
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $types = array(
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
    );

    /**
     * Array maps error codes of file uploading to error messages
     *
     * @var array
     * @access protected
     * @static
     */
    protected static $errors = array(
        UPLOAD_ERR_OK           => 'No error occured',
        UPLOAD_ERR_INI_SIZE     => 'File size exceeds specified value',
        UPLOAD_ERR_FORM_SIZE    => 'File size exceeds specified value',
        UPLOAD_ERR_PARTIAL      => 'Unspecified error occured.',
        UPLOAD_ERR_NO_FILE      => 'File not found.',
        UPLOAD_ERR_NO_TMP_DIR   => 'Temporary directory doesn\'t exists.',
        UPLOAD_ERR_CANT_WRITE   => 'Cannot write file to disk.'
    );

    /**
     * Constructor
     *
     * Open an file, if $fieldName is not null (using Upload::open()),
     * and sets values of Upload::$saveDir and Upload::$tmpDir.
     *
     * All params can be null, or string.
     *
     * @param mixed $fieldName name of form field
     * @param mixed $saveDir   destination directory
     * @param mixed $tmpDir    temporary directory
     *
     * @access public
     */
    public function __construct($fieldName=null, $saveDir=null, $tmpDir=null)
    {
        if (!is_null($saveDir)) {
            $this->saveDir = realpath($saveDir);
        }
        if ('' == $this->saveDir) {
            $this->saveDir = getcwd();
        }
        
        if (!is_null($tmpDir)) {
            $this->tmpDir = realpath($tmpDir);
        }
        if ('' == $this->tmpDir) {
            $this->tmpDir = getcwd();
        }
        
        if (!is_null($fieldName)) {
            $this->open($fieldName);
        }
    }

    /**
     * Open an fileand set properties of it
     *
     * @param string $fieldName name of form field
     *
     * @return boolean
     * @throws CENotFound {@link CENotFound description}
     * @throws CEUploadError {@link CEUploadError description}
     * @throws CETypeError {@link CETypeError description}
     *
     * @access public
     */
    public function open($fieldName)
    {
        $this->fieldName = $fieldName;

        $tmp = $this->isTmp();
        if ($tmp) {
            $this->properties = $_SESSION[$this->sessionName][$this->fieldName];
        } elseif (!isset($_FILES[$fieldName])) {
            throw new CENotFound(sprintf('Incorrect field name "%s".',
                $fieldName
            ), 400);
        } else {
            $this->properties['type']    = $_FILES[$fieldName]['type'];
            $this->properties['size']    = round($this->properties['size']/1024, 2);
            $this->properties['name']    = $_FILES[$fieldName]['name'];
            $this->properties['tmpName'] = $_FILES[$fieldName]['tmp_name'];
            $this->properties['error']   = $_FILES[$fieldName]['error'];

            if (UPLOAD_ERR_OK != $this->error) {
                switch ($this->error) {
                    case UPLOAD_ERR_NO_FILE:
                        throw new CENotFound(
                            'File not found.',
                            401
                        );
                    default:
                        throw new CEUploadError(
                            self::$errors[$this->error],
                            600
                        );
                }
            }
        }

        $ext = pathinfo($this->name, PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        if (!in_array($this->type, self::$types) ||
                !array_key_exists($ext, self::$types)) {
            throw new CETypeError(sprintf('Incorrect file type "%s".',
                $this->type
            ), 200);
        }
        $type = self::$types[$ext];
        if ($type != $this->type) {
            throw new CETypeError(sprintf('WARNING: file extension "%s" is ' .
                    'not supposed to be an "%s" mime type!',
                $ext,
                $type
            ), 201);
        }

        if ($this->size > self::MAX_SIZE) {
            throw new CEUploadError(sprintf('File size exceeds allowed (%dk) size.',
                self::MAX_SIZE
            ), 601);
        }

        return true;
    }

    /**
     * Shortcut for reopening file
     *
     * Open file without changing any properties (for example, when we open,
     * delete temporary file, and want to open again for newly uploaded file).
     *
     * More info about returning and throwing is in description of {@link Upload::open()}.
     *
     * @return boolean
     *
     * @access public
     */
    public function reopen()
    {
        return $this->open($this->fieldName);
    }

    /**
     * Check for $fieldName is name of form field to use to, or temporary file
     *
     * @return mixed path to file (if is temporary file), or false (if not)
     *
     * @access public
     */
    public function isTmp()
    {
        if (isset($_SESSION[$this->sessionName][$this->fieldName])) {
            $prop = $_SESSION[$this->sessionName][$this->fieldName];

            if (is_file($prop['tmpName'])) {
                return $prop['tmpName'];
            } else {
                unset($_SESSION[$this->sessionName][$this->fieldName]);
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Move uploaded file to temporary directory
     *
     * Use addtional methods, {@link Upload::preSave()} and {@link Upload::postSave()}
     * for other operations, needed on uploaded file (like check for proper
     * size etc).
     *
     * Params $preSave and $postSave decides did execute pre i post testing
     * for uploaded file (default both of them are disabed).
     *
     * @param boolean $preSave
     * @param boolean $postSave
     *
     * @return boolean
     * @throws CEFileSystemError {@link CEFileSystemError description}
     * @throws CEUploadError {@link CEUploadError description}
     *
     * @access public
     */
    public function toTmp($preSave=false, $postSave=false)
    {
        $tmp_path = $this->isTmp();
        if ($tmp_path) {
            throw new CEFileSystemError(sprintf('"%s" is in tmp dir already.',
                basename($tmp_path)
            ), 300);
        }

        $this->checkTmp();
        $dest = Path::join($this->tmpDir, basename($this->tmpName));
        
        if ($preSave) {
            $preSave = $this->preSave();
            if (!is_null($preSave)) {
                throw new CEUploadError($preSave[0], $preSave[1]);
            }
        }
        $ret = move_uploaded_file($this->tmpName, $dest);
        if ($postSave) {
            $postSave = $this->postSave();;
            if (!is_null($postSave)) {
                throw new CEUploadError($postSave[0], $postSave[1]);
            }
        }
        if ($ret) {
            $this->properties['tmpName'] = $dest;
            $_SESSION[$this->sessionName][$this->fieldName] = $this->properties;
            return true;
        }
        return false;
    }

    /**
     * Remove file stored in temporary directory
     *
     * @param boolean $silent if true, don't raise exception, just return false
     *
     * @return boolean
     * @throws CEUploadError {@link CEUploadError description}
     *
     * @access public
     */
    public function delTmp($silent=false)
    {
        $p = $this->isTmp();
        if ($p) {
            unlink($p);
            unset($_SESSION[$this->sessionName][$this->fieldName]);
            return true;
        } else {
            if ($silent) {
                return false;
            } else {
                throw new CEUploadError('Cannot delete not uploaded file.', 602);
            }
        }
    }

    /**
     * Save uploaded file in destionation directory
     *
     * Use addtional methods, {@link Upload::preSave()} and {@link Upload::postSave()}
     * for other operations, needed on uploaded file (like check for proper
     * size etc).
     *
     * @param string  $dest destination directory (if other then {@link Upload::$saveDir})
     * @param boolean $genName use original filename to store, or generated
     * @param boolean $overwrite overwrite file if destination file already exists?
     *
     * @return boolean
     * @throws CEFileSystemError {@link CEFileSystemError description}
     * @throws CEUploadError {@link CEUploadError description}
     *
     * @access public
     */
    public function save($dest=null, $genFname=false, $overwrite=false)
    {
        if (is_null($dest)) {
            $dest = $this->saveDir;
        }
        $dest = realpath($dest);

        if ('' == $dest || !is_writeable($dest)) {
            throw new CEFileSystemError(sprintf('Cannot write to "%s" ' .
                'directory.',
                $dest
            ), 301);
        }
        
        
        if ($genFname) {
            $fname = $this->generateFname();
        } else {
            $fname = $this->name;
        }

        $path = Path::join($dest, $fname);
        
        if (is_file($path)) {
            if ($overwrite) {
                unlink($path);
            } else {
                throw new CEFileSystemError(sprintf('File "%s" already ' .
                    'exists.',
                    $path
                ), 302);
            }
        }

        $preSave = $this->preSave();
        if (!is_null($preSave)) {
            throw new CEUploadError($preSave[0], $preSave[1]);
        }
        if (isset($_SESSION[$this->sessionName][$this->fieldName])) {
            unset($_SESSION[$this->sessionName][$this->fieldName]);
        }
        $ret = rename($this->tmpName, $path);
        $postSave = $this->postSave();;
        if (!is_null($postSave)) {
            throw new CEUploadError($postSave[0], $postSave[1]);
        }

        return $ret;
    }

    /**
     * Overloaded setter
     *
     * File properties must be read-only.
     *
     * @param string $k property name
     * @param mixed  $v property value
     *
     * @throws CESyntaxError {@link CESyntaxError description}
     * @throws CENotFound {@link CENotFound description}
     *
     * @access public
     */
    public function __set($k, $v)
    {
        if (array_key_exists($k, $this->properties)) {
            throw new CESyntaxError(sprintf('Property "%s" is read only.',
                $k
            ), 100);
        } else {
            throw new CENotFound(sprintf('Property "%s" doesn\'t exists.',
                $k
            ), 402);
        }
    }

    /**
     * Overloaded getter
     *
     * @param string $k property name
     *
     * @return mixed value of property
     * @throws CENotFound {@link CENotFound description}
     *
     * @access public
     */
    public function __get($k)
    {
        if (array_key_exists($k, $this->properties)) {
            return $this->properties[$k];
        } else {
            throw new CENotFound(sprintf('Property "%s" doesn\'t exists.',
                $k
            ), 402);
        }
    }

    /**
     * Check for temporary directory existent and writeability
     *
     * @param boolean $silent if false, raise an exception on fail
     *
     * @return boolean
     * @throws CEUploadError {@link CEUploadError description}
     *
     * @access protected
     */
    protected function checkTmp($silent=false)
    {
        if (is_dir($this->tmpDir) && is_writeable($this->tmpDir)) {
            return true;
        } else {
            if ($silent) {
                return false;
            } else {
                throw new CEUploadError(sprintf('Cannot write in temporary ' .
                        'directory (%s).',
                    $this->tmpDir
                ), 603);
            }
        }
    }

    /**
     * Generate filename
     *
     * @return string generated filename
     *
     * @access protected
     */
    protected function generateFname()
    {
        return sprintf('%s_%s', md5(mt_rand()), $this->name);
    }

    /**
     * Test uploaded file before saving
     *
     * This method is empty. If want to use it, try to extend this class
     * (by inheritance) and write Your own body of this function.
     *
     * It must return:
     *   - null:  if all tests are passed and everything is OK
     *   - array: if something goes wrong. It must contains 2 elements. First
     *            is a message with which exception is raised, and second is
     *            a error code. See doc/error_codes.txt for more.
     *
     * @return mixed
     * @access protected
     */
    protected function preSave()
    {
        return null;
    }

    /**
     * Test uploaded file after saving
     *
     * More info in {@link Upload::preSave()}.
     *
     * @return mixed
     * @access protected
     */
    protected function postSave()
    {
        return null;
    }

}

?>
