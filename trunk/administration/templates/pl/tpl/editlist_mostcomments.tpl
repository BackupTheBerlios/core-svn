<div id="left">
<img src="templates/{LANG}/images/main.gif"><strong>Komentarze - najczęciej komentowane wpisy</strong><br /><br />
<!-- NAME: editlist_mostcomments.tpl -->
<form method="post" action="main.php?p=2">
<table class="list">
<thead>
	<tr>
		<th width="13%">Data</td>
		<th width="5%"><a
            href="#" onclick="switchChecked('selected_notes[]')"><img
            src="templates/{LANG}/images/ar.gif" /></a></td>
		<th width="50%">Tytuł wpisu</td>
		<th width="12%">Liczba</td>
		<th width="10%">Edycja</td>
		<th width="10%">Usuń</td>
	</tr>
</thead>
<tfoot>
	<tr>
		<td class="addinfo" colspan="6">
            <input type="submit" name="sub_delete" value="Usuń zaznaczone wpisy" onclick="return askChecked('Czy na pewno chcesz usunąć zaznaczone wpisy?', 'selected_notes[]')" />
        </td>
	</tr>
</tfoot>
<tbody>
	<!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
		<td class="{ID_CLASS} center">{DATE}</td>
		<td class="{ID_CLASS} center"><input class="selected_note" type="checkbox" name="selected_notes[]" value="1" /></td>
		<td class="{ID_CLASS}">{TITLE}</td>
		<td class="{ID_CLASS} center">{COMMENTS}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=2&amp;action=show&amp;id={ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=2&amp;action=delete&amp;id={ID}">Usuń</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
	<tr>
		<td id="pagination" colspan="6">{STRING}</td>
	</tr>
</table>
<!-- END: editlist_mostcomments.tpl -->
</div>