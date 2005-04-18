<div id="left">
<img src="templates/images/main.gif" width="14" height="14" align="middle" hspace="2"><b>Kategorie - edycja/usuwanie</b><br /><br />
<!-- NAME: editlist_category.tpl -->
<table align="left" cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td class="mainListHeader" width="13%">Id</td>
		<td class="mainListHeader" width="15%">Kategoria</td>
		<td class="mainListHeader" width="42%">Opis kategorii</td>
		<td class="mainListHeader" width="10%">Liczba</td>
		<td class="mainListHeader" width="10%">Edycja</td>
		<td class="mainListHeader" width="10%">Usuñ</td>
	</tr>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{CATEGORY_ID}</td>
		<td class="{ID_CLASS} center">{CATEGORY_NAME}</td>
		<td class="{ID_CLASS}">{CATEGORY_DESC}</td>
		<td class="{ID_CLASS} center">{COUNT}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=9&amp;action=show&amp;id={CATEGORY_ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=9&amp;action=delete&amp;id={CATEGORY_ID}">Usuñ</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="6">{STRING}</td>
	</tr>
</table>
<!-- END: editlist_category.tpl -->
</div>