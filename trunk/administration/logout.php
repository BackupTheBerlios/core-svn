<?php
// $Id$

session_register("login");
session_register("loggedIn");

if(!isset($_SESSION["loggedIn"])){
    
    header("Location: index.php");
    exit;
}

unset($_SESSION["login"]);
unset($_SESSION["loggedIn"]);

session_destroy();

header("Location: index.php");
exit;
?>
