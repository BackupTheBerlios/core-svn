<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// $Id$
// $HeadURL$

class CoreController
{
    public function __construct()
    {
        $fulluri = $_SERVER['REQUEST_URI'];
        $req = substr($fulluri, strlen(HTTPPATH)+1);
        print_r(explode('/', $req));
    }
}

?>
