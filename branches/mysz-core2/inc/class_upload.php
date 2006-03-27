<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// $Id$
// $HeadURL$

/**
 * Error codes:

 */
class Upload
{
    const MAX_SIZE = 2048;

    protected $useTmp   = true;
    public $fieldName;
    public $sessionName = 'upload';
    public $tmpDir      = '';
    public $saveDir     = '';

    protected $properties = array(
        'type'      => '',
        'size'      => 0,
        'name'      => '',
        'tmpName'   => '',
        'error'     => 0
    );

    protected static $types = array(
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'txt'  => 'text/plain',
        'html' => 'text/html'
    );

    protected static $errors = array(
        UPLOAD_ERR_OK           => 'No error occured',
        UPLOAD_ERR_INI_SIZE     => 'File size exceeds specified value',
        UPLOAD_ERR_FORM_SIZE    => 'File size exceeds specified value',
        UPLOAD_ERR_PARTIAL      => 'Unspecified error occured.',
        UPLOAD_ERR_NO_FILE      => 'File not found.',
        UPLOAD_ERR_NO_TMP_DIR   => 'Temporary directory doesn\'t exists.',
        UPLOAD_ERR_CANT_WRITE   => 'Cannot write file to disk.'
    );

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

    public function open($fieldName)
    {
        $this->fieldName = $fieldName;

        $tmp = $this->isTmp();
        if ($tmp) {
            $this->properties = $_SESSION[$this->sessionName][$this->fieldName];
        } elseif (!isset($_FILES[$fieldName])) {
            throw new CENotFound(sprintf('Incorrect field name "%s".',
                $fieldName
            ));
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
                            self::$errors[$this->error],
                            $this->error
                        );
                    default:
                        throw new CEUploadError(
                            self::$errors[$this->error],
                            $this->error
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
            ));
        }
        $type = self::$types[$ext];
        if ($type != $this->type) {
            throw new CETypeError(sprintf('WARNING: file extension "%s" is ' .
                    'not supposed to be an "%s" mime type!',
                $ext,
                $type
            ));
        }

        if ($this->size > self::MAX_SIZE) {
            throw new CEUploadError(sprintf('File size exceeds allowed (%dk) size.',
                self::MAX_SIZE
            ));
        }
    }

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
    
    public function toTmp()
    {
        $tmp_path = $this->isTmp();
        if ($tmp_path) {
            throw new CEFileSystemError(sprintf('"%s" is in tmp dir already.',
                basename($tmp_path)
            ));
        }

        $this->checkTmp();
        $dest = Path::join($this->tmpDir, basename($this->tmpName));
        $ret = move_uploaded_file($this->tmpName, $dest);
        if ($ret) {
            $this->properties['tmpName'] = $dest;
            $_SESSION[$this->sessionName][$this->fieldName] = $this->properties;
            return true;
        }
        return false;
    }

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
                throw new CEUploadError('Cannot delete not uploaded file.');
            }
        }
    }

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
            ));
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
                ));
            }
        }

        if (isset($_SESSION[$this->sessionName][$this->fieldName])) {
            unset($_SESSION[$this->sessionName][$this->fieldName]);
        }
        return rename($this->tmpName, $path);
    }

    protected function generateFname()
    {
        return sprintf('%s_%s', md5(mt_rand()), $this->name);
    }

    public function __set($k, $v)
    {
        if (array_key_exists($k, $this->properties)) {
            throw new CESyntaxError(sprintf('Property "%s" is read only.',
                $k
            ));
        } else {
            throw new CENotFound(sprintf('Property "%s" doesn\'t exists.',
                $k
            ));
        }
    }

    public function __get($k)
    {
        if (array_key_exists($k, $this->properties)) {
            return $this->properties[$k];
        } else {
            throw new CENotFound(sprintf('Property "%s" doesn\'t exists.',
                $k
            ));
        }
    }

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
                ));
            }
        }
    }

    protected function isFile()
    {
        return is_file($this->tmpName);
    }

}

?>
