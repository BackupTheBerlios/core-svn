<?php

// [b] i [/b] dla tekstu pogrubionego.
$text = preg_replace('/\[b\]([^\"]+)\[\/(b)\]/', '<b>\\1</\\2>', $text);
		
// [i] i [/i] dla tekstu pochylonego.
$text = preg_replace('/\[i\]([^\"]+)\[\/(i)\]/', '<i>\\1</\\2>', $text);
		
// [u] i [/u] dla tekstu podkre¶lonego.
$text = preg_replace('/\[u\]([^\"]+)\[\/(u)\]/', '<u>\\1</\\2>', $text);
		
// [quote] i [/quote] dla tekstu cytowanego.
$text = preg_replace('/\[quote\]([^\"]+)\[\/(quote)\]/', '<div class="quote">\\1</div>', $text);
		
// [abbr] i [/abbr] dla akronimów.
$text = preg_replace('/\[abbr=([^\"]+)\]([^\"]+)\[\/(abbr)\]/', '<abbr title="\\1">\\2</\\3>', $text);
		
// [link] i [/link] dla odsy³aczy.
$text = preg_replace('/\[link=([^\"]+)\]([^\"]+)\[\/(link)\]/', '<a href="\\1" target="_blank">\\2</a>', $text);

?>