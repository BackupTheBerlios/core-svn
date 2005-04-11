<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Komentarze - najczê¶ciej komentowane wpisy</b><br /><br />
<!-- NAME: editlist_mostcomments.tpl -->
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td id="mainListHeader" width="13%">Data</td>
		<td id="mainListHeader" width="55%">Tytu³ wpisu</td>
		<td id="mainListHeader" width="12%">Liczba</td>
		<td id="mainListHeader" width="10%">Edycja</td>
		<td id="mainListHeader" width="10%">Usuñ</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td {ID_CLASS} align="center">{DATE}</td>
		<td {ID_CLASS}>{TITLE}</td>
		<td {ID_CLASS} align="center">{COMMENTS}</td>
		<td {ID_CLASS} align="center"><a href="show,{ID},2,edit.html">Edycja</a></td>
		<td {ID_CLASS} align="center"><a href="delete,{ID},2,edit.html">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="5">{STRING}</td>
	</tr>
</table>
<!-- END: editlist_mostcomments.tpl -->
</div>