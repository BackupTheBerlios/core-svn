<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Komentarze - edycja wpis�w</b><br /><br />
<!-- NAME: editlist_comments.tpl -->
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td class="mainListHeader" width="13%">Data</td>
		<td class="mainListHeader" width="37%">Tre�� (fragment)</td>
		<td class="mainListHeader" width="13%">Autor</td>
		<td class="mainListHeader" width="17%">IP</td>
		<td class="mainListHeader" width="10%">Edycja</td>
		<td class="mainListHeader" width="10%">Usu�</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{DATE}</td>
		<td class="{ID_CLASS}">{TEXT}</td>
		<td class="{ID_CLASS} center">{AUTHOR}</td>
		<td class="{ID_CLASS} center">{AUTHOR_IP}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=5&amp;action=show&amp;id={ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=5&amp;action=delete&amp;id={ID}">Usu�</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="6">{STRING}</td>
	</tr>
</table>
<!-- END: editlist_comments.tpl -->
</div>