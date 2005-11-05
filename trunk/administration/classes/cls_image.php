<?php
// $Id$

/*
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
 */

class Image extends CoreBase
{
    //deklaracje wlasciwosci
    var $width          = 0;
    var $height         = 0;
    var $title          = '';
    var $alt            = '';
    var $filename       = '';
    var $path           = null;
    var $tmp_dir        = TMPDIR;
    var $mime           = '';

    var $uploaded       = '';

    var $sess_var_name  = 'photoFileName';

    function _generate_filename($filename)
    {
        $filename = preg_replace('#(\\\\|/)+#', '', $filename);
        $filename = preg_replace('#^\.+#', '', $filename);
        
        $ext = str_getext($filename);
        $new_filename = empty($ext) ? $filename : substr($filename, 0, -strlen($ext));
        $new_filename = sprintf('%s_%s%s', $new_filename, random(), $ext);
        $new_filename = strtolower($new_filename);

        $filepath = pathjoin($this->get_home(), $new_filename);
        if (is_file($filepath))
        {
            $new_filename = $this->_generate_filename($filename);
        }

        return $new_filename;
    }

    function Image($filepath = null)                               //KONSTRUKTOR
    {
        CoreBase::CoreBase();

        $sessVarName = $this->get_sessVarName();

        if (!is_null($filepath))
        {
            $this->set_data_from_file($filepath);

            unset($_SESSION[$sessVarName]);
        }
        elseif (isset($_SESSION[$sessVarName]))
        {
            $filepath = pathjoin($this->get_tmp_dir(), $_SESSION[$sessVarName]);
            if (is_file($filepath))
            {
                $this->set_data_from_file($filepath);
            }
            else
            {
                $this->error_set(sprintf('Image::Image: _SESSION[\'%s\'] set, but file "%s" missing.',

                    $sessVarName,
                    $filename
                ));
            }
        }
    }

    function set_width($data)
    {
        $this->width = (int)$data;
        return true;
    }
    function set_height($data)
    {
        $this->height = (int)$data;
        return true;
    }
    function set_title($data, $entit = true)
    {
        if ($entit)
        {
            $data = str_entit($data);
        }

        $this->title = (string)$data;
        return true;
    }
    function set_alt($data, $entit = true)
    {
        if ($entit)
        {
            $data = str_entit($data);
        }

        $this->alt = (string)$data;
        return true;
    }
    function set_filename($data)
    {
        $this->filename = (string)$data;
        return true;
    }
    function set_path($data)
    {
        $this->path = (string)$data;
        return true;
    }
    function set_tmp_dir($data)
    {
        if (!is_writeable($data))
        {
            $this->error_set(sprintf('Image::setTmpDir: %s isn\'t writeable.', $data));
            return false;
        }
        $this->tmp_dir = (string)$data;
        return true;
    }
    function set_mime($data)
    {
        $this->mime = (string)$data;
        return true;
    }
    function set_sessVarName($data)
    {
        $this->set_sessVarName((string)$data);
        return true;
    }
    function set_uploaded($data)
    {
        $this->uploaded = (bool)$data;
        return true;
    }

    function set_data_from_file($filepath)
    {
        $filepath = realpath($filepath);
        if (is_file($filepath))
        {
            $pathinfo = pathinfo($filepath);

            $this->set_filename($pathinfo['basename']);
            $this->set_path($pathinfo['dirname']);

            list($img_width, $img_height, $img_type) = each(getimagesize($filepath));
            $this->set_width($img_width);
            $this->set_height($img_height);
            $this->set_mime(image_type_to_mime_type($img_type));

            $this->set_uploaded(true);

            return true;
        }
        else
        {
            $this->error_set(sprintf('Image::SetDataFromFile:: file "%s" not found.', $filepath));
            return false;
        }
    }

