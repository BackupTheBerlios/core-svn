<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>U�ytkownicy - edycja/usuwanie</b><br /><br />
<!-- NAME: editlist_users.tpl -->
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td class="mainListHeader" width="6%">Id</td>
		<td class="mainListHeader" width="20%">Login</td>
		<td class="mainListHeader" width="32%">Adres e-mail</td>
		<td class="mainListHeader" width="11%">Poziom</td>
		<td class="mainListHeader" width="11%">Aktywny</td>
		<td class="mainListHeader" width="10%">Edycja</td>
		<td class="mainListHeader" width="10%">Usu�</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{USER_ID}</td>
		<td class="{ID_CLASS}">{NAME}</td>
		<td class="{ID_CLASS}">{EMAIL}</td>
		<td class="{ID_CLASS} center"><b>{LEVEL_DOWN} {LEVEL} {LEVEL_UP}</b></td>
		<td class="{ID_CLASS} center">{STATUS}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=13&amp;action=show&amp;id={USER_ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=13&amp;action=delete&amp;id={USER_ID}">Usu�</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</table>
<!-- END: editlist_users.tpl -->
</div>