<?php
// $Id$

abstract class CEBase extends Exception
{
  public function __construct($message, $code)
  {
    parent::__construct($message, $code);
  }
  protected function toString($class)
  {
    return sprintf('%s::%d:: %s', $class, $this->code, $this->message);
  }
}

class CENotFound extends CEBase
{
  public function __construct($message, $code = null)
  {
    parent::__construct($message, $code);
  }
  public function __toString()
  {
    return $this->toString(__CLASS__);
  }
}

class CEMultiErrors extends CEBase
{
  public function __construct($message, $code = null)
  {
    parent::__construct($message, $code);
  }
  public function __toString()
  {
    return $this->toString(__CLASS__);
  }
}

class CESyntaxError extends CEBase
{
  public function __construct($message, $code = null)
  {
    parent::__construct($message, $code);
  }
  public function __toString()
  {
    return $this->toString(__CLASS__);
  }
}
class CEDBError extends CEBase
{
  public function __construct($message, $code = null)
  {
    parent::__construct($message, $code);
  }
  public function __toString()
  {
    return $this->toString(__CLASS__);
  }
}

// vim: expandtab:shiftwidth=4:softtabstop=4:tabstop=4
?>
