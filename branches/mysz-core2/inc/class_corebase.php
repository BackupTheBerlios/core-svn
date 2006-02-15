<?php

require_once 'core_exceptions.php';

abstract class CoreBase
{
  protected errors = array();
  
  public function is_error()
  {
    return (bool)count($this->errors);
  }
  public function error_set($msg, $code)
  {
    $errors[] = array($code, $msg);
  }
  public function error_get($last=true)
  {
    if ($last) return end($this->errors);
    return $this->errors;
  }
  public function error_clear()
  {
    $this->errors = array();
  }
}

?>
