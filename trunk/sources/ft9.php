<?php

$data = $tpl->fetch("MAIN");
fwrite($fd, $data); // save to a file

?>