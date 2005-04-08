<div align="left"><img src="layout/main.gif" width="14" height="14" align="middle" hspace="2"><b>Komentarze - edycja wpisów</b><br /><br /></div>
<!-- NAME: editlist_comments.tpl -->
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td id="mainListHeader" width="13%">Data</td>
		<td id="mainListHeader" width="37%">Tre¶æ (fragment)</td>
		<td id="mainListHeader" width="13%">Autor</td>
		<td id="mainListHeader" width="17%">IP</td>
		<td id="mainListHeader" width="10%">Edycja</td>
		<td id="mainListHeader" width="10%">Usuñ</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td {ID_CLASS} align="center">{DATE}</td>
		<td {ID_CLASS}>{TEXT}</td>
		<td {ID_CLASS} align="center">{AUTHOR}</td>
		<td {ID_CLASS} align="center">{AUTHOR_IP}</td>
		<td {ID_CLASS} align="center"><a href="show,{ID},5,edit.html">Edycja</a></td>
		<td {ID_CLASS} align="center"><a href="delete,{ID},5,edit.html">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="6">{STRING}</td>
	</tr>
</table>
<!-- END: editlist_comments.tpl -->