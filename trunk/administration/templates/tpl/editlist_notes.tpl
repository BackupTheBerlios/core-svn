<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Aktualno¶ci - edycja wpisów</b><br /><br />
<!-- NAME: editlist_notes.tpl -->
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td class="mainListHeader" width="13%">Data</td>
		<td class="mainListHeader" width="43%">Temat Wpisu</td>
		<td class="mainListHeader" width="12%">Autor</td>
		<td class="mainListHeader" width="12%">Aktywna</td>
		<td class="mainListHeader" width="10%">Edycja</td>
		<td class="mainListHeader" width="10%">Usuñ</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
	<tr>
		<td class="{ID_CLASS} center">{DATE}</td>
		<td class="{ID_CLASS}">{TITLE}</td>
		<td class="{ID_CLASS} center" align="center">{AUTHOR}</td>
		<td class="{ID_CLASS} center" align="center">{PUBLISHED}</td>
		<td class="{ID_CLASS} center" align="center"><a href="main.php?p=2&amp;action=show&amp;id={ID}">Edycja</a></td>
		<td class="{ID_CLASS} center" align="center"><a href="main.php?p=2&amp;action=delete&amp;id={ID}">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="6">{STRING}</td>
	</tr>
</table>
<!-- END: editlist_notes.tpl -->
</div>