<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Kategorie - edycja/usuwanie</b><br /><br />
<!-- NAME: editlist_links.tpl -->
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td id="mainListHeader" width="13%">Id</td>
		<td id="mainListHeader" width="30%">Tytu³</td>
		<td id="mainListHeader" width="37%">URI</td>
		<td id="mainListHeader" width="10%">Edycja</td>
		<td id="mainListHeader" width="10%">Usuñ</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td {ID_CLASS} align="center">{LINK_ID}</td>
		<td {ID_CLASS} align="center">{LINK_NAME}</td>
		<td {ID_CLASS}>{LINK_URL}</td>
		<td {ID_CLASS} align="center"><a href="main.php?p=12&amp;action=show&amp;id={LINK_ID}">Edycja</a></td>
		<td {ID_CLASS} align="center"><a href="main.php?p=12&amp;action=delete&amp;id={LINK_ID}">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</table>
<!-- END: editlist_links.tpl -->
</div>