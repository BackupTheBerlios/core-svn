<script type="text/javascript" src="./templates/js/textarea.js"></script>

<form enctype="multipart/form-data" method="post" name="post" action="edit,{ID},2,edit.html">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right">Tytu³ wpisu:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2"><input type="text" name="title" size="30" maxlength="255" value="{TITLE}" /></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Data:&nbsp;</td>
		<td class="form" width="234" align="left"><input type="text" name="date" size="30" maxlength="255" value="{DATE}" /></td>
		<td class="form" width="130" align="left"><input type="checkbox" name="date" value="1" align="top" />&nbsp;- aktualna data</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Autor wpisu:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2"><input type="text" name="author" size="30" maxlength="255" value="{AUTHOR}" /></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Znaki specjalne:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2">
		<input type="button" class="button" accesskey="b" name="addbbcode0" value=" B " style="font-weight:bold; width: 30px" onClick="bbstyle(0)" onMouseOver="helpline('b')" />
		<input type="button" class="button" accesskey="i" name="addbbcode2" value=" i " style="font-style:italic; width: 30px" onClick="bbstyle(2)" onMouseOver="helpline('i')" />
		<input type="button" class="button" accesskey="u" name="addbbcode4" value=" u " style="text-decoration: underline; width: 30px" onClick="bbstyle(4)" onMouseOver="helpline('u')" />
		<input type="button" class="button" accesskey="t" name="addbbcode6" value=" abbr " style="width: 40px" onClick="bbstyle(6)" onMouseOver="helpline('t')" />
		&nbsp;<a href="javascript:bbstyle(-1)" onMouseOver="helpline('a')">Zamknij Tagi HTML</a>
		</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2">
		<input type="text" name="helpbox" size="45" maxlength="100" style="width:350px; border: 0px; padding: 0px;" class="helpline" value="Rada: Style mog± byæ stosowane szybko do zaznaczonego tekstu" />
		</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right" valign="top">Tre¶æ wpisu:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2"><textarea name="text" cols="60" rows="12" style="background: url(./layout/bg3.jpg); BACKGROUND-REPEAT: repeat-x;">{TEXT}</textarea></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Publikowana:&nbsp;</td>
		<td class="form" width="224" align="left">{CHECKBOX_YES}- tak&nbsp;{CHECKBOX_NO}- nie</td>
	</tr>
	<tr>
		<td colspan="2" class="align_right"><img src="layout/arrow_blue.gif" alt="Core | CMS" align="middle" height="5" hspace="5" vspace="2" width="5" /><a href="javascript:document.forms[0].submit()">zmodyfikuj wpis</a></td>
	</tr>
</table>
</form>