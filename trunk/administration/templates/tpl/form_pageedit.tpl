<script type="text/javascript" src="../js_functions/textarea.js"></script>

<form enctype="multipart/form-data" method="post" name="post" action="edit,{ID},4,edit.html" id="formPage">
<table width="100%" align="left">
	<tr>
		<td class="form" width="80" align="right">Tytu� wpisu:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2"><input type="text" name="title" size="30" maxlength="255" value="{TITLE}" /></td>
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
		<input type="text" name="helpbox" size="45" maxlength="100" style="width:350px; border: 0px; padding: 0px;" class="helpline" value="Rada: Style mog� by� stosowane szybko do zaznaczonego tekstu" />
		</td>
	</tr>
	<tr>
		<td class="form" width="80" align="right" valign="top">Tre�� strony:&nbsp;</td>
		<td class="form" width="364" align="left" valign="top" colspan="2"><textarea name="text" cols="60" rows="12" style="background: url(./templates/images/bg3.jpg); BACKGROUND-REPEAT: repeat-x;">{TEXT}</textarea></td>
	</tr>
	<tr>
		<td class="form" width="80" align="right">Publikowana:&nbsp;</td>
		<td class="form" width="224" align="left">{CHECKBOX_YES}- tak&nbsp;{CHECKBOX_NO}- nie</td>
	</tr>
	<tr>
      <td colspan="2" class="align_right"><img src="templates/images/arrow_blue.gif" alt="Core | CMS" align="middle" height="5" hspace="5" vspace="2" width="5" /><a href="#" onclick="document.getElementById('formPage').submit()">zmodyfikuj wpis</a></td>
	</tr>
</table>
</form>