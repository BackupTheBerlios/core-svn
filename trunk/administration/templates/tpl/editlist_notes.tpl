<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Aktualno�ci - edycja wpis�w</b><br /><br />
<!-- NAME: editlist_notes.tpl -->
<form method="post" action="main.php?p=2&amp;action=multidelete" id="multipleSelected">
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td class="mainListHeader" width="13%">Data</td>
		<td class="mainListHeader" width="5%"></td>
		<td class="mainListHeader" width="37%">Temat Wpisu</td>
		<td class="mainListHeader" width="11%">Autor</td>
		<td class="mainListHeader" width="5%"></td>
		<td class="mainListHeader" width="11%">Aktywna</td>
		<td class="mainListHeader" width="10%">Edycja</td>
		<td class="mainListHeader" width="8%">Usu�</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
	<tr>
		<td class="{ID_CLASS} center">{DATE}</td>
		<td class="{ID_CLASS} center"><input class="selected_note" type="checkbox" name="selected_note[]" value="{ID}" /></td>
		<td class="{ID_CLASS}">{TITLE}</td>
		<td class="{ID_CLASS} center" align="center">{AUTHOR}</td>
		<td class="{ID_CLASS} center"><input class="selected_note" type="checkbox" name="selected_status[]" value="{ID}" /></td>
		<td class="{ID_CLASS} center" align="center">{PUBLISHED}</td>
		<td class="{ID_CLASS} center" align="center"><a href="main.php?p=2&amp;action=show&amp;id={ID}">Edycja</a></td>
		<td class="{ID_CLASS} center" align="center"><a href="main.php?p=2&amp;action=delete&amp;id={ID}">Usu�</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="8">{STRING}</td>
	</tr>
	<tr>
		<td class="addinfo" colspan="3"><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="doit('selected_note[]')">Prze��cz zaznaczenie</a>&nbsp;<img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('multipleSelected').submit()">Usu� zaznaczone wpisy</a></td>
		<td class="addinfo right" colspan="5"><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="doit('selected_status[]')">Prze��cz zaznaczenie</a>&nbsp;<img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('multipleSelected').submit()">Zmie� status wpis�w</a></td>
	</tr>
</table>
</form>
<!-- END: editlist_notes.tpl -->
</div>