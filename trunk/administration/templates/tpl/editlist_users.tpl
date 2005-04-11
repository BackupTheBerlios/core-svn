<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>U¿ytkownicy - edycja/usuwanie</b><br /><br />
<!-- NAME: editlist_users.tpl -->
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td id="mainListHeader" width="13%">Id</td>
		<td id="mainListHeader" width="20%">Login</td>
		<td id="mainListHeader" width="32%">Adres e-mail</td>
		<td id="mainListHeader" width="15%">Aktywny</td>
		<td id="mainListHeader" width="10%">Edycja</td>
		<td id="mainListHeader" width="10%">Usuñ</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td {ID_CLASS} align="center">{USER_ID}</td>
		<td {ID_CLASS} align="center">{USER_NAME}</td>
		<td {ID_CLASS}>{USER_EMAIL}</td>
		<td {ID_CLASS} align="center">{USER_STATUS}</td>
		<td {ID_CLASS} align="center"><a href="main.php?p=13&amp;action=show&amp;id={USER_ID}">Edycja</a></td>
		<td {ID_CLASS} align="center"><a href="main.php?p=13&amp;action=delete&amp;id={USER_ID}">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</table>
<!-- END: editlist_users.tpl -->
</div>