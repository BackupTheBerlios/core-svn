<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Kategorie - edycja/usuwanie</b><br /><br />
<!-- NAME: editlist_category.tpl -->
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td id="mainListHeader" width="13%">Id</td>
		<td id="mainListHeader" width="15%">Kategoria</td>
		<td id="mainListHeader" width="42%">Opis kategorii</td>
		<td id="mainListHeader" width="10%">Liczba</td>
		<td id="mainListHeader" width="10%">Edycja</td>
		<td id="mainListHeader" width="10%">Usuñ</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td {ID_CLASS} align="center">{CATEGORY_ID}</td>
		<td {ID_CLASS} align="center">{CATEGORY_NAME}</td>
		<td {ID_CLASS}>{CATEGORY_DESC}</td>
		<td {ID_CLASS} align="center">{COUNT}</td>
		<td {ID_CLASS} align="center"><a href="show,{CATEGORY_ID},9,edit.html">Edycja</a></td>
		<td {ID_CLASS} align="center"><a href="delete,{CATEGORY_ID},9,edit.html">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="6">{STRING}</td>
	</tr>
</table>
<!-- END: editlist_category.tpl -->
</div>