<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Kategorie - edycja/usuwanie</b><br /><br />
<!-- NAME: editlist_links.tpl -->
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td class="mainListHeader" width="13%">Id</td>
		<td class="mainListHeader" width="30%">Tytu³</td>
		<td class="mainListHeader" width="37%">URI</td>
		<td class="mainListHeader" width="10%">Edycja</td>
		<td class="mainListHeader" width="10%">Usuñ</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{LINK_ID}</td>
		<td class="{ID_CLASS}">{LINK_NAME}</td>
		<td class="{ID_CLASS}">{LINK_URL}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=12&amp;action=show&amp;id={LINK_ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=12&amp;action=delete&amp;id={LINK_ID}">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</table>
<!-- END: editlist_links.tpl -->
</div>