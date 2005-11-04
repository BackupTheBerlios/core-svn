<div id="left">
<img src="templates/{LANG}/images/main.gif"><strong>Komentarze - edycja wpisów</strong><br /><br />
<!-- NAME: editlist_comments.tpl -->
<form method="post" action="main.php?p=5">
<table class="list">
<thead>
	<tr>
		<th width="13%">Data</th>
        <th width="5%"><a href="#" onclick="switchChecked('selected_comments[]')"><img
            src="templates/pl/images/ar.gif" /></a></th>
		<th width="32%">Treść (fragment)</th>
		<th width="13%">Autor</th>
		<th width="17%">IP</th>
		<th width="10%">Edycja</th>
		<th width="10%">Usuń</th>
	</tr>
</thead>
<tfoot>
	<tr>
		<td id="pagination" colspan="7">
		<!-- IFDEF: PAGINATED -->
		<b>Id� do strony</b>:
		<!-- ELSE -->
		<!-- ENDIF -->
		<!-- IFDEF: MOVE_BACK -->
		<strong><a href="{MOVE_BACK_LINK}">poprzednia</a></strong>
		<!-- ELSE -->
		<!-- ENDIF -->
			{STRING}
		<!-- IFDEF: MOVE_FORWARD -->
		<strong><a href="{MOVE_FORWARD_LINK}">następna</a></strong> 
        <!-- ELSE -->
        <!-- ENDIF -->
		</td>
	</tr>
    <tr>
        <td colspan="7"><input
            type="submit" name="sub_delete" value="Usuń zaznaczone komentarze"
            onclick="return askChecked('Czy na pewno chcesz usunąć zaznaczone komentarze?', 'selected_comments[]')" /></td>
    </tr>
</tfoot>
<tbody>
    <!-- BEGIN DYNAMIC BLOCK: row -->
    <tr>
        <td class="{ID_CLASS} center">{DATE}</td>
        <td class="{ID_CLASS}"><input type="checkbox" name="selected_comments[]" class="selected_note" value="{ID}" /></td>
		<td class="{ID_CLASS}">{TEXT}</td>
		<td class="{ID_CLASS} center">{AUTHOR}</td>
		<td class="{ID_CLASS} center">{AUTHOR_IP}</td>
		<td class="{ID_CLASS} center"><a href="main.php?p=5&amp;action=show&amp;id={ID}">Edycja</a></td>
		<td class="{ID_CLASS} center"><a href="main.php?p=5&amp;action=delete&amp;id={ID}">Usuń</a></td>
	</tr>
	<!-- END DYNAMIC BLOCK: row -->
</tbody>
</table>
</form>
<!-- END: editlist_comments.tpl -->
</div>