<div align="left"><img src="layout/main.gif" width="14" height="14" align="middle" hspace="2"><b>U�ytkownicy - edycja/usuwanie</b><br /><br /></div>
<!-- NAME: editlist_users.tpl -->
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td id="mainListHeader" width="13%">Id</td>
		<td id="mainListHeader" width="20%">Login</td>
		<td id="mainListHeader" width="32%">Adres e-mail</td>
		<td id="mainListHeader" width="15%">Aktywny</td>
		<td id="mainListHeader" width="10%">Edycja</td>
		<td id="mainListHeader" width="10%">Usu�</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td {ID_CLASS} align="center">{USER_ID}</td>
		<td {ID_CLASS} align="center">{USER_NAME}</td>
		<td {ID_CLASS}>{USER_EMAIL}</td>
		<td {ID_CLASS} align="center">{USER_STATUS}</td>
		<td {ID_CLASS} align="center"><a href="show,{USER_ID},13,edit.html">Edycja</a></td>
		<td {ID_CLASS} align="center"><a href="delete,{USER_ID},13,edit.html">Usu�</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</table>
<!-- END: editlist_users.tpl -->