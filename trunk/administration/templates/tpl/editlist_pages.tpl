<div align="left"><img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Zarz±dzanie stronami - edycja</b><br /><br /></div>
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td id="mainListHeader" width="7%">Id</td>
		<td id="mainListHeader" width="61%">Tytu³ strony</td>
		<td id="mainListHeader" width="12%">Aktywna</td>
		<td id="mainListHeader" width="10%">Edycja</td>
		<td id="mainListHeader" width="10%">Usuñ</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
	<tr>
		<td {ID_CLASS} align="center">{ID}</td>
		<td {ID_CLASS}>{TITLE}</td>
		<td {ID_CLASS} align="center">{PUBLISHED}</td>
		<td {ID_CLASS} align="center"><a href="show,{ID},4,edit.html">Edycja</a></td>
		<td {ID_CLASS} align="center"><a href="delete,{ID},4,edit.html">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</table>