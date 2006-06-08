<?php

// vim: expandtab shiftwidth=4 softtabstop=4 tabstop=4
// $Id: index.php 1303 2006-03-08 20:31:39Z mysz $
// $HeadURL: svn://svn.berlios.de/core/branches/mysz-core2/index.php $

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
