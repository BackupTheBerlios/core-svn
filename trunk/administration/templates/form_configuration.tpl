<img src="layout/main.gif" width="14" height="14" align="middle" hspace="2"><b>Konfiguracja - edycja/modyfikacja ustawieñ core &copy;</b><br /><br />
<form enctype="multipart/form-data" method="post" action="note.modifyconfig">
<table width="100%" align="left">
	<tr>
		<td class="form" width="200" align="right">Liczba postów na stronê:&nbsp;</td>
		<td class="form" width="164" align="left" valign="top"><input type="text" name="mainposts_per_page" size="5" maxlength="15" value="{MAINPOSTS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td class="form" width="200" align="right">Tytu³ strony:&nbsp;</td>
		<td class="form" width="164" align="left" valign="top"><input type="text" name="title_page" size="15" maxlength="15" value="{TITLE_PAGE}" /></td>
	</tr>
	<tr>
		<td class="form" width="200" align="right">Liczba postów na stronê(administracja):&nbsp;</td>
		<td class="form" width="164" align="left" valign="top"><input type="text" name="editposts_per_page" size="5" maxlength="15" value="{EDITPOSTS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td class="form" width="364" align="left" valign="top" colspan="2"><br /><a href="javascript:document.forms[0].submit()">zapisz ustawienia</a></td>
	</tr>
</table>
</form>