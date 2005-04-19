<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Komentarze - najczê¶ciej komentowane wpisy</b><br /><br />
<!-- NAME: editlist_mostcomments.tpl -->
<form method="post" action="main.php?p=2&amp;action=multidelete" id="multipleSelected">
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td class="mainListHeader" width="13%">Data</td>
		<td class="mainListHeader" width="5%"></td>
		<td class="mainListHeader" width="50%">Tytu³ wpisu</td>
		<td class="mainListHeader" width="12%">Liczba</td>
		<td class="mainListHeader" width="10%">Edycja</td>
		<td class="mainListHeader" width="10%">Usuñ</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{DATE}</td>
		<td class="{ID_CLASS} center"><input class="selected_note" type="checkbox" name="selected_note[]" value="{ID}" /></td>
		<td class="{ID_CLASS}">{TITLE}</td>
		<td class="{ID_CLASS} center">{COMMENTS}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=2&amp;action=show&amp;id={ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=2&amp;action=delete&amp;id={ID}">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="6">{STRING}</td>
	</tr>
	<tr>
		<td class="addinfo" colspan="6"><img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="doit()">Prze³±cz zaznaczenie</a>&nbsp;<img src="templates/images/ar.gif" width="10" height="9" />&nbsp; <a href="#" onclick="document.getElementById('multipleSelected').submit()">Usuñ zaznaczone wpisy</a>
	</tr>
</table>
<!-- END: editlist_mostcomments.tpl -->
</div>