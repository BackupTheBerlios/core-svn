<div>
<!-- IFDEF: SHOW_COMMENT_FORM -->
Przy wype³nianiu formularza pole e-mail jest ca³kowicie opcjonalne, co znaczy ¿e nie wymagam podania adresu poczty mailowej.
Je¶li jednak ju¿ zdecydujesz siê to zrobiæ, to muszê zaznaczyæ, ¿e Twój adres nie bêdzie widoczny bezpo¶rednio przy komentarzu, a
przechowywany wy³±cznie w bazie danych w celach czysto informacyjnych.<br /><br />
<b><a class="date" href="{PERMA_LINK}">{NEWS_TITLE}</a></b> - link bezpo¶redni<br /><br />

<script type="text/javascript" src="templates/main/js/comments.js"></script>
<form method="post" action="{FORM_LINK}" name="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="20%" height="25" valign="top">Autor:</td>
		<td width="80\" class="nav" align="left">
		<input type="text" name="author" size="30" maxlength="130" value="{COMMENT_AUTHOR}">
		</td>
	</tr>
	<tr>
		<td width="20%" height="0" valign="top"></td>
		<td width="80%" align="left">
		<input type="hidden" name="comments_id" size="30" maxlength="130" value="{NEWS_ID}">
		</td>
	</tr>
	<tr>
		<td width="20%" height="0" valign="top"></td>
		<td width="80%" align="left">
		<input type="hidden" name="id" size="30" maxlength="130" value="{NEWS_ID}">
		</td>
	</tr>
	<tr>
		<td width="20%" height="25" valign="top">E-mail:</td>
		<td width="80" align="left">
		<input type="text" name="email" size="30" maxlength="255">&nbsp;.opcjonalnie
		</td>
	</tr>
	<tr>
		<td class="form" width="20%">Znaki specjalne:</td>
		<td class="form" width="80%" align="left" valign="top" colspan="2" style="padding-bottom: 3px;">
		<input type="button" class="button" name="addbbcode0" value=" b " style="font-weight:bold; width: 30px" onClick="bbstyle(0)" />
		<input type="button" class="button" name="addbbcode2" value=" i " style="font-style:italic; width: 30px" onClick="bbstyle(2)" />
		<input type="button" class="button" name="addbbcode4" value=" u " style="text-decoration: underline; width: 30px" onClick="bbstyle(4)" />
		<input type="button" class="button" name="addbbcode6" value=" abbr " style="width: 40px" onClick="bbstyle(6)" />
		<input type="button" class="button" name="addbbcode8" value=" quote " style="width: 45px" onClick="bbstyle(8)" />
		<input type="button" class="button" name="addbbcode10" value=" link " style="width: 40px" onClick="bbstyle(10)" />
		&nbsp;<a href="javascript:bbstyle(-1)">Zamknij Tagi HTML</a>
		</td>
	</tr>
	<tr>
		<td width="20%" height="25" valign="top">Tre¶æ komentarza:</td>
		<td width="80" class="nav" align="left">
		<textarea wrap="virtual" tabindex="3" name="text" cols="65" rows="12" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{QUOTE}</textarea>
		</td>
	</tr>
	<tr>
		<td class="align_right" width="100%" height="25" colspan="2"><a href="#" onclick="checkForm()"><b>. dodaj komentarz</b></a>&nbsp;&nbsp;</td>
	</tr>
</table>
</form>
<!-- ELSE -->
<div class="center">
{CONFIRMATION}<br />
<a href="{SUBMIT_LINK}">Wróæ do komentarzy...
</div>
<!-- ENDIF -->
</div>