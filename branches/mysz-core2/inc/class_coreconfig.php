<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4

/**
 * Provide access to database config table
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
 * @version    SVN: $Id: class_corebase.php 1246 2006-02-15 12:37:04Z mysz $
 * @link       http://core-cms.com/
 */

/**
 * Class who provides interface to core_config databse table.
 *
 * Class use PDO prepared statements.
 *
 * @category   Classes
 * @package    Classes
 * @author     Core Dev Team <core@core-cms.com>
 * @copyright  2006 Core Dev Team
 * @license    http://www.fsf.org/copyleft/gpl.html
 * @license    http://www.gnu.org.pl/text/licencja-gnu.html
 * @version    SVN: $Id: class_corebase.php 1246 2006-02-15 12:37:04Z mysz $
 * @link       http://core-cms.com/
 */
class CoreConfig extends CoreBase {

  /**
   * Prepared query for getting properties.
   * 
   * @var object
   */
  protected $stmt_get = null;

  /**
   * Prepared query for setting properties.
   * 
   * @var object
   */
  protected $stmt_set = null;

  /**
   * Constructor
   *
   * Creates prepared statements with acquire bindings.
   *
   * @return void
   *
   * @access public
   */
  public function __construct()
  {
    parent::__construct();

    $query = sprintf("
      SELECT
        `value`
      FROM
        %s
      WHERE
        `key` = :key",

      TBL_CONFIG
    );
    $this->stmt_get = $this->db->prepare($query);

    $query = sprintf("
      UPDATE
        %s
      SET
        `value` = :value
      WHERE
      `key` = :key",
      
      TBL_CONFIG
    );
    $this->stmt_set = $this->db->prepare($query);
  }

  /**
   * Overloaded getter
   *
   * @param string $key name of property
   *
   * @return string value of property
   * @throws CEDBError if any database error
   * @throws CENotFound if property not found
   *
   * @access public
   */
  public function __get($key)
  {
    try {
      $this->stmt_get->execute(array(':key'=>$key));
    } catch (PDOException $e) {
      throw new CEDBError($e->getMessage());
    }

    $row = $this->stmt_get->fetch();
    if (!$row) {
      throw new CENotFound(sprintf('Config property "%s" not found.', $key));
    }

    return unserialize($row['value']);
  }

  /**
   * Overloaded setter
   *
   * @param string $key   name of property
   * @param string $value value of property
   *
   * @throws CEDBError if any database error
   *
   * @access public
   */
  public function __set($key, $value)
  {
    try {
      $this->stmt_set->execute(array('key'=>$key, 'value'=>serialize($value)));
    } catch (PDOException $e) {
      throw new CEDBError($e->getMessage());
    }
  }
}

?>

