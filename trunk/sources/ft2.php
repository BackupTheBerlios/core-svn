<?php

// ¶cie¿ka do katalogu z plikami szablonami
$tpl = new FastTemplate("/path/to/templates");

$tpl->define(array( 'main'  =>"main.html",
                    'footer'=>"footer.html"));

?>