    function get_width()
    {
        return $this->width;
    }
    function get_height()
    {
        return $this->height;
    }
    function get_title()
    {
        return $this->title;
    }
    function get_alt()
    {
        return $this->alt;
    }
    function get_filename()
    {
        return $this->filename;
    }
    function get_home()
    {
        return $this->home;
    }
    function get_tmp_dir()
    {
        return $this->tmp_dir;
    }
    function get_mime()
    {
        return $this->mime;
    }
    function get_sessVarName()
    {
        return $this->sess_var_name;
    }
    function get_uploaded()
    {
        return $this->uploaded();
    }

    function upload($fieldname = 'photo')
    {
        if ($this->is_error())
        {
            return false;
        }

        //czy byla proba uploadu i czy zadane pole istnieje
        if (!isset($_FILES[$fieldname]['tmp_name']))
        {
            $this->error_set('Image::Upload: can\'t find $_FILES super array.');
            return false;
        }

        //czy wystapily bledy podczas uploadu
        if ($_FILES[$fieldname]['error'] != UPLOAD_ERR_OK)
        {
            //UPLOAD_ERR_NO_TMP_DIR wprowadzono dopiero w php4.3.0 i 5.0.3
            if (!defined('UPLOAD_ERR_NO_TMP_DIR'))
            {
                define('UPLOAD_ERR_NO_TMP_DIR', 6);
            }

            switch ($_FILES[$fieldname]['error'])
            {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $this->error_set('Image::Upload: uploaded file is too big.');
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $this->error_set('Image::Upload: file was only partially uploaded.');
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $this->error_set('Image::Upload: no file was uploaded.');
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $this->error_set('Image::Upload: no temporary directory was specified.');
                    break;
            }

            return false;
        }

        //czy plik byl uploadowany, czy tez jest proba ataku
        if (!is_uploaded_file($_FILES[$fieldname]['tmp_name']))
        {
            $this->error_set('Image::Upload: ALERT! Possible file upload attack!');
            return false;
        }

        //ustawiamy docelowa nazwe pliku:
        $filename = $this->_generate_filename($_FILES[$fieldname]['name']);
        $this->set_filename($filename);
        //proba przeniesienia pliku do lokalizacji tymczasowej
        if ( !move_uploaded_file(
                $_FILES[$fieldname]['tmp_name'],
                pathjoin($this->get_tmp_dir(), $filename)
        ))
        {
            $this->error_set('Image::Upload:: Some problem occured when moving file.');
            return false;
        }
        //zapisujemy nazwe pliku w sesji:
        $_SESSION[$this->get_sessVarName()] = $filename;
        $this->set_path($this->get_tmp_dir());
        $this->set_uploaded(true);

        return true;
    }
    function move($dest)
    {
        if ($this->is_error())
        {
            return false;
        }
        if (!$this->get_uploaded())
        {
            $this->error_set('Image::Move:: No file was uploaded yet.');
            return false;
        }

        $filename = $this->get_filename();
        $filepath = pathjoin($this->get_path(), $filename);
        
        if (!is_file($filepath))
        {
            $this->error_set(sprintf('Image::Save: file "%s" doesn\'t exists in "%s".', $filename, $this->get_path()));
            return false;
        }
        if (!@rename($tmp_path, pathjoin($dest, $filename)))
        {
            $this->error_set(sprintf('Image::Save: cannot move file "%s" from "%s" to "%s" directory.',

                    $filename,
                    $this->get_path(), 
                    $dest
            ));
            return false;
        }
        $this->set_path(pathjoin($dest, $filename));

        return true;
    }
    function delete()
    {
        if ($this->is_error())
        {
            return false;
        }

        $filepath = pathjoin($this->get_path(), $this->get_filename());
        if (!is_file($filepath))
        {
            $this->error_set(sprintf('Image::Delete: cannot delete file "%s": file not found.', $filepath));
            return false;
        }
        
        if (!@unlink($filepath))
        {
            $this->error_set(sprintf('Image::Delete: cannot delete file %s.', $filepath));
            return false;
        }

        return true;
    }
}

?>
