<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Provide access to open/write files
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
 * @version    SVN: $Id: class_coreconfig.php 1259 2006-02-17 01:33:25Z mysz $
 * @link       http://core-cms.com/
 */

/**
 * Class who provides access to open/write files.
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_coreconfig.php 1259 2006-02-17 01:33:25Z mysz $
 * @link       http://core-cms.com/
 */
abstract class CoreFile extends CoreBase {
    
    /**
     * File handler
     *
     * @var object
     * @access protected
     */
    protected $file;
    
    
    /**
     * Constructor
     *
     * Fill object properties from data in param, or set actual date and
     * time in proper properties.
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    
    /**
     * Open file and puts data in file handler $this->file
     * 
     * @param string $input_file name of file to be open
     *
     * @return boolean if $throw == false
     * @throws CEFile Error instead of returning bool ($throw decides)
     *
     * @access protected
     */
    protected function open($input_file)
    {
        if (!file_exists($input_file)) {
            throw new CEFileError(sprintf('File: "%s" not found.', $input_file));
        } else {
            $this->file = substr(file_get_contents($input_file), 0, -1);
        }
    }
}

?>