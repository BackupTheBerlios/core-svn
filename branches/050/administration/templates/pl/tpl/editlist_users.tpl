<div id="left">
<img src="templates/{LANG}/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>U�ytkownicy - edycja/usuwanie</b><br /><br />
<!-- NAME: editlist_users.tpl -->
<table class="list">
<thead>
	<tr>
		<th width="6%">Id</th>
		<th width="20%">Login</th>
		<th width="32%">Adres e-mail</th>
		<th width="11%">Poziom</th>
		<th width="11%">Aktywny</th>
		<th width="10%">Edycja</th>
		<th width="10%">Usu�</th>
	</tr>
</thead>
<tfoot>
</tfoot>
<tbody>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{USER_ID}</td>
		<td class="{ID_CLASS}">{NAME}</td>
		<td class="{ID_CLASS}">{EMAIL}</td>
		<td class="{ID_CLASS} center">
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
		<td class="{ID_CLASS} center"><a href="main.php?p=13&amp;action=show&amp;id={USER_ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=13&amp;action=delete&amp;id={USER_ID}">Usu�</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</tbody>
</table>
<!-- END: editlist_users.tpl -->
</div>
