<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Users - edit/delete</b><br /><br />
<!-- NAME: editlist_users.tpl -->
<table class="list">
	<tr>
		<td class="mainListHeader" width="6%">Id</td>
		<td class="mainListHeader" width="20%">Login</td>
		<td class="mainListHeader" width="32%">E-mail address</td>
		<td class="mainListHeader" width="11%">Level</td>
		<td class="mainListHeader" width="11%">Active</td>
		<td class="mainListHeader" width="10%">Edit</td>
		<td class="mainListHeader" width="10%">Delete</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{USER_ID}</td>
		<td class="{ID_CLASS}">{NAME}</td>
		<td class="{ID_CLASS}">{EMAIL}</td>
		<td class="{ID_CLASS} center">
		<strong>
		<!-- IFDEF: PRIVILEGE_DOWN -->
		&nbsp;<a href="main.php?p=13&amp;plevel=down&amp;id={USER_ID}">-</a>
		<!-- ELSE -->
		<!-- ENDIF -->
		{LEVEL}
		<!-- IFDEF: PRIVILEGE_UP -->
		<a href="main.php?p=13&amp;plevel=up&amp;id={USER_ID}">+</a>
		<!-- ELSE -->
		<!-- ENDIF -->
		</strong>
		</td>
		<td class="{ID_CLASS} center">{STATUS}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=13&amp;action=show&amp;id={USER_ID}">Edit</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=13&amp;action=delete&amp;id={USER_ID}">Delete</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</table>
<!-- END: editlist_users.tpl -->
</div>